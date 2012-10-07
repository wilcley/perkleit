<?php
require_once("settings.php");

//setup database connection (used by files that include this one)
$db = new mysqli(DBSettings::host, DBSettings::username, DBSettings::password, DBSettings::database);
if ($db->connect_errno) {
    die("Could not connect to database");
}

// Enum-like classes for code readability
abstract class SpecialTile {
    const none = 0;
    const start = 1;
    const finish = 2;
}

// boosters are not yet implemented in this version
abstract class Boosters {
    const none = 0;
    const headlamp = 1;
    const rails = 2;
}

abstract class PlayerStatus {
    const none = 0;
    const normal = 1;
    const outOfMoves = 2;
    const reachedFinish = 3;
}

class TileType {
    public $open;
    public $special = SpecialTile::none;
    public $booster = Boosters::none;
    
    function __construct($open) {
        $this->open = (boolean) $open;
    }
}

class Map {
    private $tiles;
    private $maxMoves;
    private $startX;
    private $startY;
    
    function __construct($map_id) {
    	$db = new mysqli(DBSettings::host, DBSettings::username, DBSettings::password, DBSettings::database);
    	if ($db->connect_errno) {
    		die("Could not connect to database");
    	}
    	
    	// define tile types
    	$tileTypes = array();
    	$result_tt = $db->query("SELECT * FROM tile_types");
    	while ($row = $result_tt->fetch_assoc()) {
    		$tileTypes[$row['tile_type_id']] = new TileType($row['open']);
    		$tileTypes[$row['tile_type_id']]->special = (int) $row['special'];
    	}
    	
    	// get tiles for this map
    	$result_t = $db->query("SELECT * FROM tile WHERE map_id=$map_id");
    	while ($row = $result_t->fetch_assoc()) {
    		$this->tiles[$row['y']][$row['x']] = $tileTypes[$row['tile_type_id']];
    	}
    	
    	// set maximum number of moves
    	$result_m = $db->query("SELECT * FROM map WHERE map_id=$map_id");
    	if ($row = $result_m->fetch_assoc()){
    		$this->maxMoves = (int) $row['max_moves'];
    		$this->startX = (int) $row['startX'];
    		$this->startY = (int) $row['startY'];
    	}
    }
    
    public function width() {
        return count($this->tiles[0]);
    }
    
    public function height() {
        return count($this->tiles);
    }
    
    public function adjacent_tiles($x, $y) {
        $ret = array();
        
        // above
        if (isset($this->tiles[$y-1][$x])) {
            $ret[] = $this->tile_to_array($x, $y-1);
        }
        
        // below
        if (isset($this->tiles[$y+1][$x])) {
            $ret[] = $this->tile_to_array($x, $y+1);
        }
        
        // left
        if (isset($this->tiles[$y][$x-1])) {
            $ret[] = $this->tile_to_array($x-1, $y);
        }
        
        // right
        if (isset($this->tiles[$y][$x+1])) {
            $ret[] = $this->tile_to_array($x+1, $y);
        }
        
        return $ret;
    }
    
    // returns array with tile position and properties
    // assumes that inputs are valid for this map
    public function tile_to_array($x, $y) {
        return array( 'x' => $x,
                      'y' => $y,
                      'open' => $this->tiles[$y][$x]->open,
                      'special' => $this->tiles[$y][$x]->special
                    );
    }
    
        
    public function getTile($x, $y) {
        return $this->tiles[$y][$x];
    }
    
    public function getMaxMoves(){
        return $this->maxMoves;
    }
    
    public function getStartX(){
    	return $this->startX;
    }
    
    public function getStartY(){
    	return $this->startY;
    }
    
}

class Player {
    public $map;
    private $movesRemaining;
    private $positionX;
    private $positionY;
    private $hash;
    private $status = PlayerStatus::none;
    
    function __construct($playerHash) {
        $db = new mysqli(DBSettings::host, DBSettings::username, DBSettings::password, DBSettings::database);
        if ($db->connect_errno) {
            die("Could not connect to database");
        }
    
        $result = $db->query("SELECT * FROM player WHERE player_hash = '$playerHash'");
        if ($row = $result->fetch_assoc()) {
            $this->map = new Map($row['map_id']);
            $this->movesRemaining = $row['moves_remaining'];
            $this->positionX = $row['x'];
            $this->positionY = $row['y'];
            $this->hash = $playerHash;
            $this->status = PlayerStatus::normal;
        } else {
            throw new OutOfBoundsException("Invalid player hash.");
        }
    }
    
    // validates move and updates database. returns boolean indicating success of move
    public function move($newX, $newY) {
        // validate that move is valid
        
        $newTile = $this->map->getTile($newX, $newY);
        
        // check if it's open
        if ($newTile->open) {
        
            // check if adjacent to current tile
            if ( abs($newX - $this->positionX) <= 1 &&
                 abs($newY - $this->positionY) <= 1 ) {
                
                // make sure this player still has moves
                if ($this->movesRemaining > 0) {
                
                    // decrement move counter if not one of the starting tiles
                    if($newTile->special !== SpecialTile::start) {
                        $this->movesRemaining--;
                    }
                    
                    // set new positions
                    $this->positionX = $newX;
                    $this->positionY = $newY;
                    
                    // update database
                    $db = new mysqli(DBSettings::host, DBSettings::username, DBSettings::password, DBSettings::database);
                    if ($db->connect_errno) {
                        die("Could not connect to database");
                    }
                    
                    $q = "UPDATE player SET x=$newX, y=$newY, moves_remaining=" . $this->movesRemaining .
                         " WHERE player_hash='" . $this->hash . "'";
                    if ($db->query($q)) {
                        // change status on reaching goal
                        if ($newTile->special === SpecialTile::finish) {
                            $this->status = PlayerStatus::reachedFinish;
                        }
                        return true;
                    } else {
                        throw new RuntimeException("Error updating database.");
                        return false;
                    }
                } else {
                    $this->status = PlayerStatus::outOfMoves;
                }
            }
        }
            
        
        return false;
    }
    
    public function getMoves() {
        return $this->movesRemaining;
    }
    
    public function getStatus() {
        return $this->status;
    }
}
?>