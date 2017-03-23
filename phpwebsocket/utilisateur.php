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
	var $socket;
	var $sports;

	public function __construct ($pId, $pNom, $pPrenom, $pLongitude, $pLatitude, $pMdp, $pIdSocket)
	{
		$this->id=$pId;
		$this->nom=$pNom;
		$this->prenom=$pPrenom;
		$this->longitude=$pLongitude;
		$this->latitude=$pLatitude;
		$this->idSocket=$pIdSocket;
		$this->mdp=$pMdp;
		$this->sports=array();
	}

	public function getJson()
	{
		return json_encode(get_object_vars($this));
	}

	public function getLongitude()
	{
		return $this->longitude;
	}

	public function getLatitude()
	{
		return $this->latitude;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getNom()
	{
		return $this->nom;
	}

	public function getPrenom()
	{
		return $this->prenom;	
	}

	public function setIdSocket($pIdSocket)
	{
		$this->idSocket=$pIdSocket;
	}

	public function setSocket($pSocket)
	{
		$this->socket=$pSocket;
	}

	public function setLongitude($pLongitude)
	{
		$this->longitude=$pLongitude;
	}

	public function setLatitude ($pLatitude)
	{
		$this->latitude=$pLatitude;
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

	public function addSport($pSport, $pDate)
	{
		if(!array_key_exists($pSport, $this->sports))
		{
			$this->sports[$pSport]= array();
		}

		array_push($this->sports[$pSport], $pDate);
	}

	public function suppSport($pSport, $pDate)
	{
		if(array_key_exists($pSport, $this->sports))
		{
			$vSearch=array_search($pDate, $this->sports[$pSport]);
			if($vSearch)
			{
				unset($this->sports[$pSport][$vSearch]);
				echo "suppSport: $pSport $pDate supprime\n";
				print_r($this->sports[$pSport]);
			}
		}		
	}


	public function sportsToString()
	{
		$retour="";
		foreach ( array_keys($this->sports) as $nomSport ){
			$retour=$retour.$nomSport."\n";
			
			foreach ( $this->sports[$nomSport] as $date ){
				$retour=$retour.' '.$date;
			}
			$retour=$retour."\n";
		}
		return $retour;
	}

	public function getMeteo()
	{
    	$request = 'http://api.openweathermap.org/data/2.5/weather?lat='.$this->latitude.'&lon='.$this->longitude.'&appid=bd5e378503939ddaee76f12ad7a97608';
    	$response  = file_get_contents($request);
    	$jsonobj  = json_decode($response);
    	// print_r($jsonobj);

    	$reponse="Ville: ".$jsonobj->name." Meteo generale: ".strtolower($jsonobj->weather[0]->main).", Details: ".strtolower($jsonobj->weather[0]->description)."\n";
    	return $reponse;
	}

	public function getMeteoJson()
	{
    	$request = 'http://api.openweathermap.org/data/2.5/weather?lat='.$this->latitude.'&lon='.$this->longitude.'&appid=bd5e378503939ddaee76f12ad7a97608';
    	$response  = file_get_contents($request);
    	return $reponse;
	}


}
?>
