<?php
header('Content-type: application/json; charset=utf-8');
require_once('models.php');


$m = new Map(0);

// initially visible tiles
$visible = array();

// unique identifier for this player, used in subsequent calls
$hash = substr(base64_encode(hash('sha256',mt_rand(),true)), 0, 43);

for ($i=0; $i<$m->width(); $i++) {
    for ($j=0; $j<$m->height(); $j++) {
    
        $t = $m->getTile($i, $j);
        
        // look for the start tiles
        if ($t->special == SpecialTile::start) {
            // add the start tile to the array of visible tiles
            $visible[] = $m->tile_to_array($i, $j);
            
            // add adjacent (non-start) tiles to array of visible tiles
            foreach($m->adjacent_tiles($i, $j) as $adjacent) {
                // no need for redundant references to the special tiles
                if ($adjacent['special'] === SpecialTile::none) {
                    $visible[] = $adjacent;
                }
            }
        }
        
    }
}

// add this game to the database
$mapID = 1; // From map
$movesRemaining = 25; // Max Moves
$playerX = 0;
$playerY = 0;
if ($db->query("INSERT INTO player(player_hash, map_id, moves_remaining, x, y) VALUES ('$hash', $mapID, $movesRemaining, $playerX, $playerY)")){

    // send game init to client
    echo json_encode(
        array(
            'hash' => $hash,
            'width' => $m->width(),
            'height' => $m->height(),
            'visible' => $visible,
            'x' => $playerX,
            'y' => $playerY,
            'moves' => $movesRemaining
        )
    );
} else {
    $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
    header($protocol . ' 500 Internal Server Error');
    echo 'Problem inserting Player to database';
}
?>