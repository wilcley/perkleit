<?php
// basic tile types: open and closed
$open = new TileType(true);
$closed = new TileType(false);

// starting position tiles
$start = new TileType(true);
$start->special = SpecialTile::start;

// goal tiles
$finish = new TileType(true);
$finish->special = SpecialTile::finish;

// Hard coded map for testing

for ($i = 0; $i < 8; $i++) {
    $tiles[0][$i] = $start;
    $tiles[9][$i] = $finish;
    
    // default most tiles to open
    for ($j = 1; $j<9; $j++) {
        $tiles[$j][$i] = $open;
    }
}

$tiles[1][0] = $closed;
$tiles[1][2] = $closed;
$tiles[1][4] = $closed;
$tiles[1][5] = $closed;

$tiles[2][0] = $closed;
$tiles[2][2] = $closed;
$tiles[2][6] = $closed;

$tiles[3][0] = $closed;
$tiles[3][4] = $closed;
$tiles[3][6] = $closed;

$tiles[4][1] = $closed;
$tiles[4][2] = $closed;
$tiles[4][3] = $closed;
$tiles[4][4] = $closed;
$tiles[4][7] = $closed;

$tiles[6][1] = $closed;
$tiles[6][2] = $closed;
$tiles[6][3] = $closed;
$tiles[6][5] = $closed;
$tiles[6][6] = $closed;

$tiles[7][1] = $closed;
$tiles[7][4] = $closed;

$tiles[8][3] = $closed;
$tiles[8][5] = $closed;
$tiles[8][6] = $closed;
$tiles[8][7] = $closed;
?>