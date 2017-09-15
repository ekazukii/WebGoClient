<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<script type="text/javascript" src="lib/jquery.js"></script>

<table>
	<tr>
		<th></th>
		<th>A</th>
		<th>B</th>
		<th>C</th>
		<th>D</th>
		<th>E</th>
		<th>F</th>
		<th>G</th>
		<th>H</th>
		<th>I</th>
		<th>J</th>
		<th>K</th>
		<th>L</th>
		<th>M</th>
		<th>N</th>
		<th>O</th>
		<th>P</th>
		<th>Q</th>
		<th>R</th>
		<th>S</th>
		<th>T</th>
	</tr>
	<tr>
		<th id="19">19</th>
	</tr>
	<tr>
		<th id="18">18</th>
	</tr>
	<tr>
		<th id="17">17</th>
	</tr>
	<tr>
		<th id="16">16</th>
	</tr>
	<tr>
		<th id="15">15</th>
	</tr>
	<tr>
		<th id="14">14</th>
	</tr>
	<tr>
		<th id="13">13</th>
	</tr>
	<tr>
		<th id="12">12</th>
	</tr>
	<tr>
		<th id="11">11</th>
	</tr>
	<tr>
		<th id="10">10</th>
	</tr>
	<tr>
		<th id="9">9</th>
	</tr>
	<tr>
		<th id="8">8</th>
	</tr>
	<tr>
		<th id="7">7</th>
	</tr>
	<tr>
		<th id="6">6</th>
	</tr>
	<tr>
		<th id="5">5</th>
	</tr>
	<tr>
		<th id="4">4</th>
	</tr>
	<tr>
		<th id="3">3</th>
	</tr>
	<tr>
		<th id="2">2</th>
	</tr>
	<tr>
		<th id="1">1</th>
	</tr>

</table>


<script type="text/javascript">
	console.log('test')
	lettres = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];
	for (var i = 0; i < 20; i++) {
		for (var j = lettres.length; j > 0; j--) {
			$("#" + i).after('<td id="'+ i + lettres[j-1] + '">*</td>')
		}
	}


	// for (var i = 0; i < Things.length; i++) {
	// 	Things[i]
	// }
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
