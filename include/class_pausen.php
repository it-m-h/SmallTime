<?php
/*******************************************************************************
* Automatische Pausenregelung für Mitarbeiter 
* werden von den Arbeitszeiten abgezogen
/*******************************************************************************
* Version 0.9.1
* Author:  IT-Master
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master, All rights reserved
*******************************************************************************/
abstract
class pausen{
	function __construct($monat, $jahr, $users){
		
	}
	public static function get(){
		$file 			= new time_filehandle('./include/Settings/', 'pausen.txt', '\r\n');
		$text = '';
	
		if($file->_array[0] == 'keine Daten vorhanden!'){
			$text = 'nix drin, alte Daten übernehmen';
			$anzahl = 0;
			$_settings 	= new time_settings();
			//$_settings von alten einstellungen übernehmen wenn im neuen File nix drin ist
			if($_settings->_array[21][1]){
				$time = $_settings->_array[22][1] * 60;	
				$file->_array[$anzahl] = $_settings->_array[21][1] . ';99;' . $time;		
				$anzahl = 1;
				// $_settings Pausen übernehmen von alten Einstellugnen 
				$newpausen = new time_filehandle('./include/Settings/', 'pausen.txt', '\r\n');
				$newpausen->insert_line($file->_array[0]);
				$newpausen = null;
				// $_settings Pausen auf 0 stellen 
				$_settings->_array[21][1] = "0";
				$_settings->_array[22][1] = "0";
				$savearr = array();
				foreach($_settings->_array as $eintrag){
					$savearr[] = implode('#', $eintrag);
				}
				$_settings->save_array($savearr);			
			}
		}else{
			$text = 'anzahl Datensätze: ' . count($file->_array);
			$anzahl = count($file->_array);
		}
		if($file->_array[0] == 'keine Daten vorhanden!'){
			//$anzahl = 0;
		}else{
			//$anzahl = count($file->_array);
		}
		return $file->_array;	
	}
	public static function save(){
		$meldung = "";
		// ----------------------------------------------------------------------------
		// $pausen = get_pausen();
		// ----------------------------------------------------------------------------
		$anzahlpausen =  (count($_POST)-4)/3;	
		// ----------------------------------------------------------------------------
		// FORM - auslesen
		// ----------------------------------------------------------------------------
		if($anzahlpausen){
			$pausen = array();
			for($x=1; $x<=$anzahlpausen; $x++){
				$str = trim($_POST['time1_'.$x]);
				$str .= ';';
				$str .= trim($_POST['time2_'.$x]);
				$str .= ';';
				$str.= trim($_POST['pause_'.$x]);
				$pausen[$x] = $str;			
			}
		}
		// ----------------------------------------------------------------------------
		// FORM - neues Element?
		// ----------------------------------------------------------------------------
		if($_POST['time1_x']!= ''){
			$str = trim($_POST['time1_x']);
			$str .= ';';
			$str .= trim($_POST['time2_x']);
			$str .= ';';
			$str.= trim($_POST['pause_x']);
			$pausen[] = $str;
		}
		if($pausen){
			
		
			// ----------------------------------------------------------------------------
			// daten sortieren
			// ----------------------------------------------------------------------------
			natsort($pausen);
			// ----------------------------------------------------------------------------
			// FORM - checkdata
			// ----------------------------------------------------------------------------
			$checkdata = array();
			foreach($pausen as $pause){
				$checkdata[] = explode(';', $pause);	
			}
			for($x=0; $x <= count($checkdata)-1; $x++){
				if($checkdata[$x][0] > $checkdata[$x][1]){
					$meldung .= 'Pos: ' . $x . ': Werte überprüfen, von bis Stunden verkehrt : ' . $checkdata[$x][0] .' > '. $checkdata[$x][1] . '<br>';
				}
				if($checkdata[$x][0] == $checkdata[$x][1]){
					$meldung .= 'Pos: ' . $x . ': Werte überprüfen, von bis Stunden identisch : ' . $checkdata[$x][0] .' == '. $checkdata[$x][1] . '<br>';
				}
				
				if(isset($checkdata[$x+1][0])){
					if($checkdata[$x][1] > $checkdata[$x+1][0]){
						$meldung .= 'Pos: ' . ($x+1) . ' und ' . ($x+2) . ' : Werte überprüfen, überschneidungen : ' . $checkdata[$x][1] .' > '. $checkdata[$x+1][0] . '<br>';
					}
				}
				
			}
			// ----------------------------------------------------------------------------
			// daten speichern
			// ----------------------------------------------------------------------------
			$text = implode('\r\n', $pausen);
			$newpausen = new time_filehandle('./include/Settings/', 'pausen.txt', '\r\n');
			$newpausen->clear_file();
			foreach($pausen as $pause){
				$newpausen->insert_line($pause);
			}
			$newpausen = null;
		}
		return $meldung;
	}
	public static function delete($id){
		$pausen = new time_filehandle('./include/Settings/', 'pausen.txt', '\r\n');
		$pausen->delete_line($id);
	}
	public static function check($vergleichszeit){
		$return = array();
		$return[0] = 0;
		$return[1] = 0;
		$pausen = new time_filehandle('./include/Settings/', 'pausen.txt', '\r\n');
		$p=0;
		foreach($pausen->_array as $pause){
			$tmp_pausen = explode(';',$pause);
			if(count($tmp_pausen)>1){
				$minpause = floatval($tmp_pausen[0]);
				$maxpause = floatval($tmp_pausen[1]);
				if(is_array($tmp_pausen)){
					if($minpause<= $vergleichszeit AND $maxpause > $vergleichszeit){
						$return[0] = round( $tmp_pausen[2]/60,2);
						$return[1] = $p+1;
						return $return;
					}
				}	
				$p++;
			}
		}
		return $return;
	}
}