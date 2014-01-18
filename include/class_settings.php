<?php
/*******************************************************************************
* Einstellugnen von Small Time
/*******************************************************************************
* Version 0.8
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_settings{
	private $_filename = "./include/Settings/settings.txt";
	public	$_array	= array();	
	//Einstelleungen und globale Variablen 
	//Beschreibung, Eintrag, Info 
	//0-1-2
	private $_file;
	function __construct(){
		$this->_file = new time_filehandle("./include/Settings/","settings.txt","#");
		$this->_array = $this->_file->_array;
	}
	function save_settings(){
		//echo "jaja - ich willupdaten<br>";
		$_newarray = array();
		$_zeilenvorschub = "\r\n";
		$anzahl = $_POST['anzahl'];
		$fp = fopen($this->_filename,"w+");
		for($x=0; $x<=$anzahl; $x++ ){
			//$this->_array[$x] = explode("#", $this->_array[$x]);
			$_newarray[$x][0] = $this->_array[$x][0];
			$_newarray[$x][1] = $_POST[$x];
			$this->_array[$x][1] = $_POST[$x];
			$_newarray[$x][2] = str_replace("\r", "", $this->_array[$x][2]);
			$_newarray[$x][2] = str_replace("\n", "", $_newarray[$x][2]);
			$_newarray[$x] = implode("#", $_newarray[$x]);
			fputs($fp, $_newarray[$x]);
			if($x<$anzahl) fputs($fp, $_zeilenvorschub);
		}
		fclose($fp);
		//echo "Daten wurden gespeichert";
	}
}
?>