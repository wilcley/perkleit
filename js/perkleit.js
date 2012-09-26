var SpecialTileNONE = 0;
var SpecialTileSTART = 1;
var SpecialTileFINISH = 2;

var PlayerStatusOUTOFMOVES = 2;
var PlayerStatusREACHEDFINISH = 3;


var startGame, movePlayer, gameInit, player;

$(document).ready( function () {
    startGame();    
    
    //bind movement keys
    $(document).bind('keydown.right', function() {
        movePlayer(player.x+1,player.y);
    });
    
    $(document).bind('keydown.left', function() {
        movePlayer(player.x-1,player.y);
    });
    
    $(document).bind('keydown.down', function() {
        movePlayer(player.x,player.y+1);
    });
    
    $(document).bind('keydown.up', function() {
        movePlayer(player.x,player.y-1);
    });    

});

startGame = function () {
    $.ajax({
    url: '/start.php',
    dataType: 'json',
    success: gameInit
    });
}

gameInit = function(data) {
    if (data.width && data.height) {
        // set up blank inital map
        var i, j, row, cssClass;
        $('#pl').append('<table id="plMap" class="map"><tbody></tbody></table>');
        for (i=0; i<data.height; i++) {
            $('#plMap > tbody:last').append('<tr></tr>');
            for (j=0; j<data.width; j++) {
                $('#plMap > tbody > tr:last').append('<td id="plTile_'+j+'_'+i+'" class="unknown"> </td>');
            }
        }
        
        // show revealed tiles
        revealTiles(data.visible);
        
        // initialize player object
        player = {hash:data.hash, x:data.x, y:data.y, moves:data.moves};
        
        // show player on map
        $('#plTile_'+player.x+'_'+player.y).addClass('player');
        
        $('#pl').append('<div id="plCounter"><span id="plCounterNumber">'+ player.moves + '</span> moves remaining</div>');
    }
}

revealTiles = function(tileArray) {
    for (i in tileArray) {
        tile = tileArray[i];
        
        if (tile.open) {
            cssClass = 'open';
        } else {
            cssClass = 'closed';
        }
    
        if (tile.special === SpecialTileSTART) {
            cssClass = "start"
        } else if (tile.special === SpecialTileFINISH) {
            cssClass = "finish";
        }
        
        $("#plTile_"+tile.x+"_"+tile.y).attr("class", cssClass);
    }
}

movePlayer = function(newX, newY) {
        $.ajax({
    url: '/move.php',
    type: 'post',
    data: {
        h: player.hash,
        x: newX,
        y: newY
    },
    dataType: 'json',
    success: function (data) {
        if (data.success) { // player actually moved
            // clear player icon from previous tile
            $("#plTile_"+player.x+"_"+player.y).removeClass('player');
            
            // update player object with new position
            player.x = newX;
            player.y = newY;
            
            // show player in new position
            $("#plTile_"+player.x+"_"+player.y).addClass('player');
            
            // reveal newly-visible tiles
            revealTiles(data.reveal);
            
            // update counter
            player.moves = data.moves;
            $('#plCounterNumber').html(player.moves);
            
            if (data.status === PlayerStatusREACHEDFINISH) {
                alert("Congratulations! You have reached the finish!");
            }
        } else {
            if (data.status === PlayerStatusOUTOFMOVES) {
                alert("Game Over. You ran out of moves.");
            }
        }
    }
    });
}

