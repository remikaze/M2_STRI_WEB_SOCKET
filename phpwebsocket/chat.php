#!/usr/bin/php -q
<?php
// Run from command prompt > php -q chatbot.demo.php
require "websocket.class.php";
require "utilisateur.php";

// Extended basic WebSocket as ChatBot
class ChatBot extends WebSocket{
  function process($user,$msg){
 
 	$listeUtilisateurs=array();
	$listeUtilisateurs['robin.degironde@gmail.com']= new Utilisateur('robin.degironde@gmail.com', 'DEGIRONDE', 'ROBIN', '1.00000001', '1.000000001', 'mdp', 'X');
	$listeUtilisateurs['charles.banquet@live.com']= new Utilisateur('charles.banquet@live.com', 'BANQUET', 'CHARLES', '1.00000001', '1.00000001', 'mdp', 'X');
	$listeUtilisateurs['remi.barbaste@gmail.com']= new Utilisateur('remi.barbaste@gmail.com', 'BARBASTE', 'REMI', '1.00000001', '1.00000001', 'mdp', 'X');

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
	echo $listeUtilisateurs['robin.degironde@gmail.com']->toString();
	$listeUtilisateurs['robin.degironde@gmail.com']->setIdSocket($user->id);
	echo $listeUtilisateurs['robin.degironde@gmail.com']->toString();


  }
}
$master = new ChatBot("localhost",1337);