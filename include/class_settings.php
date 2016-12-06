<?php
/*******************************************************************************
* Einstellugnen von Small Time
/*******************************************************************************
* Version 0.9.008
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_settings{
	private 	$_filename 	= "./include/Settings/settings.txt";
	public	$_array		= array();	
	// Einstellungen und globale Variablen 
	// Beschreibung, Eintrag, Info 
	private 	$_file;
	function __construct(){
		$this->_file = new time_filehandle("./include/Settings/","settings.txt","#");
		$this->_array = $this->_file->_array;
		unset($this->_file);
	}
	function save_settings(){
		$_newarray = array();
		$_zeilenvorschub = "\r\n";
		$anzahl = $_POST['anzahl'];
		$fp = fopen($this->_filename,"w+");
		for($x=0; $x<=$anzahl; $x++ ){
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
	}
	function save_array($arr){
		$_zeilenvorschub = "\r\n";
		//$anzahl = $_POST['anzahl'];
		$fp = fopen($this->_filename,"w+");
		foreach($arr as $eintrag){
			fputs($fp, $eintrag . $_zeilenvorschub);
		}
		fclose($fp);
	}
}