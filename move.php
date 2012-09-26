<?php
header('Content-type: application/json; charset=utf-8');
require_once('models.php');


$success = false;
$response = array();
if (isset($_POST['h'])) {
    $player = new Player($_POST['h']);
    
    if ($player->move($_POST['x'], $_POST['y'])) {
        $success = true;
        $response['reveal'] = $player->map->adjacent_tiles($_POST['x'], $_POST['y']);
        $response['moves'] = $player->getMoves();
    }    
    $response['status'] = $player->getStatus();
}
$response['success'] = $success;
echo(json_encode($response));

?>