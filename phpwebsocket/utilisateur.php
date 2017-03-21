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

	public function setLongitude($pLongitude)
	{
		$this->longitude=$pLongitude;
	}

	public function setLatitude ($pLatitude)
	{
		$this->latitude=$pLatitude;
	}

	public function getLongitude()
	{
		return $this->longitude;
	}

	public function getLatitude()
	{
		return $this->latitude;
	}

	public function toString()
	{
		return $this->id.' '.$this->nom.' '.$this->prenom.' '.$this->longitude.' '.$this->latitude.' '.$this->mdp.' '.$this->idSocket."\n";
	}

	public function distance($pUtilisateur) 
	{
		$alt1=0;
		$alt2=0;

		$lat1=$this->latitude;
		$lon1=$this->longitude;

		$lat2= $pUtilisateur->getLatitude();
		$lon2= $pUtilisateur->getLongitude();

		//rayon de la terre
		$r = 6366;
		$lat1 = deg2rad($lat1);
		$lat2 = deg2rad($lat2);
		$lon1 = deg2rad($lon1);
		$lon2 = deg2rad($lon2);
 
		//recuperation altitude en km
		$alt1 = $alt1/1000;
		$alt2 = $alt2/1000;
 
		//calcul précis
		$dp= 2 * asin(sqrt(pow (sin(($lat1-$lat2)/2) , 2) + cos($lat1)*cos($lat2)* pow( sin(($lon1-$lon2)/2) , 2)));
 
		//sortie en km
		$d = $dp * $r;
 
		//Pythagore a dit que :
		 $h = sqrt(pow($d,2)+pow($alt2-$alt1,2));
 
		return $h;
	}

	public function estPres($pUtilisateur)
	{
		$critere= 80;
		return($this->distance($pUtilisateur) <= $critere);
	}
}
?>
