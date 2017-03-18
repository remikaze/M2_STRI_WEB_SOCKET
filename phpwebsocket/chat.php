#!/usr/bin/php -q
<?php
// Run from command prompt > php -q chatbot.demo.php
require "websocket.class.php";
require "utilisateur.php";

$listeUtilisateurs=array_map();



// Extended basic WebSocket as ChatBot
class ChatBot extends WebSocket{
  function process($user,$msg){
 
    $this->say("< ".$user->socket." :".$msg);
 
 	foreach ( $this->users as $utilisateur ){
		$this->send($utilisateur->socket,$msg);
	}

	
	echo "JSON TO ARRAY\n";

	$res = json_decode($msg, true);
	print_r($res);
	echo $res['commande']."\n";
	echo $res['data']."\n";


  }
}
$master = new ChatBot("localhost",1337);