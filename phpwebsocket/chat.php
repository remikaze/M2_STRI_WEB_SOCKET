#!/usr/bin/php -q
<?php
// Run from command prompt > php -q chatbot.demo.php
require "websocket.class.php";


// Extended basic WebSocket as ChatBot
class ChatBot extends WebSocket{

  function process($user,$msg){
 
 	//ECHO
    $this->say("< ".$user->socket." :".$msg);
    $this->send($user->socket,$msg);

	// //Envoyer a tous les utilisateurs 	
 // 	foreach ( $this->users as $utilisateur ){
	// 	$this->send($utilisateur->socket,$msg);
	// }

	
	echo "JSON TO ARRAY\n";
	//Decode le message entier et recupere la commande
	$res = json_decode($msg, true);
	echo $res['commande']."\n";
	$commande=$res['commande'];

	//DECODE la data qui est un json de personne
	$personne = json_decode($res['data'], true);

	switch ($commande) {
		case "CONNECT":
			echo "$commande\n";
			$this->listeUtilisateurs[$personne['id']]->setIdSocket($user->id);
			$this->listeUtilisateurs[$personne['id']]->setLongitude($personne['longitude']);
			$this->listeUtilisateurs[$personne['id']]->setLatitude($personne['latitude']);
			break;
		
		default:
			echo "COMMANDE INCONNUE: $commande\n";
			break;
	}

	foreach ( $this->listeUtilisateurs as $utilisateur ){
		echo $utilisateur->toString();		
	}

	//VERIF SI L UTILISATEUR EST PRES
	echo "TEST DISTANCE: ".$this->listeUtilisateurs['robin.degironde@gmail.com']->distance($this->listeUtilisateurs['charles.banquet@live.com'])." \n";

	if($this->listeUtilisateurs['robin.degironde@gmail.com']->estPres($this->listeUtilisateurs['charles.banquet@live.com']))
		echo "CHARLES EST PRES DE ROBIN";
	else
		echo "CHARLES N'EST PAS PRES DE ROBIN";
	
	}
}
$master = new ChatBot("localhost",1337);