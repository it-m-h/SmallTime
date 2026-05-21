<?php
/*******************************************************************************
* Auszahlung von Stunden
/*******************************************************************************
* Version 0.9.204
* Author:  IT-Master
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master, All rights reserved
*******************************************************************************/
class auszahlung{
	public $_ausz_jahr	= NULL;		// Auszahlungen summe
	public $_ausz_monat	= NULL;		// Auszahlungen summe
	public $_ausz_anz	= NULL;		// Auszahlungen summe
	public $_arr_ausz	= NULL;		// Auszahlungen als array (Monat, Jahr, Anzahl)
	public $_tot_ausz	= NULL;		// Auszahlungen summe
	
	function __construct($monat, $jahr){
		$this->_ausz_jahr 	= trim($jahr);
		$this->_ausz_monat 	= trim($monat);
		$this->calc_auszahlungen();
		$this->_ausz_anz	= $this->get_auszahlung($this->_ausz_monat, $this->_ausz_jahr);	
	}
        
	function save_auszahlung($anzahl){
		$_zeilenvorschub = "\r\n";
		if($anzahl=="" OR $anzahl == NULL) { $anzahl = 0; }
		$file = "./Data/".$_SESSION['datenpfad'] ."/Timetable/auszahlungen";
		if(count($this->_arr_ausz)==0){
			//Falls Datei leer ist, Eintrag speichern
			$this->_arr_ausz[] = $this->_ausz_monat .";".$this->_ausz_jahr  . ";" .  $anzahl;
			$neu = implode( "", $this->_arr_ausz);
			$open = fopen($file,"w+");
			fwrite ($open, $neu . $_zeilenvorschub);
			fclose($open);	
		}else{
			// suche ob schon ein Eintrag vorhanden ist
			$pos = -1;
			for($i=0; $i< count($this->_arr_ausz);$i++){
				if(trim($this->_arr_ausz[$i][0])==$this->_ausz_monat && trim($this->_arr_ausz[$i][1])==$this->_ausz_jahr){
					$pos =  $i;
					break;	
				}
			}
			//existiert schon ein Eintrag, falls nein, eine Zeile hinzufügen
			$_new = file($file);
			if($pos>=0){
				$_new[$pos] = $this->_ausz_monat .";".$this->_ausz_jahr  . ";" .  $anzahl.$_zeilenvorschub;
			}else{
				$_new[] = $this->_ausz_monat .";".$this->_ausz_jahr  . ";" .  $anzahl.$_zeilenvorschub;
			}
			$neu = implode( "", $_new);
			$open = fopen($file,"w+");
			fwrite ($open, $neu);
			fclose($open);
		}
	}
	
	function save($array){
		$_zeilenvorschub = "\r\n";
		$file = "./Data/".$_SESSION['datenpfad'] ."/Timetable/auszahlungen";
		$fp = fopen($file,"w+");				
		fputs($fp, $array.$_zeilenvorschub);		
		fclose($fp);
	}

	function get_numeric_value($value){
		if(is_array($value)){
			$value = $value[0] ?? 0;
		}
		$value = trim((string)$value);
		if($value === ""){
			return 0.0;
		}
		return floatval(str_replace(',', '.', $value));
	}
              
	function get_auszahlung($monat, $jahr){
		$anz = 0;
		for($i=0; $i< count($this->_arr_ausz);$i++){
			if(trim($this->_arr_ausz[$i][0])== trim($monat) && trim($this->_arr_ausz[$i][1])== trim($jahr)){		
				$anz =  $this->get_numeric_value($this->_arr_ausz[$i][2] ?? 0);				
			}
		}
		return $anz;
	}
	
	function calc_auszahlungen(){
		// Auszahlungen berechnen (Datei ./Data/username/Timetable/auszahlungen : Monat;Jahr;Anzahl)
		$file = "./Data/".$_SESSION['datenpfad'] ."/Timetable/auszahlungen";
		if(file_exists($file)){
			$this->_arr_ausz = file($file);
			for($i=0; $i< count($this->_arr_ausz);$i++){
				$this->_arr_ausz[$i] = explode(";", $this->_arr_ausz[$i]);
				if(isset($this->_arr_ausz[$i][2])){
					$this->_tot_ausz += $this->get_numeric_value($this->_arr_ausz[$i][2]);
				}
			}
		}else{
			$fp = fopen($file, "w");
			fclose($fp); 
		}
	}	
}