/* js/main.js */
$("#tool").hide();
$("#calculate").hide();
var socketOk = false;
var isDebugMode = true;
var isP1Turn = false
var lettres = ['A', 'B','C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];
//Effectivement il n'y a pas de lettre I c'est normal 
var numbers = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19];
var board;
var game;
var type;

document.getElementById("start").addEventListener("click", function() {
	type = $("#type").val();
	if (isDebugMode) {
		console.log(type)
	}
	if (type == "jvj") {

		$("#tool").show();
		$("#calculate").show();
		$("#type").hide();
		$("#start").hide();

		isP1Turn = true;

		board = new WGo.Board(document.getElementById("board"), {
			width: 600
		});

		game = new WGo.Game()

		if (isDebugMode) {
			console.log(game)
		}	

		var test = document.getElementById("calculate")
		var score = document.getElementById("score");


		test.addEventListener("click", function() {
			new WGo.ScoreMode(game.getPosition(), board, 7.5, function(msg){
				document.getElementById("score").innerHTML = msg;
			}).start();
		})

	    var tool = document.getElementById("tool"); // get the <select> element

		board.addEventListener("click", function(x, y) {
	    	if(tool.value == "play") {
	        	var numbers2 = Array.from(numbers);
	        	numbers2.reverse();
	       		var gnuY = numbers2[y];
	       		var gnuX = lettres[x];


	        	result = game.play(x, y);

	        	if (isDebugMode) {
	        		console.log(result)
	        		getNearStone(x, y);
	        	}

	        	if (result instanceof Array) {
	        		isP1Turn = false;
	        		board.addObject({
	            		x: x,
	            		y: y,
	            		c: -game.turn
	        		});
	        	}

	        	if (result.length > 0) {
	        		for (var i = 0; i < result.length; i++) {
	        			board.removeObjectsAt(result[i].x, result[i].y);
	        		}
	        	}


	        	//console.log(game.addStone(x,y, 1))
	    	}
	    	else if(tool.value == "debug") {
	    		if (isDebugMode) {
	    			console.log(game.getStone(x, y))
	        		showboard();
	    		}
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
	} else if ($("#type").val() == "iavia") {

		if (!socketOk) {return;}

		$("#tool").show();
		$("#calculate").show();
		$("#type").hide();
		$("#start").hide();

		board = new WGo.Board(document.getElementById("board"), {
			width: 600
		});

		game = new WGo.Game()

		if (isDebugMode) {
			console.log(game)
		}	

		var test = document.getElementById("calculate")
		var score = document.getElementById("score");


		test.addEventListener("click", function() {
			new WGo.ScoreMode(game.getPosition(), board, 7.5, function(msg){
				document.getElementById("score").innerHTML = msg;
				isP1Turn = false;
			}).start();
		})

		genmove('black');

	    var tool = document.getElementById("tool"); // get the <select> element

		board.addEventListener("click", function(x, y) {
	    	if(tool.value == "debug" || tool.value == "play" ) {
	    		if (isDebugMode) {
	    			console.log(game.getStone(x, y))
	        		showboard();
	    		}
	    	}
	    	else {}{
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

	} else if ($("#type").val() == "jvia") {

		if (!socketOk) {return;}

		$("#tool").show();
		$("#calculate").show();
		$("#type").hide();
		$("#start").hide();

		isP1Turn = true;

		board = new WGo.Board(document.getElementById("board"), {
			width: 600
		});

		game = new WGo.Game()

		if (isDebugMode) {
			console.log(game)
		}	

		var test = document.getElementById("calculate")
		var score = document.getElementById("score");


		test.addEventListener("click", function() {
			new WGo.ScoreMode(game.getPosition(), board, 7.5, function(msg){
				document.getElementById("score").innerHTML = msg;
				isP1Turn = false;
			}).start();
		})

	    var tool = document.getElementById("tool"); // get the <select> element

		board.addEventListener("click", function(x, y) {
	    	if(tool.value == "play" && isP1Turn) {
	        	var numbers2 = Array.from(numbers);
	        	numbers2.reverse();
	       		var gnuY = numbers2[y];
	       		var gnuX = lettres[x];


	        	result = game.play(x, y, WGo.B);
	        	if (isDebugMode) {
	        		console.log(result)
	        		getNearStone(x, y);
	        	}

	        	if (result instanceof Array) {
	        		isP1Turn = false;
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
	        		play('black', gnuX, gnuY);
	        	}


	        	//console.log(game.addStone(x,y, 1))
	    	}
	    	else if(tool.value == "debug") {
	    		if (isDebugMode) {
	    			console.log(game.getStone(x, y))
	        		showboard();
	    		}
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
})

function getNearStone(x, y) {
	console.log(game.getStone(x+1, y) + '' + game.getStone(x-1, y));
	console.log(game.getStone(x, y+1) + '' + game.getStone(x, y-1));
}