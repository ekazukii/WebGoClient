<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>WGo</title>
	<script type="text/javascript" src="wgo/wgo.js"></script>
	<script type="text/javascript" src="wgo/scoremode.js"></script>
	<!-- <script type="text/javascript" src="wgo/wgo.player.min.js"></script> -->
	<link rel="stylesheet" type="text/css" href="wgo/wgo.player.css" />
	<style type="text/css">
		#total {
			
		}
	</style>
</head>
<body>
<h1 style="font-family: Consolas">WGo.js Player demo</h1>

<div id="total">
	<div id="option">
		<select id="tool" style="display: block; margin-bottom: 10px;">
		  	<option value="play" selected>jouer</option>
		  	<option value="debug">Debug</option>
		  	<option value="SQ">Square</option>
		  	<option value="TR">Triangle</option>
		  	<option value="CR">Circle</option>
		</select>
		<input type="button" name="test" value="calculate" id="test">
		<div id="score"></div>
		<input type="button" name="commencer" value="commencer" id="start">
	</div>
	<div>
		<div id="board"></div>
	</div>
</div>


<script type="text/javascript" src="js/socket.js"></script>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>