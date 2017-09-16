#!/php -q
<?php  

$command = '/usr/games/gnugo --mode gtp';

$descriptorspec = array(
	0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
	1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
	2 => array("file", "/tmp/error-output.txt", "a") // stderr is a file to write to
);


$process = proc_open($command, $descriptorspec, $pipes);

if (is_resource($process)) {

	fwrite($pipes[0], 'genmove white'); 
	fclose($pipes[0]);

	echo stream_get_contents($pipes[1]);
	fclose($pipes[1]);

	// It is important that you close any pipes before calling
	// proc_close in order to avoid a deadlock
	proc_close($process);
}

// Run from command prompt > php -q chatbot.demo.php
include "websocket.class.php";
// Extended basic WebSocket as ChatBot
class ChatBot extends WebSocket{
  function process($user,$msg){
    $this->say("< ".$msg);
    switch($msg){
      case "hello" : $this->send($user->socket,"hello human");                       break;
      case "hi"    : $this->send($user->socket,"zup human");                         break;
      case "name"  : $this->send($user->socket,"my name is Multivac, silly I know"); break;
      case "age"   : $this->send($user->socket,"I am older than time itself");       break;
      case "date"  : $this->send($user->socket,"today is ".date("Y.m.d"));           break;
      case "time"  : $this->send($user->socket,"server time is ".date("H:i:s"));     break;
      case "thanks": $this->send($user->socket,"you're welcome");                    break;
      case "bye"   : $this->send($user->socket,"bye");                               break;
      default      : $this->send($user->socket,$msg." not understood");              break;
    }
  }
}
$master = new ChatBot("localhost",11345);