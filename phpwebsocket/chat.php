#!/usr/bin/php -q
<?php
// Run from command prompt > php -q chatbot.demo.php
require "websocket.class.php";
require "utilisateur.php";

$listeUtilisateurs=array();



// Extended basic WebSocket as ChatBot
class ChatBot extends WebSocket{
  function process($user,$msg){
 
    $this->say("< ".$user->socket." :".$msg);
 
 	foreach ( $this->users as $utilisateur ){
		$this->send($utilisateur->socket,$msg);
	}

	
	echo "JSON TO ARRAY\n";

	//Decode le message entier et recupere la commande
	$res = json_decode($msg, true);
	echo $res['commande']."\n";

	//decode la data qui est un json de personne
	$personne= json_decode($res['data'], true);
	$util= new Utilisateur($user->id, $personne['nom'], $personne['prenom'], $personne['longitude'], $personne['latitude']);
	echo $util->toString();
  }
}
$master = new ChatBot("localhost",1337);