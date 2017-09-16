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
  	<option value="black" selected>Black stone</option>
  	<option value="white">White stone</option>
  	<option value="SQ">Square</option>
  	<option value="TR">Triangle</option>
  	<option value="CR">Circle</option>
  	<option value="remove">Remove</option>
</select>

<div id="board"></div>


<script type="text/javascript">
	var board = new WGo.Board(document.getElementById("board"), {
    	width: 600,
    });

    var tool = document.getElementById("tool"); // get the <select> element

	board.addEventListener("click", function(x, y) {
    	if(tool.value == "black") {
       		board.addObject({
            	x: x,
            	y: y,
            	c: WGo.B
        	});
    	}
    	else if(tool.value == "white") {
        	board.addObject({
            	x: x,
            	y: y,
            	c: WGo.W
        	});
    	}
    	else if(tool.value == "remove") {
        	board.removeObjectsAt(x, y);
    	}
    	else {
        	board.addObject({
            	x: x,
            	y: y,
            	type: tool.value
        	});
    	}
	});

	$(document).ready(function(){
	    //Open a WebSocket connection.
	    var wsUri = "ws://localhost:9000/daemon.php";  
	    websocket = new WebSocket(wsUri);
	   
	    //Connected to server
	    websocket.onopen = function(ev) {
	        alert('Connected to server ');
	    }
	   
	    //Connection close
	    websocket.onclose = function(ev) {
	        alert('Disconnected');
	    };
	   
	    //Message Receved
	    websocket.onmessage = function(ev) {
	        alert('Message '+ev.data);
	    };
	   
	    //Error
	    websocket.onerror = function(ev) {
	        alert('Error '+ev.data);
	    };
	   
	     //Send a Message
	    $('#send').click(function(){
	        var mymessage = 'This is a test message';
	        websocket.send(mymessage);
	    });
	});
</script>

<?php
	$command = '/usr/games/gnugo --mode gtp';

	$descriptorspec = array(
   		0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   		1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
   		2 => array("file", "/tmp/error-output.txt", "a") // stderr is a file to write to
	);


	$process = proc_open($command, $descriptorspec, $pipes);
	echo '<pre>';

	if (is_resource($process)) {
    	// $pipes now looks like this:
    	// 0 => writeable handle connected to child stdin
    	// 1 => readable handle connected to child stdout
    	// Any error output will be appended to /tmp/error-output.txt

   	 	fwrite($pipes[0], 'genmove white'); 
    	fclose($pipes[0]);

    	echo stream_get_contents($pipes[1]);
    	fclose($pipes[1]);

    	// It is important that you close any pipes before calling
    	// proc_close in order to avoid a deadlock
    	$return_value = proc_close($process);

    	echo "command returned $return_value\n";
	}
	echo  '</pre>';
?>
</body>
</html>