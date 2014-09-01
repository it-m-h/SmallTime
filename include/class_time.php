<?php
/*******************************************************************************
* Timestamp für alle anderen Berechnungen
/*******************************************************************************
* Version 0.89
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/

class time{
	public $_jahr;
	public $_monat;
	public $_monatname;
	public $_tag;
	public $_stunde;
	public $_minute;
	public $_sekunde;
	public $_timestamp;
	public $_letzterTag;
	public $_runden;

	function __construct(){
		$this->_jahr = date("Y", time());
		$this->_monat = date("n", time());
		$this->_tag = date("j", time());
		$this->_stunde= date("H", time());
		$this->_minute = date("i", time());
		$this->_sekunde=0;
		$this->_timestamp = mktime($this->_stunde, $this->_minute, $this->_sekunde, $this->_monat, $this->_tag, $this->_jahr);
		$this->_letzterTag = idate(d,mktime(0, 0, 0, ($this->_monat+1), 0, $this->_jahr));
		//$this->_letzterTag = idate("d",mktime(0, 0, 0, ($this->_monat+1), 0, $this->_jahr));
		$this->_runden = 0;
	}
	function edit_accept($time,$settingday){
		$lastday = mktime(0, 0, 0, date("n", time()), date("j", time())-$settingday, date("Y", time()));
		//echo "<hr> teste : ". $time . " mit " . $lastday;
		if($time>=$lastday){
			//echo " ----------------------  Edit ok.";
			return true;
		}elseif($settingday==0){
			return true;
		}else{
			return false;
		}
	}
	function set_timestamp($time){
		$this->_jahr = date("Y", $time);
		$this->_monat = date("n", $time);
		$this->_tag = date("j", $time);
		$this->_stunde= date("H", $time);
		$this->_minute = date("i", $time);
		$this->_sekunde=0;
		if(date("s", $time)=='59' and date("i", $time)=='59'){
			$this->_minute = '00';
			$this->_stunde = $this->_stunde+1;	
		}
		//$this->_timestamp = $this->mktime($this->_stunde, $this->_minute, $this->_sekunde, $this->_monat, $this->_tag, $this->_jahr);
		$this->_timestamp = $time;
		$this->_letzterTag = idate(d,mktime(0, 0, 0, ($this->_monat+1), 0, $this->_jahr));	
		//echo "<hr>".$time."=".$this->_timestamp."<hr>";
	}
	function set_monatsname($strnamen){
		$strnamen = explode(";",$strnamen);
		$this->_monatname = ($strnamen[$this->_monat-1]);
	}
	function get_now(){
		return time();
	}
	function get_stunde_now(){
		return date("H", time());
	}
	function get_minute_now(){
		return date("i", time());
	}
	function get_lastmonth(){
		//$_arr = array();
		$_arr = 0;
		if($this->_monat==1){
			//$_arr[0] = 12;
			//$_arr[1] = mktime(0, 0, 0, 12, 1, $this->_jahr-1);
			//$_arr[2] = $this->_jahr-1;	
			$_arr = mktime(0, 0, 0, 12, 1, $this->_jahr-1);	
		}else{
			//$_arr[0] = $this->_monat-1;
			//$_arr[1] = mktime(0, 0, 0, $this->_monat-1, 1, $this->_jahr);
			//$_arr[2] = $this->_jahr;
			$_arr = mktime(0, 0, 0, $this->_monat-1, 1, $this->_jahr);	
		}
		//Monat - Zahl, Timestamp, Jahreszahl
		return $_arr;
	}
	function get_nextmonth(){
		//$_arr = array();
		$_arr = 0;
		global $_monate;
		if($this->_monat==12){
			//$_arr[0] = 1;
			//$_arr[1] = mktime(0, 0, 0, 1, 1, $this->_jahr+1);
			//$_arr[2] = $this->_jahr+1;
			$_arr = mktime(0, 0, 0, 1, 1, $this->_jahr+1);	
		}else{
			//$_arr[0] = $this->_monat+1;
			//$_arr[1] = mktime(0, 0, 0, $this->_monat+1, 1, $this->_jahr);
			//$_arr[2] = $this->_jahr;	
			$_arr = mktime(0, 0, 0, $this->_monat+1, 1, $this->_jahr);
		}
		//Monat - Zahl, Timestamp, , Jahreszahl
		return $_arr;	
	}
	function mktime($_w_stunde,$_w_minute,$_w_sekunde,$_w_monat,$_w_tag,$_w_jahr){
		if($_w_stunde == '24' and $_w_minute == '00'){
			$_w_stunde = '23';
			$_w_minute = '59';
			$_w_sekunde = '59';
		}elseif($_w_stunde == '00' and $_w_minute == '00'){
			$_w_stunde = '00';
			$_w_minute = '00';
			$_w_sekunde = '01';	
		}
		return mktime($_w_stunde,$_w_minute,$_w_sekunde,$_w_monat,$_w_tag,$_w_jahr);;
	}
	
	function save_time_list($_ordnerpfad){
		$_zeilenvorschub = "\r\n";
		$_file = "./Data/".$_ordnerpfad."/Timetable/" . $this->_jahr . "." . $this->_monat;
		$fp = fopen($_file,"a+");
		fputs($fp, mktime($_w_stunde, $_w_minute, $_w_sekunde, $_w_monat, $_w_tag, $_w_jahr));
		fputs($fp, $_zeilenvorschub);
		fclose($fp);
		$_timestamp = mktime($_w_stunde, $_w_minute, $_w_sekunde, $_w_monat, $_w_tag, $_w_jahr);
	}	
	function save_time($_timestamp, $_ordnerpfad){	
		$_zeilenvorschub = "\r\n";
		$_file = "./Data/".$_ordnerpfad."/Timetable/" . $this->_jahr . "." . $this->_monat;
		$fp = fopen($_file,"a+");
		fputs($fp, $_timestamp);
		fputs($fp, $_zeilenvorschub);
		fclose($fp);	
	}
	
	function set_runden($zahl){
		$this->_runden = (int) $zahl;
		//echo "zahl : " . $zahl . "<br>";
		//echo "runden : " . $this->_runden. "<br>";
	}
	function save_quicktime($_ordnerpfad){
		$_zeilenvorschub = "\r\n";
		$time = time();
		$_w_jahr = date("Y", $time);
		$_w_monat = date("n", $time);
		$_w_tag = date("j", $time);
		$_w_stunde= date("H", $time);
		$_w_minute = date("i", $time);
		$_w_sekunde=0;
		//$_timestamp = mktime($_w_stunde, $_w_minute, $_w_sekunde, $_w_monat, $_w_tag, $_w_jahr);
		$_file = "./Data/".$_ordnerpfad."/Timetable/" . $_w_jahr . "." . $_w_monat;
		// runden der Quicktime stempelzeit auf Minuten die in den Settings eingestellt ist
		if($this->_runden){
			//echo "Minuten : " . $_w_minute . "<br>"; 							// Beispiel : 58
			$_neu = round($_w_minute / $this->_runden,0)*$this->_runden; 		// Beispiel : 60
			$_von = $_neu - ($this->_runden/2); 								// Beispiel : 55
			$_bis = $_von + $this->_runden; 									// Beispiel : 65
			//echo "von : " .$_von . "<br>";
			//echo "bis : " . $_bis . "<br>";
			//echo "wirdzu : " . $_neu . "<br>";
			$_w_minute = $_neu;
		}
		$_timestamp = mktime($_w_stunde, $_w_minute, $_w_sekunde, $_w_monat, $_w_tag, $_w_jahr);
		//echo "<br>Minute: " . $_w_minute = date("i", $_timestamp);
		//echo " / Stunden: " . $_w_minute = date("H", $_timestamp);
		//echo "<hr>";
		
		$fp = fopen($_file,"a+");
		// Sekundengenau stempeln, kann zu Berechnungsfehlern führen 
		// Sekunden grösser als kommt und Minute geht kleiner als kommt, wird keine Stunde abgerechnet - Logik überprüfen)
		// fputs($fp, time());
		// Minutengenau
		fputs($fp, $_timestamp);
		fputs($fp, $_zeilenvorschub);
		fclose($fp);
		// Sekundengenau
		$this->set_timestamp(time());
		// Minutengenau	
		$this->set_timestamp($_timestamp);			
	}

	function update_stempelzeit($_oldtime, $_newtime, $_ordnerpfad){
		$_zeilenvorschub = "\r\n";
		$_file = "./Data/".$_ordnerpfad."/Timetable/" . $this->_jahr . "." . $this->_monat;
		//Stempelzeiten in ein Array speichern
		if(!file_exists($_file)){
			//echo "keine Daten vorhanden";
		}else{
			$_timeTable = file($_file);
		}
		$i=0;
		foreach($_timeTable as $_tmp){
			if(trim($_tmp) == trim($_oldtime)){
				//echo "Vergleich " . $_tmp. " == ".$_timestamp ." wird nun zu " .        $_timestamp_new . "<br>";
				$_timeTable[$i] = $_newtime.$_zeilenvorschub;
				$_oldtime = NULL;
			}elseif(trim($_tmp) == ""){
				//Leere zeile wird gelöscht, falls vorhanden
				unset($_timeTable[$i]);
			}else{
				//echo "Vergleich" . $_tmp. " == ".$_timestamp ." - Kein Update!<br>";
			}
			$i++;
		}
		$neu = implode( "", $_timeTable);
		$open = fopen($_file,"w+");
		fwrite ($open, $neu);
		fclose($open);
	}
	function delete_stempelzeit($_oldtime, $_ordnerpfad){
		$_file = "./Data/".$_ordnerpfad."/Timetable/" . $this->_jahr . "." . $this->_monat;
		//Stempelzeiten in ein Array speichern
		if(!file_exists($_file)){
			//echo "<hr>keine Daten vorhanden<hr>";
		}else{
			$_timeTable = file($_file);
		}
		$i=0;
		foreach($_timeTable as $_tmp){
			if(trim($_tmp) == trim($_oldtime)){
				//echo "Vergleich " . $_tmp. " == ".$_oldtime ." wird nun gelöscht<br>";
				unset($_timeTable[$i]);
				$_oldtime = NULL;
				//$_timeTable[$i] = $_timestamp_new.$_zeilenvorschub;
			}elseif(trim($_tmp) == ""){
				//Leere zeile wird gelöscht, falls vorhanden
				unset($_timeTable[$i]);
			}else{
				//echo "Vergleich" . $_tmp. " == ".$_oldtime ." - Kein Update!<br>";
			}
			$i++;
		}
		$neu = implode( "", $_timeTable);
		$open = fopen($_file,"w+");
		fwrite ($open, $neu);
		fclose($open);
	}
	function save_timestamp($_timestamp, $_ordnerpfad){
		$_zeilenvorschub = "\r\n";
		$jahr = date("Y", $_timestamp);
		$monat = date("n", $_timestamp);
		$_file = "./Data/".$_ordnerpfad."/Timetable/" . $jahr . "." . $monat;
		//echo "schreibe in :". $_file. " / Wert: ". $_timestamp . "<br>"; 
		$fp = fopen($_file,"a+");
		fputs($fp, $_timestamp);
		fputs($fp, $_zeilenvorschub);
		fclose($fp);		        
	}
	function checktime($_stunde,$_minute,$_monat,$_tag,$_jahr){
		$_sekunde=0;
		//echo "<hr>";
		//echo "ALT : ".$_tag . "/" . $_monat . "/" . $_jahr . " - " . $_stunde . ":" . $_minute. ":". $_sekunde. "<br>";
		if($_stunde == '24' && $_minute == '00'){
			$_stunde = 23;
			$_minute = 59;
			$_sekunde = 59;
			$_eintragen = mktime($_stunde, $_minute, $_sekunde, $_monat, $_tag, $_jahr);
			//echo "NEU : ".$_tag . "/" . $_monat . "/" . $_jahr . " - " . $_stunde . ":" . $_minute. ":". $_sekunde. "<br>";
			//echo "Timestamp alt". $_timestamp . " / Timestamp neu : ". $_timestampn . "<hr>";
		}
		//echo "<hr>";
		//echo "<hr>Timestamp alt". $_timestamp . " / Timestamp neu : ". $_timestamp . "<hr>";
		return $_eintragen;
	}
	function lasttime($_timestamp, $_ordnerpfad){
		$_zeilenvorschub = "\r\n";
		$jahr = date("Y", $_timestamp);
		$monat = date("n", $_timestamp);
		$_timeTable = NULL;
		$_file = "./Data/".$_ordnerpfad."/Timetable/" . $jahr . "." . $monat;	
		// diesen Monat überprüfen
		if(file_exists($_file)){
			$_timeTable = file($_file);
			//falls kein Eintrag. letzten Monat überprüfen
			//echo "File existiert, wird überprüft<br>";
			$datum = $this->timecount($_timeTable);
			//echo $_datum . " - " . $_lastday . "<hr>";
		}
		// letzten Monat überprüfen falls in diesem keine Einträge drin sind
		if(count($_timeTable)<1){
			//echo "File lastmonth existiert, wird überprüft<br>";
			$monat = $monat-1;
			$_file = "./Data/".$_ordnerpfad."/Timetable/" . $jahr . "." . $monat;
			if(file_exists($_file)){
				$_timeTable = file($_file);
				$datum = $this->timecount($_timeTable);
			}
		}
		if($datum){
			return mktime(0, 0, 0, $monat, $datum,  date("Y", $_timestamp));
		}else{
			return NULL;
		}		
	}
	private function timecount($_timeTable){
		$_lastday = NULL;
		rsort($_timeTable);
		foreach($_timeTable as $_tmp){
			//echo date("Y", $_tmp).".".date("n", $_tmp).".".date("j", $_tmp)."<br>";
			if(!$_lastday){
				$_lastday = date("j", $_tmp);
				$_count++;
			}elseif($_lastday == date("j", $_tmp)){ 
				$_count++;
			}
				
		}
		if($_count % 2){
			//wenn eine Zeit fehlt den Tag zurückgeben
			return $_lastday;
		}else{
			//falls keien Zeit fehlt
			return NULL;
		}
		
	}
}
?>
