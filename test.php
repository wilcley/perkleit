<?php
require_once('models.php');

// hard coded map that defines the $tiles variable used below
require('map1.php'); 

$m = new Map($tiles);

echo '<style type="text/css">';
require("style.css");
echo '</style>';

// Display the map
echo '<table class="map">';
    for ($i=0; $i<$m->height(); $i++) {
        echo '<tr>';
        for ($j=0; $j<$m->width(); $j++) {
            $t = $m->tiles[$i][$j];
            
            // check that this is a tile object
            if(get_class($t) == 'Tile') {
            
                if ($t->open) {
                    $cssClass = "open";
                } else {
                    $cssClass = "closed";
                }
                
                // override CSS Class with special attributes
                if ($t->special == SpecialTile::start) {
                    $cssClass = "start";
                } elseif ($t->special == SpecialTile::finish) {
                    $cssClass = "finish";
                }
                
                echo "<td class=\"$cssClass\"> </td>";
            } else {
                echo '<td>E</td>';
            }
        
        }
        echo '</tr>';
    }

echo '</table>';
?>