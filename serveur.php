#!/php -q
<?php  


// if (is_resource($process)) {

// 	fwrite($pipes[0], 'genmove white'); 
// 	fclose($pipes[0]);

// 	echo stream_get_contents($pipes[1]);
// 	fclose($pipes[1]);

// 	// It is important that you close any pipes before calling
// 	// proc_close in order to avoid a deadlock
// 	proc_close($process);
// }

// Run from command prompt > php -q chatbot.demo.php
// 

include "websocket.class.php";
// Extended basic WebSocket as ChatBot
class ChatBot extends WebSocket{

  function process($user,$msg){
  	if ($msg !== null) {
  		if ($msg == "showboard") {
  			fwrite($this->pipes[0], $msg);
  			fclose($this->pipes[0]);
  			$this->log(stream_get_contents($this->pipes[1]));
  		} else {
  			print_r($this->pipes);
	  		fwrite($this->pipes[0], $msg."\n");
	  		$response = fgets($this->pipes[1],4096);
	  		$color = substr($msg, 5, 5);
	  		fwrite($this->pipes[0], "\n");
	  		$this->log(fgets($this->pipes[1],4096));
	  		$this->send($user->socket, $response);
  		}

  	}
  }

  function __construct($address,$port) {
  	$command = '/usr/games/gnugo --mode gtp';

	$descriptorspec = array(
		0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
		1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
		2 => array("file", "/tmp/error-output.txt", "a") // stderr is a file to write to
	);
    $this->process = proc_open($command, $descriptorspec, $this->pipes);

  	parent::__construct($address,$port);
  }
}
$master = new ChatBot("localhost",11345);