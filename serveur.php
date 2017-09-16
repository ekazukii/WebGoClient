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
class GnuBot extends WebSocket{

  function process($user,$msg){
  	if ($msg !== null) {
  		if ($msg == "showboard") {
  			fwrite($user->pipes[0], $msg);
  			fclose($user->pipes[0]);
  			$this->log(stream_get_contents($user->pipes[1]));
  		} else {
  			print_r($user->pipes);
	  		fwrite($user->pipes[0], $msg."\n");
	  		$response = fgets($user->pipes[1],4096);
	  		$color = substr($msg, 5, 5);
	  		fwrite($user->pipes[0], "\n");
	  		$this->log(fgets($user->pipes[1],4096));
	  		$this->send($user->socket, $response);
  		}

  	}
  }

  function connect($socket) {

  	$user = new UserGo();
    $user->id = uniqid();
    $user->socket = $socket;
    array_push($this->users,$user);
    array_push($this->sockets,$socket);
    $this->log($socket." CONNECTED!");
    $this->log(date("d/n/Y ")."at ".date("H:i:s T"));

  	$command = '/usr/games/gnugo --mode gtp --level 1';

	$descriptorspec = array(
		0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
		1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
		2 => array("file", "/tmp/error-output.txt", "a") // stderr is a file to write to
	);

    $user->process = proc_open($command, $descriptorspec, $user->pipes);
  }

  function disconnect($socket){
    $found=null;
    $n=count($this->users);
    for($i=0;$i<$n;$i++){
      fclose($this->users[$i]->pipes[0]);
      fclose($this->users[$i]->pipes[1]);
      proc_close($this->users[$i]->process);
      if($this->users[$i]->socket==$socket){ $found=$i; break; }
    }
    if(!is_null($found)){ array_splice($this->users,$found,1); }
    $index=array_search($socket,$this->sockets);
    socket_close($socket);
    $this->log($socket." DISCONNECTED!");
    if($index>=0){ array_splice($this->sockets,$index,1); }
  }
}

class UserGo extends User{
  var $process;
  var $pipes;
}


$master = new GnuBot("localhost",11345);