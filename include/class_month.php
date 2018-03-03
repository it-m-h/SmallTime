<?php
/*******************************************************************************
* Monatsberechnungen
/*******************************************************************************
* Version 0.9.020
* Author: IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_month{
	private $_file					= NULL;	// Datei - Pfad inkl. Name mit Stempelzeiten
	private $_pfad 				= NULL;	// Ordnerpfad
	private $_wochentage			= NULL;	// Bezeichnung der Wochentage
	private $_arbeitstage			= NULL;	// User - Arbeitstage - Einstellungen	
	private $_u_feiertage			= NULL;	// User - Feiertage Einstellungen
	private $_feiertage				= NULL;	// Feiertage - Datum und Name in einem Array
	private $_absenz				= NULL;	// Absenzen
	private $_timeTable				= NULL;	// Zeiteinträge in einem Array
	private $_startzeit				= NULL;	// Beginn der Zeitrechnung
	private $_arbeitszeit			= NULL;
	private $_autopause			= NULL;
	private $_setautopause			= "";
	private $_zeitzuschlag			= NULL;
	private $_absenzberechnung 		= NULL;	
	private $_absenzberechnungArbeitszeit = NULL;
	public $_SollProTag 			= NULL;	// Soll Arbeitszeit pro Tag
	public $_letzterTag				= NULL;	// Anzahl der Tage im gewählen Monat	
	public $_SummeSollProMonat 	= NULL;	// Summe der Soll - Stunden im Monat
	public $_SummeWorkProMonat 	= NULL;	// Summe der gearbeiteten Stunden im Monat
	public $_SummeAbsenzProMonat 	= NULL;
	public $_SummeSaldoProMonat 	= NULL;	// Saldo in dem aktuellen Monat
	public $_SummeStempelzeiten	= NULL;	// ungerade Zahl, damit existiert ein Fehler in der Berechnung
	public $_SummeFerien			= NULL;
	public $_SummeKrankheit		= NULL;
	public $_SummeUnfall			= NULL;
	public $_SummeMilitaer			= NULL;
	public $_SummeIntern			= NULL;
	public $_SummeWeiterbildung	= NULL;
	public $_SummeExtern			= NULL;
	public $_MonatsArray 			= NULL;	// Array des Monats
	public $_modal				= NULL;
	public $_modal_str				= NULL;
	
	function __construct($SettingCountry, $lastday, $ordnerpfad, $jahr, $monat, $arbeitstage, $ufeiertag, $_SollProTag, $_startzeit, $arbeitszeit, $autopause, $absenzberechnung, $absenzberechnungArbeitszeit){
		$this->_file = "./Data/".$ordnerpfad."/Rapport/";
		$this->_pfad = "./Data/".$ordnerpfad."/";
		$this->_arbeitstage	= $arbeitstage;
		$this->_u_feiertage = $ufeiertag;
		$this->_SollProTag = $_SollProTag;
		$this->_wochentage = array("So","Mo","Di","Mi","Do","Fr","Sa");
		$this->_startzeit	= $_startzeit;
		$this->set_Monatsueberschrift();
		$this->set_letzterTag($lastday);
		$this->set_timetable_daten($ordnerpfad, $jahr, $monat);
		$tmp = new time_feiertage($jahr, $SettingCountry, $this->_u_feiertage);
		$this->_feiertage = $tmp->_feiertage;
		$this->_absenz = new time_absenz($ordnerpfad, $jahr);
		$this->_arbeitszeit = $arbeitszeit;	
		$this->_autopause	= $autopause;
		$this->_absenzberechnung = $absenzberechnung;
		$this->_absenzberechnungArbeitszeit = $absenzberechnungArbeitszeit;
		//Absenzenberechnung nur bis Heute in den Settings?
		if($absenzberechnung){ $this->absenzsetting(); }
		//Monatsdaten berechnen und im Array speichern
		$this->set_monatsdaten($monat,$jahr);
		// Monatssummen, Zeit und Ferien speichern in der Jahresdatei
		$this->save_data($monat,$jahr);
	}
	function __destruct(){
	}
	function get_calc_absenz(){
		return $this->_absenz->_calc;
	}  
	private function absenzsetting(){
		for($i=0; $i<= count($this->_absenz->_array);$i++){
			if(@$this->_absenz->_array[$i][0] >= time()){
				$this->_absenz->_array[$i][2]="";
			}	
		}
	}
	public function save_data($monat,$jahr){
		$_zeilenvorschub = "\r\n";
		$_file = $this->_pfad ."Timetable/" . $jahr;
		if(!file_exists($_file)){
			$fp = fopen($_file,"w+");
			fputs($fp, ";".$_zeilenvorschub);
			fputs($fp, ";".$_zeilenvorschub);
			fputs($fp, ";".$_zeilenvorschub);
			fputs($fp, ";".$_zeilenvorschub);
			fputs($fp, ";".$_zeilenvorschub);
			fputs($fp, ";".$_zeilenvorschub);
			fputs($fp, ";".$_zeilenvorschub);
			fputs($fp, ";".$_zeilenvorschub);
			fputs($fp, ";".$_zeilenvorschub);
			fputs($fp, ";".$_zeilenvorschub);
			fputs($fp, ";".$_zeilenvorschub);
			fputs($fp, ";");
			fclose($fp);
		}else{
			$_year_data = file($_file);
		}
		// Saldo; Ferien; Sollstunden; Work
		// (Sollstunden und Work mit Jahresanzeige berechnen und eintragen lassen)
		$_stunden = str_ireplace('\n','',$_year_data[$monat-1] );
		$_stunden = str_ireplace('\r', '', $_stunden);
		$_stunden = trim($_stunden);
		$_stunden = explode(";", $_stunden);
		if(
			($_stunden[0]!=trim($this->_SummeSaldoProMonat))
			or
			($_stunden[2]!=trim($this->_SummeSollProMonat))
			or
			($_stunden[3]!=trim($this->_SummeWorkProMonat))
		){
			$_year_data[$monat-1] = $this->_SummeSaldoProMonat.";".$this->_SummeFerien.$_zeilenvorschub;
			$_str = $this->_SummeSaldoProMonat.";";
			$_str .= $this->_SummeFerien.";";
			$_str .= $this->_SummeSollProMonat.";";
			$_str .= $this->_SummeWorkProMonat;
			$_str .= $_zeilenvorschub;
			$_year_data[$monat-1]  = $_str;
			$_FT = implode("", $_year_data);
			$fp = fopen($_file,"w+");
			fwrite ($fp, $_FT);
			fclose($fp);
		}
	}
	private function set_monatsdaten($monat,$jahr){	
		$this->_modal_str = "&modal";
		for($i=1; $i<=$this->_letzterTag; $i++){
			$_Day = mktime(0, 0, 0, $monat, $i, $jahr);	
			$this->_MonatsArray[$i][0] = $_Day;
			$this->_MonatsArray[$i][1] = date("d.m", $_Day);
			$_tagNR = date("w", $_Day);	
			$this->_MonatsArray[$i][2] = $_tagNR;
			$this->_MonatsArray[$i][3] = $this->_wochentage[$_tagNR];
			$this->_MonatsArray[$i][4] = $this->_arbeitstage[$_tagNR];
			$this->_MonatsArray[$i][5] = $this->is_feiertag($_Day, $i);
			$tmp = $this->_MonatsArray[$i][5];
			$wahl = $this->_MonatsArray[$i][5];
			$tmparr = array_keys($this->_feiertage);
			if($wahl>=0){
				$this->_MonatsArray[$i][6] = $tmparr[$wahl];
			}else{
				$this->_MonatsArray[$i][6] = '';
			}
			$this->_MonatsArray[$i][7] = ($this->_MonatsArray[$i][4]>'0' && $this->_MonatsArray[$i][5] == -1)? "1":"0";
			// Falls das Datum in der Zukunft liegt, noch kein Arbeitstag und keine Zeitrechnung
			if($_Day > time()) $this->_MonatsArray[$i][7]=0;	
			$this->_MonatsArray[$i][8] = ($this->_MonatsArray[$i][7])? $this->_SollProTag*$this->_MonatsArray[$i][4]:0;
			$this->_MonatsArray[$i][9] = " ";
			$this->_MonatsArray[$i][10] = $this->get_timestamps($_Day);	
			$this->_MonatsArray[$i][11] = count($this->_MonatsArray[$i][10]);
			$this->_MonatsArray[$i][12] = "";

			$this->_setautopause = "";
			// Arbeitsstunden berechnen und Zeitanzeige generieren
			$this->_MonatsArray[$i][13] = $this->get_time($i);
			// Falls das Datum in der Zukunft liegt Arbeitsstunden nicht berechnen
			if($_Day>time()) $this->_MonatsArray[$i][13] = 0;
			// Falls das Datum vor der Zeitrechung ist - keine Sollzeit
			if($_Day<$this->_startzeit) $this->_MonatsArray[$i][8] = 0;				
			// check, ob Absenzen vorhanden sind
			if(is_array($this->_absenz->_array)){
				$tmp = $this->get_absenz($_Day);
			}else{
				$tmp = array();
			}
			$this->_MonatsArray[$i][14] = 0;
			$this->_MonatsArray[$i][15] = 0;
			$this->_MonatsArray[$i][16] = 0;
			$this->_MonatsArray[$i][17] = 0;
			if(isset($tmp[1])) $this->_MonatsArray[$i][14] = $tmp[1];
			if(isset($tmp[2])) $this->_MonatsArray[$i][15] = $tmp[2];
			if(isset($tmp[3])) $this->_MonatsArray[$i][16] = $tmp[3];
			if(isset($tmp[4])) $this->_MonatsArray[$i][17] = $tmp[4];	
			//$this->_MonatsArray[$i][14] = $tmp[1];
			//$this->_MonatsArray[$i][15] = $tmp[2];	// Anzahl der Absenz
			//$this->_MonatsArray[$i][16] = $tmp[3];
			//$this->_MonatsArray[$i][17] = $tmp[4]; 
			$tmp1=0;
			// Liegen die Absenzen oder die Zeiten in der Zukunft, dann nicht berechnen
			if($this->_MonatsArray[$i][15]<>0 && ($this->_MonatsArray[$i][4]==0 || $this->_MonatsArray[$i][5]<> -1) && $_Day<time()){
				$tmp=$this->_SollProTag;$tmp1=1;
			}else{
				$tmp=$this->_MonatsArray[$i][8];$tmp1=0;
			}
			$tmp = round($tmp*$this->_MonatsArray[$i][15]*$this->_MonatsArray[$i][17]/100, 2);
			$this->_MonatsArray[$i][18] = $tmp;
			$this->_MonatsArray[$i][19] = "";

			//-------------------------------------------------------------------------
			// Zeitberechnung
			//-------------------------------------------------------------------------
			// Arbeitszeit 	(13) 	in Stunden
			// Sollzeit 	(8) 	in Stunden
			// Absenz 	(15) 	in Tagen
			// Absenzgew	(17) in Prozent
			// Absenzstd	(18) in Stunden
			// Arbeitstag 	(4) 	in Anzahl
			// Wenn Absenz und keine Arbeitszeiten dann ist Absenz = Absenz (Anzahl) * Arbeitstag (Gewichtung)
			if($this->_MonatsArray[$i][15] and $this->_MonatsArray[$i][13] == 0){
				$this->_MonatsArray[$i][15] = round($this->_MonatsArray[$i][15]*$this->_MonatsArray[$i][4],2);
			}
			// saldo pro Tag = arbeitszeit(13) plus absenzzeit(18) minus soll(8)
			$saldo = 0;
			$saldo = $this->_MonatsArray[$i][13] + $this->_MonatsArray[$i][18] - $this->_MonatsArray[$i][8];
			// wenn gearbeitet grösser als soll, dann Absenzen ignorieren
			if($this->_MonatsArray[$i][13] > $this->_MonatsArray[$i][8] and $this->_MonatsArray[$i][15] == 1){
				$this->_MonatsArray[$i][15] = 0;
				$this->_MonatsArray[$i][18] = 0;
				$saldo = $this->_MonatsArray[$i][18] + $this->_MonatsArray[$i][13] - $this->_MonatsArray[$i][8];
			}
			//wenn absenz(15) == 1 Prozentual ausrechnen sowie tmp=0(nicht in der Zukunft)
			if($this->_MonatsArray[$i][15] == 1 and $tmp1==1){
				$this->_MonatsArray[$i][18] = round(($this->_MonatsArray[$i][8] - $this->_MonatsArray[$i][13])*$this->_MonatsArray[$i][17]/100, 2);
				$this->_MonatsArray[$i][15] = round(($this->_MonatsArray[$i][8] - $this->_MonatsArray[$i][13])/$this->_MonatsArray[$i][8],2);
				$this->_MonatsArray[$i][15] = round($this->_MonatsArray[$i][15]*$this->_MonatsArray[$i][4],2);
				$saldo = $this->_MonatsArray[$i][18] + $this->_MonatsArray[$i][13];
			}
			// wenn eine Absenz vorhanden ist und das saldo >0 sowie tmp=0(nicht in der Zukunft)
            if($this->_absenzberechnungArbeitszeit == 1){
			    if($this->_MonatsArray[$i][15] == 1 and $saldo > 0 and $tmp1==0){
                $this->_MonatsArray[$i][18] = round(($this->_MonatsArray[$i][8] - $this->_MonatsArray[$i][13])*$this->_MonatsArray[$i][17]/100, 2);
                $this->_MonatsArray[$i][15] = round((($this->_MonatsArray[$i][8]-$this->_MonatsArray[$i][13])/$this->_MonatsArray[$i][8]),2);
                $this->_MonatsArray[$i][15] = round($this->_MonatsArray[$i][15]*$this->_MonatsArray[$i][4],2);
                $saldo = $this->_MonatsArray[$i][18] + $this->_MonatsArray[$i][13] - $this->_MonatsArray[$i][8];
                }
			}
			$saldo = round($saldo,2);
			$this->_MonatsArray[$i][20] = $saldo;
			if($i>0){
				$this->_MonatsArray[$i][21] = round($this->_MonatsArray[$i-1][21] + $this->_MonatsArray[$i][20],2);
			}else{
				$this->_MonatsArray[$i][21] = $this->_MonatsArray[$i][20];
			}
			//-------------------------------------------------------------------------
			// für die Darstellung
			//-------------------------------------------------------------------------
			$this->_MonatsArray[$i][30] = "class=td_background_tag";
			if(!$this->_MonatsArray[$i][4] OR $this->_MonatsArray[$i][4]==0) $this->_MonatsArray[$i][30] = "class=td_background_wochenende";
			if($this->_MonatsArray[$i][6]<>"") $this->_MonatsArray[$i][30] = "class=td_background_feiertag";
			if(date("Y.m.d", $this->_MonatsArray[$i][0]) == date("Y.m.d", time())) $this->_MonatsArray[$i][30] = "class=td_background_heute";	
			// Links
			if($this->_MonatsArray[$i][14]<>""){
				$this->_MonatsArray[$i][31] = "<a class='deleteabsenz' title='delete Absenz' href='?action=delete_absenz&timestamp=".$this->_MonatsArray[$i][0]."'><img src='images/icons/date_delete.png' border=0></a>";
				// Info bezüglich Absenz
				$this->_MonatsArray[$i][32] = "<img border='0' src='images/icons/information.png' title='".trim($this->_MonatsArray[$i][15])." Tag ".trim($this->_MonatsArray[$i][16])." / Bezahlt : ".trim($this->_MonatsArray[$i][17])."%'>";
				$this->_MonatsArray[$i][32] = trim($this->_MonatsArray[$i][15])." Tag ".trim($this->_MonatsArray[$i][16])." / Bezahlt : ".trim($this->_MonatsArray[$i][17])."%";	
			}else{
				$this->_MonatsArray[$i][31] = "<a href='?action=add_absenz&timestamp=".$this->_MonatsArray[$i][0].$this->_modal_str."' title='Absenz hinzuf&uuml;gen'><img border='0' src='images/icons/date_add.png'></img></a>";
				$this->_MonatsArray[$i][32] = " ";
			}
			// Rapport - hinzufügen oder löschen falls vorhanden
			$this->_MonatsArray[$i][34] = $this->get_rapport($this->_MonatsArray[$i][0]);	

			$_file = $this->_pfad."Rapport/" . date("Y.m.d", $this->_MonatsArray[$i][0]);
			if(file_exists($_file)){
				// DIV, das sich öffnet und den Text anzeigt im Rapport
				$text = $this->_MonatsArray[$i][34];
				$text = str_replace("\n", "<br>", $text);
				$text = str_replace("\r", "", $text);
				$this->_MonatsArray[$i][33] = '';	
				$this->_MonatsArray[$i][33] .= '<a ';
				$this->_MonatsArray[$i][33] .= 'onMouseout="RapportMouseout();" ';
				$this->_MonatsArray[$i][33] .= 'onMouseover="RapportMouseover(event, \'' . $text . '\');" ';
				$this->_MonatsArray[$i][33] .= 'href="?action=add_rapport&timestamp=';
				$this->_MonatsArray[$i][33] .= $this->_MonatsArray[$i][0].$this->_modal_str.'" ';
				$this->_MonatsArray[$i][33] .= '>';
				$this->_MonatsArray[$i][33] .= '<img src="images/icons/application_edit.png" border="0">';
				$this->_MonatsArray[$i][33] .= '';
				$this->_MonatsArray[$i][33] .= '</a>';
				$this->_MonatsArray[$i][37] = '';	
				$this->_MonatsArray[$i][37] .= '<div ';
				$this->_MonatsArray[$i][37] .= 'onMouseout="RapportMouseout();" ';
				$this->_MonatsArray[$i][37] .= 'onMouseover="RapportMouseover(event, \'' . $text . '\');" ';
				$this->_MonatsArray[$i][37] .= '>';
				$this->_MonatsArray[$i][37] .= '<img src="images/icons/application_edit.png" border="0">';
				$this->_MonatsArray[$i][37] .= '';
				$this->_MonatsArray[$i][37] .= '</div>';
			}else{
				$this->_MonatsArray[$i][33] = "<a title='Rapport hinzuf&uuml;gen' href='?action=add_rapport&timestamp=".$this->_MonatsArray[$i][0].$this->_modal_str."'><img src='images/icons/application_add.png' border=0></a> ";
			}
			$this->_MonatsArray[$i][35] = "";
			if($this->_setautopause){
				$_temptext = "Die automatische Pause von " . $this->_MonatsArray[$i][38] . "h wurde abgerechnet in den Stempelzeiten : ". $this->_setautopause;				
				$this->_MonatsArray[$i][35] = "<img title='$_temptext' src='images/icons/clock_pause.png' border=0>";
				$this->_setautopause ="";
			}
			if($this->_zeitzuschlag){
				$this->_MonatsArray[$i][36] = "<img title='".$this->_zeitzuschlag."' src='images/icons/clock_red.png' border=0>";
				$this->_zeitzuschlag="";
			}else{
				$this->_MonatsArray[$i][36] = "";
			}
			//-------------------------------------------------------------------------
			// Summen berechnen
			//-------------------------------------------------------------------------
			$this->_SummeSollProMonat 		= $this->_SummeSollProMonat	+ $this->_MonatsArray[$i][8];
			$this->_SummeWorkProMonat 	= $this->_SummeWorkProMonat	+ $this->_MonatsArray[$i][13];
			$this->_SummeAbsenzProMonat 	= $this->_SummeAbsenzProMonat	+ $this->_MonatsArray[$i][18];
			$this->_SummeSaldoProMonat 	= $this->_SummeSaldoProMonat + $this->_MonatsArray[$i][20];
			$this->_SummeStempelzeiten 		= $this->_SummeStempelzeiten + $this->_MonatsArray[$i][11];
						
			$this->_SummeWorkProMonat 	= round($this->_SummeWorkProMonat,2);
			$this->_SummeAbsenzProMonat 	= round($this->_SummeAbsenzProMonat,2);
			$this->_SummeSaldoProMonat 	= round($this->_SummeSaldoProMonat,2);
			$this->_SummeStempelzeiten 		= round($this->_SummeStempelzeiten,2);
			
			//-------------------------------------------------------------------------
			// Summen der Absenzen berechnen
			//-------------------------------------------------------------------------
			// Array mit Daten - Summen in der Spalte 3 
			$a=0;
			foreach($this->_absenz->_calc as $zeile){
				if($this->_MonatsArray[$i][14]==$zeile[1]){
					$this->_absenz->_calc[$a][3] = $this->_absenz->_calc[$a][3] + $this->_MonatsArray[$i][15];
				}	
				$a++;
			}
			//-------------------------------------------------------------alte Abwesenheitsberechnungen
			if($this->_MonatsArray[$i][14]=="F") $this->_SummeFerien = $this->_SummeFerien + $this->_MonatsArray[$i][15];
			if($this->_MonatsArray[$i][14]=="K") $this->_SummeKrankheit = $this->_SummeKrankheit+ $this->_MonatsArray[$i][15];
			if($this->_MonatsArray[$i][14]=="U") $this->_SummeUnfall = $this->_SummeUnfall+ $this->_MonatsArray[$i][15];
			if($this->_MonatsArray[$i][14]=="M") $this->_SummeMilitaer = $this->_SummeMilitaer+ $this->_MonatsArray[$i][15];
			if($this->_MonatsArray[$i][14]=="I") $this->_SummeIntern = $this->_SummeIntern+ $this->_MonatsArray[$i][15];
			if($this->_MonatsArray[$i][14]=="W") $this->_SummeWeiterbildung = $this->_SummeWeiterbildung+ $this->_MonatsArray[$i][15];
			if($this->_MonatsArray[$i][14]=="E") $this->_SummeExtern = $this->_SummeExtern+ $this->_MonatsArray[$i][15];
			//------------------------------------------------------------------alte Abwesenheitsberechnungen	
		}	
	}
	private function get_absenz($_Day){
		foreach($this->_absenz->_array as $string){
			if($string[0] == $_Day){
				return $string;
			}
		}
	}	
	private function get_absenz_text($_Day){
		$_file = $this->_file . "/A" . date("Y", $_Day);
		if(file_exists($_file)){
			$_absenzliste = file($_file);
			foreach($_absenzliste as $string){
				$string = explode(";", $string);
				if($string[0] == $_Day){					
					return $string;
				}
			}
		}
	}
	private function get_rapport($_Day){
		$_txt = " ";
		$_file = $this->_pfad."Rapport/" . date("Y.m.d", $_Day);
		if(file_exists($_file)){
			$_txt = file_get_contents($_file);
		}
		return $_txt;
	}
	private function is_feiertag($_Day, $i){
		$z=0;
		foreach(array_values($this->_feiertage) as $feiertag){
			if($feiertag == $_Day){
				return $z;
			}
			$z++;
		}
		return -1;
	}
	function get_wochentage($i){
		return $this->_wochentage[$i];
	}
	function get_feiertage(){
		return $this->_feiertage;
	}
	private function get_time($i){
		$_stempelzeit = $this->_MonatsArray[$i][10];
		$_anzeige = array();
		$_h_pro_day	= 0;
		$_anz = 1;
		$_debug	= 0;
		$_debug_berechnung = 0;
		// Falls eine ungerade anzahl, nur die geraden berechnen
		// Debug bei Berechnungen-----------------------------------------
		if($_debug){
			echo "<hr>";
			echo "Zeit berechnen : ";
			print_r ($this->_MonatsArray[$i][10]);
			echo "<br>". $_stempelzeit [0] ;
			echo "<br>". $_stempelzeit [1] ;
		}
		// -------------------------------------------------------------------------
		$_count = count($_stempelzeit);
		for($h=0; $h<$_count; $h=$h+2){
			if($_debug_berechnung){
					echo "<br>--------------------------------------------------------------------------------";
					echo "<br>Tag:  " . $i;
				}
			$_anzeige[$h]	= date("H:i",$_stempelzeit[$h]);
			//Anzeige bei 59 Min und 59 Sek runden
			if(date("i",$_stempelzeit[$h])=='59' and date("s",$_stempelzeit[$h])=='59'){
				$tmp = date("H",$_stempelzeit[$h])+1;
				$tmp .= ":00";
				$_anzeige[$h]	= $tmp;
				if($_debug_berechnung) echo ".....Zahl runden<br>";
			}
			// falls eine Zeit fehlt ist die nächste zeit 0 und kann nicht berechnet werden
			if(isset($_stempelzeit[$h+1]) and $_stempelzeit[$h+1]<>0){
				if($_debug) echo "<hr>";
				$_anzeige[$h+1]	= date("H:i",$_stempelzeit[$h+1]);
				//Anzeige bei 59 Min und 59 Sek runden
				if(date("i",$_stempelzeit[$h+1])=='59' and date("s",$_stempelzeit[$h+1])=='59'){
					$tmp = date("H",$_stempelzeit[$h+1])+1;
					$tmp .= ":00";
					$_anzeige[$h+1]	= $tmp;
					if($_debug_berechnung ) echo ".....59 Sekunden<br>";
				}
				// Stunden die gearbeitet wurden (mal 100 für die Dezimalumrechnung)
				$_start = date("H",$_stempelzeit[$h]);
				$_ende = date("H",$_stempelzeit[$h+1]);
				//------------------------------------------------------------------------------------------
				// Korrektur 28.12.2016 - Zeitberechnung - kleine Fehlerkorrektur im hunderstel - Bereich
				//------------------------------------------------------------------------------------------
				$work_h = date("H",$_stempelzeit[$h+1]) - date("H",$_stempelzeit[$h]);
				// Resultat könnte kleiner als 0 sein, wird weiter unten korrigiert
				$work_m = date("i",$_stempelzeit[$h+1]) - date("i",$_stempelzeit[$h]);
				// Resultat könnte kleiner als 0 sein, wird weiter unten korrigiert
				$work_s = date("s",$_stempelzeit[$h+1]) - date("s",$_stempelzeit[$h]);
				// falls Berechung im Minus ist, eine Minute abziehen und 60 Sekunden hinzu zählen
				if($work_s<0){
					$work_m = $work_m-1;
					$work_s = $work_s + 60;
				}
				// falls Berechung im Minus ist, eine Stunde abziehen und 60 Minuten hinzu zählen
				if($work_m<0){
					$work_h = $work_h-1;
					$work_m = $work_m +60;
				}
				if($_debug_berechnung ){
					echo "<br>Arbeitszeit in Stunden, Minuten, Sekunden: " . $work_h . ":" . $work_m . ":" . $work_s ; 
				}
				// Zeiten nun in Dezimal umrechnen
				$work_s = $work_s/60;
				$work_m = $work_m/60*100;
				$work_m = ($work_m + $work_s) / 100;
				$work_h = $work_h + $work_m;
				if($_debug_berechnung ){
					echo "<br>Arbeitszeit in Dezimal grundet: " . round($work_h,2) ; 
				}
				$_zeit  = round($work_h,2) ;
				//------------------------------------------------------------------------------------
				// Autopause berechnen bis V 0.9.007
				//------------------------------------------------------------------------------------
				if($this->_arbeitszeit > 0 && $_zeit >= $this->_arbeitszeit){
					$_zeit = ($_zeit - $this->_autopause);	
					if($this->_setautopause){
						$this->_setautopause = $this->_setautopause. " & ". $_anz;
					}else{
						$this->_setautopause = $this->_setautopause . $_anz;	
					}
					$_anz++;	
				}
				$time = $this->_arbeitszeit ;
				//------------------------------------------------------------------------------------
				// Autopause berechnen ab V 0.9.007 : alte Version wird automatisch inaktiv
				//------------------------------------------------------------------------------------
				if($_zeit > 0){
					$vergleichszeit= number_format($_zeit);
					$vergleichszeit = $_zeit;
					if(class_exists('pausen')){
						$time = pausen::check($vergleichszeit);
						if($time[0]>0){
							// für die Darstellung im Monatsarray Spalte 38, wie viel Zeit abgezogen wurde
							$this->_MonatsArray[$i][38] = $this->_MonatsArray[$i][38] + $time[0];
							if($this->_autopause==""){
								$this->_autopause = $time[0];
							}else{
								$this->_autopause = $this->_autopause + $time[0];
							}
							$this->_autopause .= ' Stunden';
							$_zeit = ($_zeit - $time[0]);
							if($this->_setautopause){
								$this->_setautopause = $this->_setautopause. " & Zeitenpaar ". $_anz;
							}else{
								$this->_setautopause = $this->_setautopause . " Zeitenpaar " . $_anz;	
							}
							$this->_setautopause = $this->_setautopause . " Abzug nach id : " . $time[1];
							$_anz++;
						}
					} 
				}
				//------------------------------------------------------------------------------------
				// Zeitzuschlag berechnen
				//------------------------------------------------------------------------------------
				$this->_file = $this->_pfad . "/userdaten.txt";
				$_userdaten = file($this->_file);
				$_tmptag = 9+$this->_MonatsArray[$i][2];
				$this->_zeitzuschlag = "";
				$tmp = explode(";", $_userdaten[$_tmptag]);
				$Start = $tmp[0];
				$Ende = $tmp[1];
				$zuschlag = ($tmp[2]-100)/100;	
				$Stempel1 = (date("H",$_stempelzeit[$h])*100 + date("i",$_stempelzeit[$h])*100/60)/100;
				$Stempel2 = (date("H",$_stempelzeit[$h+1])*100 + date("i",$_stempelzeit[$h+1])*100/60)/100;	
				if($_debug) echo " Zeiten : " . $Stempel1 . " - ". $Stempel2;
				if($_debug) echo " Stempel : " . $Stempel1 . " - ". $Stempel2 . " / Zuschlag von ". $Start . " - ". $Ende;
				$_tmptime = 0;
				if($Stempel1 < $Start && $Stempel2 >= $Start && $Stempel2 < $Ende){
					//$this->_zeitzuschlag = "Logik 1 : ". ($Stempel2-$Start) ." Std.";
					$_tmptime = round(($Stempel2-$Start)* $zuschlag,2);
					//$this->_zeitzuschlag = $this->_zeitzuschlag."-" . $_tmptime . "-";
					$this->_zeitzuschlag = "Zuschlag von ". $Start . " - ". $Ende ." Uhr, ".trim($tmp[2])."% / Berechnet : ".$_tmptime." Std.";
					if($_debug) echo " - Logik 1:".$_tmptime;
				}elseif($Stempel1 >= $Start && $Stempel2 <= $Ende){
					//$this->_zeitzuschlag = "Logik 2 : " . $_zeit ." Std.";
					$_tmptime = round(($_zeit)*$zuschlag,2);
					//$this->_zeitzuschlag = $this->_zeitzuschlag."-" . $_tmptime . "-";
					$this->_zeitzuschlag = "Zuschlag von ". $Start . " - ". $Ende ." Uhr, ".trim($tmp[2])."% / Berechnet : ".$_tmptime." Std.";
					if($_debug) echo " - Logik 2: ".$_zeit." - ".$_tmptime;
				}elseif($Stempel1 <= $Start && $Stempel2 >= $Ende){
					//$this->_zeitzuschlag = "Logik 3 : " . ($Ende - $Start) ." Std.";
					$_tmptime = round(($Ende - $Start)* $zuschlag,2);
					//$this->_zeitzuschlag = $this->_zeitzuschlag."-" . $_tmptime . "-";
					$this->_zeitzuschlag = "Zuschlag von ". $Start . " - ". $Ende ." Uhr, ".trim($tmp[2])."% / Berechnet : ".$_tmptime." Std.";
					if($_debug) echo " - Logik 3:".$_tmptime;
				}elseif($Stempel1 > $Start && $Stempel1 <= $Ende && $Stempel2 > $Ende){
					//$this->_zeitzuschlag = "Logik 4 : " . ($Ende -$Stempel1) . " Std.";
					$_tmptime = round(($Ende-$Stempel1)* $zuschlag,2);
					//$this->_zeitzuschlag = $this->_zeitzuschlag."-" . $_tmptime . "-";
					$this->_zeitzuschlag = "Zuschlag von ". $Start . " - ". $Ende ." Uhr, ".trim($tmp[2])."% / Berechnet : ".$_tmptime." Std.";
					if($_debug) echo " - Logik 4:".$_tmptime;
				}
				if($_debug) echo "<hr>";
				$_h_pro_day = $_h_pro_day + $_zeit+ $_tmptime;	
			}
		}	
		$this->_MonatsArray[$i][12] = $_anzeige;
		//Debug bei Berechnungen-----------------------------------------
		if($_debug){
			echo "<hr>";
		}
		//-------------------------------------------------------------------------
		return $_h_pro_day;
	}
	private function get_timestamps($_Day){
		$_stempelzeit = array();
		$_saldo = array();
		for($g=0; $g<count($this->_timeTable); $g++){
			//Datenüberprüfung und Bereinigung bei Leerzeilen
			$this->_timeTable[$g] = trim($this->_timeTable[$g]);
			$this->_timeTable[$g] = str_replace("\r", "", $this->_timeTable[$g]);
			$this->_timeTable[$g] = str_replace("\n", "", $this->_timeTable[$g]);
			if($this->_timeTable[$g]){
				if(date("d.m.Y",$this->_timeTable[$g])== date("d.m.Y",$_Day)){
					$_stempelzeit[] = $this->_timeTable[$g];
				}	
			}else{
				$_datum = date("d.m.Y",time());
				$_uhrzeit = date("H:i",time());
				$_datetime = $_datum." - ".$_uhrzeit;
				$_debug = new time_filehandle("./debug/","time.txt",";");
				$_debug->insert_line("Time;" . $_datetime . ";Fehler in class_month;304;" .$this->_file.";Leerzeile entdeckt");
			}
		}	
		return $_stempelzeit;
	}
	private function set_timetable_daten($ordnerpfad, $jahr, $monat){
		$this->_file = "./Data/".$ordnerpfad."/Timetable/" . $jahr . "." . $monat;
		if(!file_exists($this->_file)){
			// echo "keine Daten vorhanden";
			// TODO : if umschreiben und testen 
		}else{
			$this->_timeTable = file($this->_file);
			sort($this->_timeTable);
		}
	}
	private function set_letzterTag($tag){
		$this->_letzterTag = $tag;
	}
	private function set_Monatsueberschrift(){
		$this->_MonatsArray[0][0] = "(0)<br>Timestamp";		// Timestamp vom Tag
		$this->_MonatsArray[0][1] = "(1)<br>Datum";			// Datum
		$this->_MonatsArray[0][2] = "(2)<br>Nr-Tag";			// Wochentagnummer
		$this->_MonatsArray[0][3] = "(3)<br>Tag";				// Wochentag
		$this->_MonatsArray[0][4] = "(4)<br>Arbeitstag";		// User Arbeitstag
		$this->_MonatsArray[0][5] = "(5)<br>Feiertag";			// User Feiertag
		$this->_MonatsArray[0][6] = "(6)<br>Feiertagname"; 		// Name des Feiertages
		$this->_MonatsArray[0][7] = "(7)<br>Arbeitstag";		// Arbeitstag 0 oder 1
		$this->_MonatsArray[0][8] = "(8)<br>Soll";				// Sollarbeitszeit pro Tag

		$this->_MonatsArray[0][9] = " ";
		$this->_MonatsArray[0][10] = "(10)<br>timestamps";		// Timestamps
		$this->_MonatsArray[0][11] = "(11)<br>anzahl times";	// bein ungerade fehlt eine Zeit
		$this->_MonatsArray[0][12] = "(12)<br>Stempelzeiten";	// Stempelzeiten in einem Array
		$this->_MonatsArray[0][13] = "(13)<br>Arbeitszeit";		// gearbeitete Zeit anhand der Stempelzeiten

		$this->_MonatsArray[0][14] = "(14)<br>Abs. KZ";		// Kurzbezeichnung der Abwensenheit
		$this->_MonatsArray[0][15] = "(15)<br>Abs. Tag";		// Absenz in Tagen (z. 0.5 Tag)
		$this->_MonatsArray[0][16] = "(16)<br>Abs. Beschr.";	// Absenz Beschreibung
		$this->_MonatsArray[0][17] = "(17)<br>Abs. %";		// Prozentualer Anteil, der die Firma bei einer Absenz übernimmt
		$this->_MonatsArray[0][18] = "(18)<br>Abs. h";			// Absenz in Stunden

		$this->_MonatsArray[0][19] = "(19)<br>";				//
		$this->_MonatsArray[0][20] = "(20)<br>Saldo";			// Saldo am Tag
		$this->_MonatsArray[0][21] = "(21)<br>Summe";		// Summe bis zum vorherigen Tag

		$this->_MonatsArray[0][30] = "(30)<br>class";			// Tabellen - Hintergrundfarbe
		$this->_MonatsArray[0][31] = "(31)<br>absenz";		// Absenzt einfügen
		$this->_MonatsArray[0][32] = "(32)<br>info";			// Absenz Info
		$this->_MonatsArray[0][33] = "(33)<br>rapport";			// Rapport - Hyperlink
		$this->_MonatsArray[0][34] = "(34)<br>text";			// Rapport - text
		$this->_MonatsArray[0][35] = "(35)<br>ap";			// automatische Pause
		$this->_MonatsArray[0][36] = "(36)<br>az";				// Arbeitszeit spezial Vergütung
		$this->_MonatsArray[0][33] = "(37)<br>rapport popup";	// Rapport - Nur popup
	}
	public function check_autopause($zeit,$pause){
		for($i=1; $i<=$this->_letzterTag; $i++){
			// TODO : function veraltet - > bereinigen
		}
	}
}