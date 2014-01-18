<?php
/*******************************************************************************
* Absenzen - Klasse
/*******************************************************************************
* Version 0.8
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_absenz{
	public $_array = NULL;
	public $_filetext  = NULL;
	function __construct($ordnerpfad,$jahr){	
		$_file = "./Data/".$ordnerpfad."/Timetable/A" . $jahr;
		$this->_filetext = file("./Data/".$ordnerpfad."/absenz.txt");
		//print_r($this->_text);
		if(file_exists($_file)){
			$this->_array = file($_file);
			$i=0;
			foreach($this->_array as $string){
				$string = explode(";", $string);
				foreach($this->_filetext as $_zeile){
					$_zeile = explode(";", $_zeile);
					//echo "Vergleich : ". trim($string[1])." == ". trim($_zeile[1]);
					if (trim($string[1]) == trim($_zeile[1])){	
						//echo "---------------ja";
						$string[3] = trim($_zeile[0]);
						//echo "-".trim($string[2])."-";
						if (!trim($string[2])<>0) $string[2]=1;
						$string[4] = trim($_zeile[2]);
					}	
					//echo "<br>";
				}
				$this->_array[$i] = $string;
				$i++;
			}
		}
		return $this->_array;
	}	
	function get_absenztext(){
		return $this->_filetext;
	}
	function insert_absenz($ordnerpfad, $_w_jahr){
		$_zeilenvorschub = "\r\n";
        $_file = "./Data/".$ordnerpfad."/Timetable/A" . $_w_jahr;
        if (!file_exists($_file)) {
             $_meldung=  "Keine Daten vorhanden, folgende Datei wurde versucht zu öffnen ". $_file;
        }else{
        	//$_meldung = "Datei vorhanden : ". $_file;
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
	function __destruct(){
	}
}
?>