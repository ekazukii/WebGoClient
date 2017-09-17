var socket = new WebSocket("ws://localhost:11345/serveur.php");

socket.onmessage = function(e){
	if (e.data.replace(/\s/g,'') != "=") {
		$dataRaw = e.data.replace(/\s/g,'');
		$data = $dataRaw.substr(1);

		var numbers2 = Array.from(numbers);
        numbers2.reverse();
       	var wgoY = numbers2[$data.substr(1)];
       	var gnuX = $data.substr(0,1);

       	for (var i = 0; i < lettres.length; i++) {
       		if (lettres[i] == gnuX) {
       			var result = game.play(i, wgoY, game.turn);
       			if (isDebugMode) {
       				console.log(e.data.replace(/\s/g,''))
        			console.log(result)
        			getNearStone(i, wgoY);
        		}
	        	if (result instanceof Array) {
	        		board.addObject({
	            		x: i,
	            		y: wgoY,
	            		c: -game.turn
	        		});
	        	}

	        	if (result.length > 0) {
	        		for (var i = 0; i < result.length; i++) {
	        			board.removeObjectsAt(result[i].x, result[i].y);
	        		}
	        	}
	        	if (type == "iavia") {
	        		if (game.turn == 1) {
	        			genmove("black") 
	        		} else {
	        			genmove("white")
	        		}
	        	} else {
	        		isP1Turn = true;
	        	}
       		}
       	}

	} else {
		socket.send('genmove white');
	}
};

socket.onopen  = function(e){socketOk = true;};
socket.onclose = function(e){}
socket.onerror = function(e){console.error(e)}

function showboard() {
	socket.send("showboard");
}

function play(color, x, y) {
	if (isDebugMode) {
		console.log('play '+ color + " " + x + "" + y);
	}
	socket.send('play '+ color + " " + x + "" + y);
}

function genmove(color) {
	if (isDebugMode) {
		console.log('genmove '+color);
	}
	socket.send('genmove '+color);
}