#!/usr/bin/php -q
<?php
// Run from command prompt > php -q chatbot.demo.php
require "websocket.class.php";
// Extended basic WebSocket as ChatBot
class ChatBot extends WebSocket{
	function getUtilisateurByUserId($pUserId)
	{
		foreach ( $this->listeUtilisateurs as $utilisateur ){
			if($utilisateur->idSocket == $pUserId)
			{
				return $utilisateur;
			}
		}
	}
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
	
	//Retrouver qui a initialise la fonction
	if ($commande != "CONNECT") { $utilisateurSocket= $this->getUtilisateurByUserId($user->id);}
	
	switch ($commande) {
		case "CONNECT":
			//DECODE la data qui est un json de personne
			$personne = json_decode($res['data'], true);
			$this->listeUtilisateurs[$personne['id']]->setIdSocket($user->id);
			$this->listeUtilisateurs[$personne['id']]->setLongitude($personne['longitude']);
			$this->listeUtilisateurs[$personne['id']]->setLatitude($personne['latitude']);
			$this->listeUtilisateurs[$personne['id']]->setSocket($user->socket);
			$utilisateurSocket= $this->getUtilisateurByUserId($user->id);
			break;
		case "MYSPORT":
			 echo "\n\n\nCOMMANDE CONNUE : $commande\n\n\n";
			$mySports = json_encode(array("commande"=>"MYSPORT", "data"=>$utilisateurSocket->sports), true);
			echo "azert :: ".$mySports."\n\n";
			echo "\n\n this.say :: \n";
			$this->say("< ".$user->socket." :".$mySports);
	 		$this->send($user->socket,$mySports);
 			echo "END\n\n\n";
			break;
		case "ADDCRENEAU":
			$sport = json_decode($res['data'], true);
			$utilisateurSocket->addSport($sport['nom'],$sport['date']);
			break;
		
		default:
			echo "COMMANDE INCONNUE: $commande\n";
			break;
	}
	
	//VOIR SI LUTILISATEUR EST PRES
	foreach ( $this->listeUtilisateurs as $utilisateur){
		//PREVENIR L'UTILISATEUR
		if($utilisateur->getId() != $utilisateurSocket->id)
		{
			if($this->listeUtilisateurs[$utilisateurSocket->id]->estPres($utilisateur))
			{
				//MESSAGE A LUTILISATEUR QUI VIENT DE SE CONNECTER
				$retour= $utilisateur->prenom." est pres de vous: ".$this->listeUtilisateurs[$utilisateurSocket->id]->distance($utilisateur)."km\n".$utilisateur->getMeteo();
				echo $retour;
				$this->say("< ".$user->socket." :".$msg);
	 			$this->send($user->socket,$retour);
	 			//MESSAGE A L AUTRE UTILISATEUR
				$retour= $this->listeUtilisateurs[$utilisateurSocket->id]->prenom." est actuellement pres de vous: ".$this->listeUtilisateurs[$utilisateurSocket->id]->distance($utilisateur)."km\n".$this->listeUtilisateurs[$utilisateurSocket->id]->getMeteo();
				echo $retour;
				$this->say("< ".$utilisateur->socket." :".$msg);
	 			$this->send($utilisateur->socket,$retour);
			}
		}
	}
	//VOIR SI utilSocket pratique les même sports que quelqu'un d'autre
	foreach ( $this->listeUtilisateurs as $vUtilisateur){
		//VERIF QUE CE NEST PAS LE MEME USER
		if($vUtilisateur->getId() != $utilisateurSocket->id)
		{
			//PARCOURIR TOUS LES SPORTS DE utilisateurSocket
			foreach (array_keys($utilisateurSocket->sports) as $vSport) {
				//REGARDER SI, AU MOINS vUtilisateur pratique le même sport que utilisateurSocket
				if(array_key_exists($vSport, $vUtilisateur->sports)){
					$intersect=array_intersect($utilisateurSocket->sports[$vSport], $vUtilisateur->sports[$vSport]);
					foreach ($intersect as $vDate) {
						$retour= $vUtilisateur->prenom." est disponible pour faire: ".$vSport." le ".$vDate."\n";
						$this->say("< ".$user->socket." :".$retour);
	 					$this->send($user->socket,$retour);
					}
				}
			}
		}
	}
	foreach ( $this->listeUtilisateurs as $utilisateur ){
		echo $utilisateur->toString();
		echo $utilisateur->sportsToString();
	}

	}
}
$master = new ChatBot("0.0.0.0",1337);