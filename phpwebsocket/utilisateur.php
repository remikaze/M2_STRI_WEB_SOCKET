<?php
class Utilisateur{
	//ID=MAIL
	var $id;
	var $nom;
	var $prenom;
	var $longitude;
	var $latitude;
	var $mdp;
	var $idSocket;

	public function __construct ($pId, $pNom, $pPrenom, $pLongitude, $pLatitude, $pMdp, $pIdSocket)
	{
		$this->id=$pId;
		$this->nom=$pNom;
		$this->prenom=$pPrenom;
		$this->longitude=$pLongitude;
		$this->latitude=$pLatitude;
		$this->idSocket=$pIdSocket;
		$this->mdp=$pMdp;
	}

	public function getJson()
	{
		return json_encode(get_object_vars($this));
	}

	public function setIdSocket($pIdSocket)
	{
		$this->idSocket=$pIdSocket;
	}

	public function toString()
	{
		return $this->id.' '.$this->nom.' '.$this->prenom.' '.$this->longitude.' '.$this->latitude.' '.$this->mdp.' '.$this->idSocket."\n";
	}
}
?>
