<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>WGo</title>
	<script type="text/javascript" src="lib/jquery.js"></script>
	<script type="text/javascript" src="wgo/wgo.js"></script>
	<script type="text/javascript" src="wgo/scoremode.js"></script>
	<!-- <script type="text/javascript" src="wgo/wgo.player.min.js"></script> -->
	<link rel="stylesheet" type="text/css" href="wgo/wgo.player.css" />
	<style type="text/css">
		#total {
			display: flex;
		}

		#boardDiv {
			padding-left: 10%;
		}

		#option {
			width: 228px;
		}
	</style>
</head>
<body>
<h1 style="font-family: Consolas">WGo.js Player demo</h1>

<div id="total">
	<div id="option">
		<select id="type" style="display: block; margin-bottom: 10px;">
			<option value="jvj">Joueur vs Joueur</option>
			<option value="iavia">Ordinateur vs Ordinateur</option>
			<option value="jvia">Joueur vs Ordinateur (BETA)</option>
		</select>
		<select id="tool" style="display: block; margin-bottom: 10px;">
		  	<option value="play" selected>Jouer</option>
		  	<option value="debug">Debug</option>
		  	<option value="SQ">Square</option>
		  	<option value="TR">Triangle</option>
		  	<option value="CR">Circle</option>
		</select>
		<input type="button" name="test" value="calculate" id="calculate">
		<div id="score"></div>
		<input type="button" name="commencer" value="commencer" id="start">
	</div>
	<div id="boardDiv">
		<div id="board"></div>
	</div>
</div>


<script type="text/javascript" src="js/socket.js"></script>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>