<?php
/*******************************************************************************
 * Fotoklasse, verkleinert das Bild
/*******************************************************************************
 * Version 0.896
 * Author:  IT-Master GmbH
 * www.it-master.ch / info@it-master.ch
 * Copyright (c), IT-Master GmbH, All rights reserved
 *******************************************************************************/
class foto{
	//Speicherorte der Bilder
	public $BildPfad;
	//Bildinformationen
	private $BildName;
	private $BildBreite;
	private $BildHoehe;
	private $BildSize;
	private $BildDate;
	private $ServerBildName;

	function __construct($BildName, $BildBreite, $BildHoehe){
		//Pfade setzen
		$this->BildName	= $BildName;
		$BildDestBreite	= $BildBreite;
		$BildDestHoehe	= $BildHoehe;

		$sourceImage = imagecreatefromjpeg($BildName);
		$size = getimagesize($BildName);
		$BildHoehe = $size[1];
		$BildBreite = $size[0];
		$destImage = imagecreatetruecolor($BildDestBreite, $BildDestHoehe);

		$verhaeltniss = $BildHoehe / $BildBreite;
		if($verhaeltniss >= 1.2){
			$BildHoehe = $BildBreite*1.2;
		}else{
			$BildBreite = $BildHoehe/1.2;
		}
		imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $BildDestBreite, $BildDestHoehe, $BildBreite, $BildHoehe);
		imagejpeg($destImage, $BildName,100);
	}
}