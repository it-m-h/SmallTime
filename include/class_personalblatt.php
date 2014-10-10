<?php
/*******************************************************************************
* User - Daten
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_personalblatt{
	public  $_personaldaten	= NULL;
	private $_file 				= NULL;
	function __construct(){
		$this->_personaldaten[0][0] 	= "Personal-ID";
		$this->_personaldaten[1][0] 	= "AHV-Nr.:";
		$this->_personaldaten[2][0] 	= "Kurzzeichen";
		$this->_personaldaten[3][0] 	= "Titel";
		$this->_personaldaten[4][0] 	= "Geschlecht";
		$this->_personaldaten[5][0] 	= "Geburtstag";
		$this->_personaldaten[6][0] 	= "E-Mail";
		$this->_personaldaten[7][0] 	= "Telefon";
		$this->_personaldaten[8][0] 	= "Mobil";
		$this->_personaldaten[9][0] 	= "Homepage";
		$this->_personaldaten[10][0] 	= "Strasse";
		$this->_personaldaten[11][0] 	= "PLZ / Ort";
		$this->_personaldaten[12][0] 	= "Land";
		$this->_personaldaten[13][0] 	= "Enitritt";
		$this->_personaldaten[14][0] 	= "Gelernte Berufe";
		$this->_personaldaten[15][0] 	= "Anstellung als";
		$this->_personaldaten[16][0] 	= "Arbeitsort";
		$this->_personaldaten[17][0] 	= "Abteilung";
		$this->_file 			= "./Data/".$_SESSION['datenpfad']."/personaldaten.txt";
		$this->load_data();
	}
	function load_data(){
		if($_SESSION['datenpfad']){
			if(!file_exists($this->_file)){
				$fp = fopen($this->_file, "w");
				fclose($fp); 
			}
			$_userdaten = file($this->_file);
			$z=0;
			foreach($_userdaten as $tmp){
				$this->_personaldaten[$z][1] = trim($tmp);
				$z++;
			}
		}	
	}
	function save_data($array){
		$_zeilenvorschub = "\r\n";
		$neu = implode($_zeilenvorschub, $array);
		$open = fopen($this->_file,"w+");
		fwrite ($open, $neu);
		fclose($open);
	}
}