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
       			var result = game.play(i, wgoY, WGo.W);
       			if (isDebugMode) {
       				console.log(e.data.replace(/\s/g,''))
        			console.log(result)
        			getNearStone(i, wgoY);
        		}
	        	if (result instanceof Array) {
	        		board.addObject({
	            		x: i,
	            		y: wgoY,
	            		c: WGo.W
	        		});
	        	}

	        	if (result.length > 0) {
	        		for (var i = 0; i < result.length; i++) {
	        			board.removeObjectsAt(result[i].x, result[i].y);
	        		}
	        	}

	        	isP1Turn = true;
       		}
       	}

	} else {
		socket.send('genmove white');
	}
};

socket.onopen  = function(e){start()};
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