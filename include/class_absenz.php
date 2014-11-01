<?php
/*******************************************************************************
* Absenzen - Klasse
/*******************************************************************************
* Version 0.9
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_absenz{
	public $_array 		= NULL;
	public $_filetext  	= NULL;
	public $_calc 		= NULL;
	private $ordnerpfad	= NULL;
	function __construct($ordnerpfad,$jahr){	
		$this->ordnerpfad = $ordnerpfad;
		$_file = "./Data/".$this->ordnerpfad."/Timetable/A" . $jahr;
		$this->_filetext = file("./Data/".$this->ordnerpfad."/absenz.txt");
		if(file_exists($_file)){
			$this->_array = file($_file);
			$i=0;
			foreach($this->_array as $string){
				$string = explode(";", $string);
				
				foreach($this->_filetext as $_zeile){
					$_zeile = explode(";", $_zeile);
					if (trim($string[1]) == trim($_zeile[1])){	
						$string[3] = trim($_zeile[0]);
						if (!trim($string[2])<>0) $string[2]=1;
						$string[4] = trim($_zeile[2]);
					}	
				}
				$this->_array[$i] = $string;
				$i++;
			}
		}
		$this->calc();
		return $this->_array;
	}	
	function calc(){
		if(!$this->_calc){
			$o=0;
			foreach($this->_filetext as $_zeile){
				$_zeile = str_replace("ä","ae", $_zeile);
				$_zeile = str_replace("ö","oe", $_zeile);
				$_zeile = str_replace("ü","ue", $_zeile);
				$_zeile = explode(";", $_zeile);
				$this->_calc[$o] = array($_zeile[0], $_zeile[1], $_zeile[2], 0);
				$o++;
			}		
		}
	}
	function get_absenztext(){
		return $this->_filetext;
	}
	function insert_absenz($ordnerpfad, $_w_jahr){
		$_zeilenvorschub = "\r\n";
        $_file = "./Data/".$ordnerpfad."/Timetable/A" . $_w_jahr;
        if (!file_exists($_file)) {
             $_meldung=  "Keine Daten vorhanden, folgende Datei wurde versucht zu &ouml;ffnen ". $_file;
        }else{
        	$_abwesenheit = file($_file);
        }
        $_timestamp 	= $_GET['timestamp'];
        $_grund 		= $_POST['_grund'];
        $_anzahl		= $_POST['_anzahl'];
        $fp = fopen($_file,"a+");
        fputs($fp, $_timestamp.";".$_grund.";".$_anzahl.$_zeilenvorschub);
        fclose($fp);	
	}
	function delete_absenz($ordnerpfad, $_w_jahr){
		$_timestamp	= $_GET['timestamp'];
        $_file 		= "./Data/".$ordnerpfad."/Timetable/A" . $_w_jahr;
        $_absenzliste = file($_file);
        $i=0;
        foreach($_absenzliste as $string){
                $string = explode(";", $string);
                if ($string[0] == $_timestamp) {
                        unset($_absenzliste[$i]);
                }
                $i++;
        }
        $neu = implode( "", $_absenzliste);
        $open = fopen($_file,"w+");
        fwrite ($open, $neu);
        fclose($open);
	}
	public function __destruct() {
   	}
}