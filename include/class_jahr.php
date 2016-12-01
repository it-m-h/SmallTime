<?php
/*******************************************************************************
* Jahresberechnung
/*******************************************************************************
* Version 0.9.005
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_jahr{		
	public $_jahr 			= NULL;		// Startjahr des Users
	public $_timestamp		= NULL;		// welches jahr wurde gewählt, bzw Monat wurde gewählt
	public $_summe_t		= NULL;		// Summe seit Beginn inkl. Übertrag
	public $_modell 		= NULL;		// Zeitberechnungsmodell (0=normal, alle kumuliertd, 1 = Jährlich, 2 Monatlich) (datei ./Data/user/userdaten.txt zeile 16 erweitern mit 0,1,2)
	public $_summe_F	= NULL;		// Feriensumme
	public $_summe_vorholzeit;
	public $_CalcToTimestamp	=TRUE;	
	public $_saldo_t		= NULL;		// Zeitsaldo
	public $_saldo_F		= NULL;		// Feriensaldo
	public $_saldo_Fv		= NULL;		// Vergangene Ferien
	public $_saldo_Fz		= NULL;		// Eingetragene Ferien in der Zukunft
	public $_arr_ausz		= NULL;		// Auszahlungen als array (Monat, Jahr, Anzahl)
	public $_tot_ausz		= NULL;		// Auszahlungen summe
	public $_ordnerpfad		= NULL;		// Pfad zu den Daten
	public $_startjahr 		= NULL;		// Beginn der Zeitrechnung in den User - Einstellungen
	public $_startmonat		= NULL;		// Beginn der Zeitrechnung in den User - Einstellungen
	public $_array			= NULL;		// Array des Jahres
	public $_data			= NULL;		// Array der Daten	
	public $_Ferien_pro_Jahr;
	public $_Stunden_uebertrag; 		
	public $_Ferienguthaben_uebertrag;
	//public $Ferien_pro_Jahr; 		
	public $_Vorholzeit_pro_Jahr; 	
	
	function __construct($ordnerpfad, $jahr, $startjahr, $Stunden_uebertrag, $Ferienguthaben_uebertrag, $Ferien_pro_Jahr, $Vorholzeit_pro_Jahr, $modell, $_timestamp){	
		$this->_ordnerpfad 			= $ordnerpfad;
		$this->_timestamp 			= $_timestamp;
		// Jahr auf aktuell setzten falls kein Endjahr angegeben ist
		if($jahr==0) $this->_jahr = date("Y", time());
		$this->_startjahr 			= date("Y",$startjahr);
		$this->_startmonat 			= date("n",$startjahr);
		$this->_Stunden_uebertrag 	= $Stunden_uebertrag;
		$this->_Ferienguthaben_uebertrag = $Ferienguthaben_uebertrag;
		$this->_Ferien_pro_Jahr 		= $Ferien_pro_Jahr;
		$this->_Vorholzeit_pro_Jahr 	= $Vorholzeit_pro_Jahr;	
		$this->_modell 				= $modell;	
		$this->_CalcToTimestamp 	= $_SESSION['calc'] ;
		$this->calc_feriensumme();
		// ---------------------------------------------------------------------------------------
		// Falls jeden Monat die Überzeit auf 0 gestellt wird:
		if($this->_modell ==2){ 
			$this->calc_month();
			// ---------------------------------------------------------------------------------------
			// Falls jedes Jahr die Überzeit auf 0 gestellt wird:
		}elseif($this->_modell ==1){ 
			$this->calc_auszahlungen_year();
			$this->calc_year();
			// ---------------------------------------------------------------------------------------
			// kumuliert
		}else{ 
			$this->calc_auszahlungen();
			$this->calc_kumuliert();
		}
		$this->_saldo_t = round($this->_saldo_t,2);
		$this->savetotal();
	}
	function get_auszahlung($monat, $jahr){
		$anz = 0;
		for($i=0; $i< count($this->_arr_ausz);$i++){
			if($this->_CalcToTimestamp && date("Y", $this->_timestamp)>trim($monat)){
				if(strstr(trim($this->_arr_ausz[$i][0]),trim($monat)) && strstr(trim($this->_arr_ausz[$i][1]),trim($jahr))){
					$anz =  $this->_arr_ausz[$i][2];
				}
			}elseif(!$this->_CalcToTimestamp){
				if(strstr(trim($this->_arr_ausz[$i][0]),trim($monat)) && strstr(trim($this->_arr_ausz[$i][1]),trim($jahr))){
					$anz =  $this->_arr_ausz[$i][2];
				}
			}	
		}
		return $anz;
	}
	function calc_auszahlungen_year(){
		// Auszahlungen berechnen (Datei ./Data/username/Timetable/auszahlungen : Monat;Jahr;Anzahl)
		$file = "./Data/".$_SESSION['datenpfad'] ."/Timetable/auszahlungen";
		if(file_exists($file)){
			$this->_arr_ausz = file($file);
			for($i=0; $i< count($this->_arr_ausz);$i++){
				$this->_arr_ausz[$i] = explode(";", $this->_arr_ausz[$i]);
				// nur bis zum aktuellen Datum berechnen = $htis->_CalcToTimestamp
				if($this->_CalcToTimestamp && date("n", $this->_timestamp)>=$this->_arr_ausz[$i][0] && date("Y", $this->_timestamp)>=$this->_arr_ausz[$i][1]){
					if($this->_modell ==1 &&  date("Y", $this->_CalcToTimestamp)==$this->_arr_ausz[$i][1]){
						$this->_tot_ausz += $this->_arr_ausz[$i][2];
					}elseif(!$this->_modell ==1){
						$this->_tot_ausz += $this->_arr_ausz[$i][2];
					}
				}elseif(!$this->_CalcToTimestamp){
					if($this->_modell ==1 &&  date("Y", $this->_CalcToTimestamp)==$this->_arr_ausz[$i][1]){
						$this->_tot_ausz += $this->_arr_ausz[$i][2];
					}elseif(!$this->_modell ==1){
						$this->_tot_ausz += $this->_arr_ausz[$i][2];
					}
				}
			}
		}
	}
	function calc_auszahlungen(){
		// Auszahlungen berechnen (Datei ./Data/username/Timetable/auszahlungen : Monat;Jahr;Anzahl)
		$file = "./Data/".$_SESSION['datenpfad'] ."/Timetable/auszahlungen";
		if(file_exists($file)){
			$this->_arr_ausz = file($file);
			for($i=0; $i< count($this->_arr_ausz);$i++){
				$this->_arr_ausz[$i] = explode(";", $this->_arr_ausz[$i]);
				// nur bis zum aktuellen Datum berechnen = $htis->_CalcToTimestamp
				$tmpaustime = mktime(1,1,1,$this->_arr_ausz[$i][0],1,$this->_arr_ausz[$i][1]);
				$now = (int)$this->_timestamp;
				$_aj 		= date("Y", $this->_timestamp);
				$_am 	= date("m", $this->_timestamp);
				// wenn Auszahlungsjahr kleiner, alle Einträge
				if($this->_arr_ausz[$i][1]<$_aj){
					$this->_tot_ausz += $this->_arr_ausz[$i][2];
					//wenn Auszahlungsjahr gleich, dann nur bis zum Monat	
				}elseif($this->_arr_ausz[$i][1]==$_aj){
					if($this->_arr_ausz[$i][0]<=$_am){
						$this->_tot_ausz += $this->_arr_ausz[$i][2];
					}
				}
			}
		}
	}	
	
	function calc_month(){
		$i = date("Y", $this->_timestamp);
		$z = date("m", $this->_timestamp)-1;
		$file = "./Data/".$this->_ordnerpfad ."/Timetable/" . $i;
		if(!file_exists($file)){
			$fp = fopen($file, "w");
			fclose($fp); 
		}
		$this->_data[$i] = file($file);
		$z=0;
		foreach($this->_data[$i] as $zeile){
			$this->_data[$i][$z] = explode(";", $zeile);
			$z++;
		}			
		$this->_data[$i][$z] = explode(";", $this->_data[$i][$z]);
		$this->_saldo_t = $this->_data[$i][$z][0]; 		
	}
	
	function calc_year(){
		$i = date("Y", $this->_timestamp);
		$file = "./Data/".$this->_ordnerpfad ."/Timetable/" . $i;
		if(!file_exists($file)){
			$fp = fopen($file, "w");
			fclose($fp); 
		}
		$this->_data[$i] = file($file);
		$z=0;
		foreach($this->_data[$i] as $zeile){
			$this->_data[$i][$z] = explode(";", $zeile);
			// nur bis zum aktuellen Datum berechnen = $htis->_CalcToTimestamp
			if($this->_CalcToTimestamp && date("n", $this->_timestamp)>$z){
				$this->_summe_t = $this->_summe_t + $this->_data[$i][$z][0]; 
			}elseif(!$this->_CalcToTimestamp){
				$this->_summe_t = $this->_summe_t + $this->_data[$i][$z][0]; 
			}	
			$z++;
		}		
		// jährliche Vorholzeit - Summe hinzurechnen
		$this->_saldo_t = $this->_summe_t - $this->_Vorholzeit_pro_Jahr - $this->_tot_ausz;
		// im Start-Jahr Übertrag hinzufügen
		if($this->_startjahr==date("Y", $this->_timestamp)){
			$this->_saldo_t = $this->_saldo_t  + $this->_Stunden_uebertrag;	
		}					
	}
	
	function calc_kumuliert(){
		// Schleife - Startjahr bis Heute
		$_year_start = $this->_startjahr;
		$_year_heute = $this->_jahr;
		$_year_wahl = date('Y',$this->_timestamp);
		$_month_wahl = date('m',$this->_timestamp);
		$this->_summe_vorholzeit=0;
		for($i=$this->_startjahr; $i<=$_year_wahl ; $i++){
			$this->set_ueberschriften($i);
			$file = "./Data/".$this->_ordnerpfad ."/Timetable/" . $i;
			// Falls die Datei nicht existiert eine leere Datei erstellen
			if(!file_exists($file)){
				$fp = fopen($file, "w");
				fclose($fp); 
			}
			$this->_data[$i] = file($file);
			$z=0;
			// Schleife - Monats Daten in der Jahres Datei 
			foreach($this->_data[$i] as $zeile){
				$this->_data[$i][$z] = explode(";", $zeile);
				// nur bis zum aktuellen Datum berechnen = $htis->_CalcToTimestamp wenn der Monat auch im Gewählten Jahr liegt
				if($this->_CalcToTimestamp){
					// Jahr ist gleich, dann nur bis zum aktuellen Monat
					$year = date("Y", $this->_timestamp);
					if(date("Y", $this->_timestamp) == $i){
						if(date("n", $this->_timestamp)>$z){
							$this->_summe_t = $this->_summe_t + $this->_data[$i][$z][0];
						}
					}else{
						$this->_summe_t = $this->_summe_t + $this->_data[$i][$z][0];
					}
				}else{
					$this->_summe_t = $this->_summe_t + $this->_data[$i][$z][0];
				}						 
				$z++;
				$temp = $this->_summe_t  ;
			}
			// Jährliche Vorholzeit - Summe
			$_monate=0;
			if($this->_startjahr==$i){
				if($_year_wahl==$i){
					$_monate = $_month_wahl- $this->_startmonat+1;
				}else{
					$_monate = 13-$this->_startmonat;
				}
				$tmp = round($this->_Vorholzeit_pro_Jahr/12*$_monate,2);
				$this->_summe_vorholzeit += $tmp;
			}elseif($_year_wahl==$i){
				$_monate = $_month_wahl;
				$tmp = round($this->_Vorholzeit_pro_Jahr/12*$_monate,2);
				$this->_summe_vorholzeit += $tmp;
			}else{
				$this->_summe_vorholzeit += $this->_Vorholzeit_pro_Jahr;
			}
			
		}
		$this->_saldo_t 	= 	0;
		$this->_saldo_t 	= 	$this->_saldo_t + $this->_summe_t ;
		$this->_saldo_t 	=	$this->_saldo_t - $this->_summe_vorholzeit; 
		$this->_saldo_t 	=	$this->_saldo_t + $this->_Stunden_uebertrag;
		$this->_saldo_t 	=	$this->_saldo_t - $this->_tot_ausz;	
	}

	function calc_feriensumme(){
		// Schleife - Startjahr bis Heute
		$_year_start = $this->_startjahr;
		$_year_heute = $this->_jahr;
		$_year_wahl = date('Y',$this->_timestamp);
		$_month_wahl = date('m',$this->_timestamp);
		$_now_month= date('m',time());
		// wurde ein Monat in der vergangenheit gewählt nur bis dahin berechnen
		// falls es der aktuelle Monat ist bis zum heutigen Datum
		if($_year_heute==$_year_wahl AND $_now_month==$_month_wahl){
			$timestampvergleich = time();
		}else{
			// letzter Tag im Monat, darum 1. vom kommenden Monat berechnen minus einer sekunde...
			$_jahr = $_year_wahl;
			$_monat = $_month_wahl+1;
			$_tag = 1;
			$_stunde=0;
			$_minute = 0;
			$_sekunde=0;
			$_timestamp = mktime($_stunde, $_minute, $_sekunde, $_monat, $_tag, $_jahr);
			$timestampvergleich = $_timestamp-1;
		}	
		// Ferienberechnung Ferien vorher und zukunft
		$this->_summe_Fv = 0; // zukünftige Ferien
		$this->_summe_Fz = 0; // vergangene Ferien
		for($i=$this->_startjahr; $i<=$_year_wahl; $i++){
			$tmp_absenzen[$i] = new time_absenz($this->_ordnerpfad, $i);
			// Falls keine Einträge in den Ferien oder kein File vorhanden
			if(is_array($tmp_absenzen[$i]->_array[0])){
				foreach($tmp_absenzen[$i]->_array as $eintrag){
					if($eintrag[1]=='F'){
						$zahl = $eintrag[2];
						if($timestampvergleich <= $eintrag[0]){
							$this->_summe_Fz = $this->_summe_Fz + $eintrag[2];
						}else{
							$this->_summe_Fv = $this->_summe_Fv + $eintrag[2];
						}
					}
				}	
			}
			// bei Startjahr Ferienanspruch berechnen
			$this->_saldo_F += $this->calc_Ferien($i);
		}
		// Settings überprüfen, ob alle oder nur vergangene Ferien berechnet werden sollen
		$tmpsettings = new time_settings();
		if($tmpsettings->_array[27][1]==0){
			$this->_summe_F = $this->_summe_Fv  + $this->_summe_Fz;
		}else{
			$this->_summe_F =  $this->_summe_Fv;
		}

		//falls in der Ansicht ein alter Monat gewählt wurde und für PDF -> Ferien in der Zukunft noch nicht berechnen
		if(isset($_GET['timestamp'])){
			// Saldo der Ferien inkl. Übertrag berechnen
			$this->_saldo_F = $this->_saldo_F - $this->_summe_Fv + $this->_Ferienguthaben_uebertrag;
		}else{
			// Saldo der Ferien inkl. Übertrag berechnen
			$this->_saldo_F = $this->_saldo_F - $this->_summe_F + $this->_Ferienguthaben_uebertrag;
		}
		//runden auf 2 Stellen 
			$this->_saldo_F = round($this->_saldo_F,2);	
	}		
	function __destruct(){
	}
	function savetotal(){
		$_zeilenvorschub = "\r\n";
		$totalfie = "./Data/".$this->_ordnerpfad."/Timetable/total.txt";
		$fp = fopen($totalfie,"w+");
		fputs($fp, $this->_saldo_t);
		fputs($fp, $_zeilenvorschub);
		fputs($fp, $this->_saldo_F);
		fclose($fp);
	}
	
	function calc_Ferien($i){
		// Falls der Startmonat nicht der Januar ist, restliches Guthaben der Ferien berechnen
		if($this->_startmonat > 1 && $this->_startjahr == $i){
			$Ferien = round(($this->_Ferien_pro_Jahr / 12 * (13-$this->_startmonat)),2);
		}else{
			$Ferien = $this->_Ferien_pro_Jahr;
		}	
		return 	$Ferien;	
	}
	// TODO : löschen ist als Plugin gelöst
	function set_ueberschriften($jahr){
		//Erweiterung für Jahresübersicht --- in Planung
		$this->_array[$jahr][0][0] 	= "Monat";
		$this->_array[$jahr][0][1] 	= "Saldo";	
		$this->_array[$jahr][0][2] 	= "Soll";
		$this->_array[$jahr][0][3] 	= "Work";
		$this->_array[$jahr][0][4] 	= "Absenz";
		$this->_array[$jahr][0][5] 	= "F";
		$this->_array[$jahr][0][6] 	= "K";
		$this->_array[$jahr][0][7] 	= "U";
		$this->_array[$jahr][0][8] 	= "M";
		$this->_array[$jahr][0][9] 	= "I";
		$this->_array[$jahr][0][10]	= "W";
		$this->_array[$jahr][0][11]	= "E";
	}
}