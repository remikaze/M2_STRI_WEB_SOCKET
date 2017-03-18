<?php
class Utilisateur{
	var $id;
	var $nom;
	var $prenom;
	var $longitude;
	var $latitude;

	public function _construct ($pId, $pNom, $pPrenom, $pLongitude, $pLatitude)
	{
		$this->id=$pId;
		$this->nom=$pNom;
		$this->prenom=$pPrenom;
		$this->longitude=$pLongitude;
		$this->latitude=$pLatitude;
	}

	public function getJson()
	{
		return json_encode(get_object_vars($this));
	}
}
?>
