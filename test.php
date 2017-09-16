<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>WGo</title>
	<script type="text/javascript" src="wgo/wgo.js"></script>
	<!-- <script type="text/javascript" src="wgo/wgo.player.min.js"></script> -->
	<link rel="stylesheet" type="text/css" href="wgo/wgo.player.css" />
</head>
<body>
<h1 style="font-family: Consolas">WGo.js Player demo</h1>

<select id="tool" style="display: block; margin-bottom: 10px;">
  	<option value="play" selected>jouer</option>
  	<option value="debug">Debug</option>
  	<option value="SQ">Square</option>
  	<option value="TR">Triangle</option>
  	<option value="CR">Circle</option>
</select>

<div id="board"></div>


<script type="text/javascript">
	lettres = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];
	//Effectivement il n'y a pas de lettre I c'est normal

	numbers = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19];

	var isP1Turn = true;

	var board = new WGo.Board(document.getElementById("board"), {
	width: 600
		// section: {
		// 	top: 20, 
		// 	right: 20, 
		// 	bottom: 20, 
		// 	left: 20
		// }
	});

	var game = new WGo.Game()
	console.log(game)

	var socket = new WebSocket("ws://localhost:11345/serveur.php");

	socket.onopen = function(e){

	    var tool = document.getElementById("tool"); // get the <select> element

		board.addEventListener("click", function(x, y) {
	    	if(tool.value == "play" && isP1Turn) {
	        	var numbers2 = Array.from(numbers);
	        	numbers2.reverse();
	       		var gnuY = numbers2[y];
	       		var gnuX = lettres[x];

	       		getNearStone(x, y);

	        	result = game.play(x, y, WGo.B);
	        	if (result instanceof Array) {
	        		isP1Turn = false;
	        		console.log("OK")
	        		console.log(result)
	        		board.addObject({
	            		x: x,
	            		y: y,
	            		c: WGo.B
	        		});
	        	}

	        	if (result.length > 0) {
	        		for (var i = 0; i < result.length; i++) {
	        			board.removeObjectsAt(result[i].x, result[i].y);
	        		}
	        	}
	        	if (result instanceof Array) {
	        		console.log('play black ' + gnuX + "" + gnuY);
	        		socket.send('play black ' + gnuX + "" + gnuY);
	        	}


	        	//console.log(game.addStone(x,y, 1))
	    	}
	    	else if(tool.value == "debug") {
	        	// board.addObject({
	         //    	x: x,
	         //    	y: y,
	         //    	c: WGo.W
	        	// });
	        	console.log(game.getStone(x, y))
	        	socket.send("showboard");
	    	}
	    	else {
	        	board.addObject({
	            	x: x,
	            	y: y,
	            	type: tool.value
	        	});
	    	}
		});

		var coordinates = {
	    // draw on grid layer
	    	grid: {
		        draw: function(args, board) {
		            var ch, t, xright, xleft, ytop, ybottom;
		            
		            this.fillStyle = "rgba(0,0,0,0.7)";
		            this.textBaseline="middle";
		            this.textAlign="center";
		            this.font = board.stoneRadius+"px "+(board.font || "");
		            
		            xright = board.getX(-0.50);
		            xleft = board.getX(board.size-0.50);
		            ytop = board.getY(-0.50);
		            ybottom = board.getY(board.size-0.50);
		            
		            for(var i = 0; i < board.size; i++) {
		                ch = i+"A".charCodeAt(0);
		                if(ch >= "I".charCodeAt(0)) ch++;
		                
		                t = board.getY(i);
		                this.fillText(board.size-i, xright, t);
		                this.fillText(board.size-i, xleft, t);
		                
		                t = board.getX(i);
		                this.fillText(String.fromCharCode(ch), t, ytop);
		                this.fillText(String.fromCharCode(ch), t, ybottom);
		            }
		            
		            this.fillStyle = "black";
				}
	    	}
		}
		board.addCustomObject(coordinates);
	} 
	socket.onmessage = function(e){
		console.log(e);
		if (e.data.replace(/\s/g,'') != "=") {
			$dataRaw = e.data.replace(/\s/g,'');
			$data = $dataRaw.substr(1);

			var gnuY = $data.substr(1)

			var numbers2 = Array.from(numbers);
	        numbers2.reverse();
	       	var wgoY = numbers2[gnuY];

	       	console.log(wgoY)

	       	var gnuX = $data.substr(0,1);

	       	for (var i = 0; i < lettres.length; i++) {
	       		if (lettres[i] == gnuX) {
	       			var result = game.play(i, wgoY, WGo.W);
		        	if (result instanceof Array) {
		        		console.log("OK")
		        		console.log(result)
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
	}
	socket.onclose = function(e){}
	socket.onerror = function(e){console.error(e)}

	function getNearStone(x, y) {
		console.log(game.getStone(x+1, y) + '' + game.getStone(x-1, y));
		console.log(game.getStone(x, y+1) + '' + game.getStone(x, y-1));
	}

</script>
</body>
</html>