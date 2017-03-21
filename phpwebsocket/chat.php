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
			$this->listeUtilisateurs[$personne['id']]->setSocket($user->socket);
			break;
		
		default:
			echo "COMMANDE INCONNUE: $commande\n";
			break;
	}

	foreach ( $this->listeUtilisateurs as $utilisateur){
		
		//PREVENIR L'UTILISATEUR
		if($utilisateur->getId() != $personne['id'])
		{
			if($this->listeUtilisateurs[$personne['id']]->estPres($utilisateur))
			{
				//MESSAGE A LUTILISATEUR QUI VIENT DE SE CONNECTER
				$retour= $utilisateur->prenom." est pres de vous: ".$this->listeUtilisateurs[$personne['id']]->distance($utilisateur)."km\n";
				echo $retour;

				$this->say("< ".$user->socket." :".$msg);
	 			$this->send($user->socket,$retour);


	 			//MESSAGE A L AUTRE UTILISATEUR
				$retour= $this->listeUtilisateurs[$personne['id']]->prenom." est pres de vous: ".$this->listeUtilisateurs[$personne['id']]->distance($utilisateur)."km\n";
				echo $retour;


				$this->say("< ".$utilisateur->socket." :".$msg);
	 			$this->send($utilisateur->socket,$retour);

			}
		}
		
	}











	foreach ( $this->listeUtilisateurs as $utilisateur ){
		echo $utilisateur->toString();
		echo $utilisateur->sportsToString();
	}

	}
}
$master = new ChatBot("localhost",1337);