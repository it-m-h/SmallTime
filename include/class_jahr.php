<?php

/*******************************************************************************
 * Jahresberechnung
/*******************************************************************************
 * Version 0.9.205
 * Author:  IT-Master
 * www.it-master.ch / info@it-master.ch
 * Copyright (c), IT-Master, All rights reserved
 *******************************************************************************/
#[AllowDynamicProperties]
class time_jahr
{
	public $_jahr 				= NULL;		// Startjahr des Users
	public $_timestamp			= NULL;		// welches jahr wurde gewählt, bzw Monat wurde gewählt
	public $_summe_t			= NULL;		// Summe seit Beginn inkl. Übertrag
	public $_modell 			= NULL;		// Zeitberechnungsmodell (0=normal, alle kumuliertd, 1 = Jährlich, 2 Monatlich) (datei ./Data/user/userdaten.txt zeile 16 erweitern mit 0,1,2)
	public $_summe_F			= NULL;		// Feriensumme
	public $_summe_vorholzeit	= NULL;
	public $_CalcToTimestamp	= TRUE;
	public $_saldo_t			= NULL;		// Zeitsaldo
	public $_saldo_F			= NULL;		// Feriensaldo
	public $_saldo_Fv			= NULL;		// Vergangene Ferien
	public $_saldo_Fz			= NULL;		// Eingetragene Ferien in der Zukunft
	public $_summe_Fv			= NULL;
	public $_summe_Fz			= NULL;
	public $_summe_Ft			= NULL;
	public $_summe_Fgeplant		= NULL;
	public $_arr_ausz			= NULL;		// Auszahlungen als array (Monat, Jahr, Anzahl)
	public $_tot_ausz			= NULL;		// Auszahlungen summe
	public $_ordnerpfad			= NULL;		// Pfad zu den Daten
	public $_startjahr 			= NULL;		// Beginn der Zeitrechnung in den User - Einstellungen
	public $_startmonat			= NULL;		// Beginn der Zeitrechnung in den User - Einstellungen
	public $_endzeit			= NULL;
	public $_array				= NULL;		// Array des Jahres
	public $_data				= NULL;		// Array der Daten	
	public $_Ferien_pro_Jahr	= NULL;
	public $_Stunden_uebertrag	= NULL;
	public $_Ferienguthaben_uebertrag	= NULL;
	//public $Ferien_pro_Jahr; 		
	public $_Vorholzeit_pro_Jahr		= NULL;

	function __construct($ordnerpfad, $jahr, $startjahr, $Stunden_uebertrag, $Ferienguthaben_uebertrag, $Ferien_pro_Jahr, $Vorholzeit_pro_Jahr, $modell, $_timestamp, $_endzeit = 0)
	{
		$this->_ordnerpfad 				= $ordnerpfad;
		$this->_timestamp 				= $_timestamp;
		// Jahr auf aktuell setzten falls kein Endjahr angegeben ist
		if ($jahr == 0) $this->_jahr 		= date("Y", time());
		$startjahr = trim($startjahr);
		$this->_startjahr 				= date("Y", $startjahr);
		$this->_startmonat 				= date("n", $startjahr);
		$this->_endzeit 				= intval($_endzeit);
		$this->_Stunden_uebertrag 		= $Stunden_uebertrag;
		$this->_Ferienguthaben_uebertrag = $Ferienguthaben_uebertrag;
		$this->_Ferien_pro_Jahr 		= $Ferien_pro_Jahr;
		$this->_Vorholzeit_pro_Jahr 	= $Vorholzeit_pro_Jahr;
		$this->_modell 					= $modell;
		if (isset($_SESSION['calc'])) $this->_CalcToTimestamp = $_SESSION['calc'];
		$this->calc_feriensumme();
		// ---------------------------------------------------------------------------------------
		// Falls jeden Monat die Überzeit auf 0 gestellt wird:
		if ($this->_modell == 2) {
			$this->calc_month();
			// ---------------------------------------------------------------------------------------
			// Falls jedes Jahr die Überzeit auf 0 gestellt wird:
		} elseif ($this->_modell == 1) {
			$this->calc_auszahlungen_year();
			$this->calc_year();
			// ---------------------------------------------------------------------------------------
			// kumuliert
		} else {
			$this->calc_auszahlungen();
			$this->calc_kumuliert();
		}
		$this->_saldo_t = round($this->_saldo_t, 2);
		//$this->calc_feriensumme();
		$this->savetotal();
	}
	function get_auszahlung($monat, $jahr)
	{
		$anz = 0;
		if (time_user::is_month_after_end($jahr, $monat, $this->_endzeit)) {
			return 0;
		}
		$referenceYear = $this->get_reference_year();
		$referenceMonth = $this->get_reference_month();
		if(is_array($this->_arr_ausz)){
			for ($i = 0; $i < count($this->_arr_ausz); $i++) {
				if ($this->_CalcToTimestamp && ($jahr < $referenceYear || ($jahr == $referenceYear && $monat <= $referenceMonth))) {
					if (trim($this->_arr_ausz[$i][0]) == trim($monat) && trim($this->_arr_ausz[$i][1]) == trim($jahr)) {
						$anz =  $this->get_timetable_value($this->_arr_ausz[$i][2] ?? 0);
					}
				} elseif (!$this->_CalcToTimestamp) {
					if (trim($this->_arr_ausz[$i][0]) == trim($monat) && trim($this->_arr_ausz[$i][1]) == trim($jahr)) {
						$anz =  $this->get_timetable_value($this->_arr_ausz[$i][2] ?? 0);
					}
				}
			}
		}elseif(isset($this->_arr_ausz)){
			$anz =  $this->get_timetable_value($this->_arr_ausz);
		}else{
			$anz =  0;
		}
		return $anz;
	}
	function calc_auszahlungen_year()
	{
		// Auszahlungen berechnen (Datei ./Data/username/Timetable/auszahlungen : Monat;Jahr;Anzahl)
		$file = "./Data/" . $_SESSION['datenpfad'] . "/Timetable/auszahlungen";
		$referenceYear = $this->get_reference_year();
		$referenceMonth = $this->get_reference_month();
		if (file_exists($file)) {
			$this->_arr_ausz = file($file);
			for ($i = 0; $i < count($this->_arr_ausz); $i++) {
				$this->_arr_ausz[$i] = explode(";", $this->_arr_ausz[$i]);
				$monat = intval(trim($this->_arr_ausz[$i][0] ?? 0));
				$jahr = intval(trim($this->_arr_ausz[$i][1] ?? 0));
				if (!$this->is_auszahlung_allowed($monat, $jahr)) {
					continue;
				}
				$auszahlung = $this->get_timetable_value($this->_arr_ausz[$i][2] ?? 0);
				if ($jahr != $referenceYear) {
					continue;
				}
				if ($this->_CalcToTimestamp && $monat > $referenceMonth) {
					continue;
				}
				$this->_tot_ausz += $auszahlung;
			}
		}
	}
	function calc_auszahlungen()
	{
		// Auszahlungen berechnen (Datei ./Data/username/Timetable/auszahlungen : Monat;Jahr;Anzahl)
		$file = "./Data/" . $_SESSION['datenpfad'] . "/Timetable/auszahlungen";
		if (file_exists($file)) {
			$this->_arr_ausz = file($file);
			for ($i = 0; $i < count($this->_arr_ausz); $i++) {
				$this->_arr_ausz[$i] = explode(";", $this->_arr_ausz[$i]);
				if (!$this->is_auszahlung_allowed(intval(trim($this->_arr_ausz[$i][0] ?? 0)), intval(trim($this->_arr_ausz[$i][1] ?? 0)))) {
					continue;
				}
				$auszahlung = $this->get_timetable_value($this->_arr_ausz[$i][2] ?? 0);
				// nur bis zum aktuellen Datum berechnen = $htis->_CalcToTimestamp
				if (isset($this->_arr_ausz[$i][0]) && isset($this->_arr_ausz[$i][1])) {
					$tmpaustime = mktime(1, 1, 1, $this->_arr_ausz[$i][0], 1, $this->_arr_ausz[$i][1]);
					$now = (int)$this->_timestamp;
					$_aj 		= date("Y", $this->_timestamp);
					$_am 	= date("m", $this->_timestamp);
					// wenn Auszahlungsjahr kleiner, alle Einträge
					if ($this->_arr_ausz[$i][1] < $_aj) {
						$this->_tot_ausz += $auszahlung;
						//wenn Auszahlungsjahr gleich, dann nur bis zum Monat	
					} elseif ($this->_arr_ausz[$i][1] == $_aj) {
						if ($this->_arr_ausz[$i][0] <= $_am) {
							$this->_tot_ausz += $auszahlung;
						}
					}
				}
			}
		}
	}
	function calc_month()
	{
		$i = $this->get_reference_year();
		$z = $this->get_reference_month() - 1;
		$file = "./Data/" . $this->_ordnerpfad . "/Timetable/" . $i;
		if (!file_exists($file)) {
			$fp = fopen($file, "w");
			fclose($fp);
		}
		$this->_data[$i] = file($file);
		$z = 0;
		foreach ($this->_data[$i] as $zeile) {
			if (strpos($zeile, ";") !== false) {
				$this->_data[$i][$z] = explode(";", $zeile);
			} else {
				//$this->_data[$i][$z] = $this->_data[$i][$z];
				$this->_data[$i][$z][0] = $zeile;
			}
			$z++;
		}
		// wenn ; ist in $this->_data[$i][$z]
		if (isset($this->_data[$i][$z]) && strpos($this->_data[$i][$z], ";") !== false){
			$this->_data[$i][$z] = explode(";", $this->_data[$i][$z]);
		}else{ 
			if(isset($this->_data[$i][$z])){
				$this->_data[$i][$z][0] = $this->_data[$i][$z];
			}else{
				$this->_data[$i][$z][0] = 0;
			}
			//$this->_data[$i][$z] = $this->_data[$i][$z];
			
		}
		//$this->_data[$i][$z][0] = 0;

		
		$this->_saldo_t = $this->get_timetable_value($this->_data[$i][$z][0] ?? 0);
	}

	function get_timetable_value($value)
	{
		if (is_array($value)) {
			$value = $value[0] ?? 0;
		}

		$value = trim((string)$value);
		if ($value === '') {
			return 0.0;
		}

		return floatval(str_replace(',', '.', $value));
	}
	private function get_reference_timestamp()
	{
		if ($this->_endzeit > 0 && intval($this->_timestamp) > $this->_endzeit) {
			return $this->_endzeit;
		}

		return intval($this->_timestamp);
	}
	private function get_reference_year()
	{
		return intval(date("Y", $this->get_reference_timestamp()));
	}
	private function get_reference_month()
	{
		return intval(date("n", $this->get_reference_timestamp()));
	}
	private function get_calc_month_limit($year)
	{
		$year = intval($year);
		$limit = 12;

		if ($this->_CalcToTimestamp) {
			$selectedYear = intval(date("Y", intval($this->_timestamp)));
			$selectedMonth = intval(date("n", intval($this->_timestamp)));
			if ($year > $selectedYear) {
				return 0;
			}
			if ($year === $selectedYear) {
				$limit = min($limit, $selectedMonth);
			}
		}
		if ($this->_endzeit > 0) {
			$endYear = intval(date("Y", $this->_endzeit));
			$endMonth = intval(date("n", $this->_endzeit));
			if ($year > $endYear) {
				return 0;
			}
			if ($year === $endYear) {
				$limit = min($limit, $endMonth);
			}
		}

		return $limit;
	}
	private function get_year_active_months($year, $respectCalcLimit = false)
	{
		$starttime = mktime(0, 0, 0, intval($this->_startmonat), 1, intval($this->_startjahr));
		$endtime = $this->_endzeit;

		if ($respectCalcLimit) {
			$calcLimit = $this->get_calc_month_limit($year);
			if ($calcLimit <= 0) {
				return 0;
			}
			$calcEnd = mktime(23, 59, 59, $calcLimit + 1, 0, intval($year));
			if ($endtime > 0) {
				$endtime = min($endtime, $calcEnd);
			} else {
				$endtime = $calcEnd;
			}
		}

		return time_user::get_year_active_months($year, $starttime, $endtime);
	}
	private function is_auszahlung_allowed($monat, $jahr)
	{
		return !time_user::is_month_after_end($jahr, $monat, $this->_endzeit);
	}

	function calc_year()
	{
		$i = $this->get_reference_year();
		$file = "./Data/" . $this->_ordnerpfad . "/Timetable/" . $i;
		if (!file_exists($file)) {
			$fp = fopen($file, "w");
			fclose($fp);
		}
		$this->_data[$i] = file($file);
		$z = 0;
		$maxMonth = $this->get_calc_month_limit($i);
		foreach ($this->_data[$i] as $zeile) {
			$this->_data[$i][$z] = explode(";", $zeile);
			$monatssumme = $this->get_timetable_value($this->_data[$i][$z][0] ?? 0);
			if (($z + 1) <= $maxMonth) {
				$this->_summe_t = $this->_summe_t + $monatssumme;
			}
			$z++;
		}
		// jährliche Vorholzeit - Summe hinzurechnen
		$vorholzeit = floatval($this->_Vorholzeit_pro_Jahr);
		if ($this->_endzeit > 0) {
			$vorholzeitMonate = $this->get_year_active_months($i, true);
			$vorholzeit = round(floatval($this->_Vorholzeit_pro_Jahr) / 12 * floatval($vorholzeitMonate), 2);
		}
		$this->_saldo_t = $this->_summe_t - $vorholzeit - $this->_tot_ausz;
		// im Start-Jahr Übertrag hinzufügen
		if ($this->_startjahr == $i) {
			$this->_saldo_t = $this->_saldo_t  + $this->_Stunden_uebertrag;
		}
	}
	function calc_kumuliert()
	{
		// Schleife - Startjahr bis Heute
		$_year_start = $this->_startjahr;
		$_year_heute = $this->_jahr;
		$_year_wahl = date('Y', $this->_timestamp);
		$_month_wahl = date('m', $this->_timestamp);
		$this->_summe_vorholzeit = 0;
		for ($i = $this->_startjahr; $i <= $_year_wahl; $i++) {
			$this->set_ueberschriften($i);
			$file = "./Data/" . $this->_ordnerpfad . "/Timetable/" . $i;
			// Falls die Datei nicht existiert eine leere Datei erstellen
			if (!file_exists($file)) {
				$fp = fopen($file, "w");
				fclose($fp);
			}
			$this->_data[$i] = file($file);
			$z = 0;
			$maxMonth = $this->get_calc_month_limit($i);
			// Schleife - Monats Daten in der Jahres Datei 
			foreach ($this->_data[$i] as $zeile) {
				$this->_data[$i][$z] = explode(";", $zeile);
				$monatssumme = $this->get_timetable_value($this->_data[$i][$z][0] ?? 0);
				if (($z + 1) <= $maxMonth) {
					$this->_summe_t = $this->_summe_t + $monatssumme;
				}
				$z++;
				$temp = $this->_summe_t;
			}
			// Jährliche Vorholzeit - Summe
			$_monate = $this->get_year_active_months($i, true);
			if ($_monate > 0) {
				$tmp = round(floatval($this->_Vorholzeit_pro_Jahr) / 12 * floatval($_monate), 2);
				$this->_summe_vorholzeit += $tmp;
			}
		}
		$this->_saldo_t 	= 	0;
		$this->_saldo_t 	= 	$this->_saldo_t + $this->_summe_t;
		$this->_saldo_t 	=	$this->_saldo_t - $this->_summe_vorholzeit;
		$this->_saldo_t 	=	$this->_saldo_t + $this->_Stunden_uebertrag;
		$this->_saldo_t 	=	$this->_saldo_t - $this->_tot_ausz;
	}
	function calc_feriensumme()
	{
		// Startjahr des Mitarbeiters
		$_year_start = $this->_startjahr;
		$_month_start = $this->_startmonat;
		// ausgewähltes Jahr und Monat für die Berechnung
		$_year_wahl = date('Y', $this->_timestamp);
		$_month_wahl = date('m', $this->_timestamp);
		// Jahr und Monat von aktuellen Datum
		$_year_heute = $this->_jahr;
		$_month_heute = date('m', time());
		//Settings - Einstellungen für Ferienberechnung
		$tmpsettings = new time_settings();
		// wurde ein Monat in der vergangenheit gewählt nur bis dahin berechnen
		// falls es der aktuelle Monat ist bis zum heutigen Datum berechnen
		if ($_year_heute == $_year_wahl and $_month_heute == $_month_wahl) {
			$timestampvergleich = time();
		} else {
			// letzter Tag im Monat, darum 1. vom kommenden Monat berechnen minus einer sekunde...
			$_jahr = $_year_wahl;
			$_monat = $_month_wahl + 1;
			$_tag = 1;
			$_stunde = 0;
			$_minute = 0;
			$_sekunde = 0;
			$_timestamp = mktime($_stunde, $_minute, $_sekunde, $_monat, $_tag, $_jahr);
			$timestampvergleich = $_timestamp - 1;
		}
		// Ferienberechnung Ferien vorher und zukunft
		$this->_summe_Fv = 0; // berechnete vergangene Ferien
		$this->_summe_Fz = 0; // berechnete zukünftige Ferien
		$this->_summe_Ft	= 0; // total berechnete veingetragene Ferien
		// Ferien die in den Monatsberechnungen eingetragen sind: bezogene Ferien- auch die in der Zukunft eingetragene	
		// Einträge in der Datei : ./Data/Username/Timestamp/Jahr
		for ($j = $_year_start; $j <= $_year_wahl; $j++) {
			$file = "./Data/" . $this->_ordnerpfad . "/Timetable/" . $j;
			if (file_exists($file)) {
				$_arr = file($file);
				for ($i = 0; $i < count($_arr); $i++) {
					if (time_user::is_month_after_end($j, $i + 1, $this->_endzeit)) {
						continue;
					}
					$_arr[$i] = explode(";", $_arr[$i]);
					// settings == 1 wenn kommende Ferien nicht mitberechnet werden sollen
					if ($tmpsettings->_array[27][1] == 1) {
						// Ferien in der Vergangenheit von gewählten Jahr
						if ($_year_wahl == $j) {
							//  Ferien in der Vergangenheit von gewählten Monat
							if ($_month_wahl <= $i) {
								$this->_summe_Fz = floatval($this->_summe_Fz) + floatval($_arr[$i][1]);
							} else {
								$this->_summe_Fv = floatval($this->_summe_Fv) + floatval($_arr[$i][1]);
							}
						} else {
							$this->_summe_Fv = floatval($this->_summe_Fv) + floatval($_arr[$i][1]);
						}
					} else {
						$this->_summe_Fv = floatval($this->_summe_Fv) + floatval($_arr[$i][1]);
					}
					//Summe alle Eingtetragenen und Berechneten Ferien
					$this->_summe_Ft = floatval($this->_summe_Ft) + floatval($_arr[$i][1]);
				}
			}
		}
		$this->_summe_Fgeplant = 0;
		// eingetragene Ferien noch nicht berechnet in den Monatssummen:
		for ($i = $_year_start; $i <= $_year_wahl; $i++) {
			$tmp_absenzen[$i] = new time_absenz($this->_ordnerpfad, $i);

			// Falls keine Einträge in den Ferien oder kein File vorhanden
			if (is_array($tmp_absenzen[$i]->_array) && count($tmp_absenzen[$i]->_array)) {
				if (is_array($tmp_absenzen[$i]->_array[0])) {
					foreach ($tmp_absenzen[$i]->_array as $eintrag) {
						if ($eintrag[1] == 'F') {
							$zahl = $eintrag[2];
							if (time() < $eintrag[0] && !time_user::is_after_endtime($eintrag[0], $this->_endzeit)) {
								//wenn der eintrag in der Zukunft liegt und noch nicht in den Summen enthalten ist
								$this->_summe_Fgeplant += floatval($eintrag[2]);
							}
						}
					}
				}
			}
			// bei Startjahr Ferienanspruch berechnen
			$this->_saldo_F += floatval($this->calc_Ferien($i));
		}
		$this->_summe_Fz = floatval($this->_summe_Fz)  + floatval($this->_summe_Fgeplant);
		// Summe von Ferienanspruch und Übertragenen Ferien 
		$this->_saldo_F = $this->_saldo_F + floatval($this->_Ferienguthaben_uebertrag);
		// settings == 1 wenn kommende Ferien nicht mitberechnet werden sollen
		if ($tmpsettings->_array[27][1] == 1) {
			$this->_saldo_F = floatval($this->_saldo_F) - floatval($this->_summe_Fv);
		} else {
			$this->_saldo_F = floatval($this->_saldo_F) - floatval($this->_summe_Fv) - floatval($this->_summe_Fz);
		}
		//runden auf 2 Stellen 
		$this->_saldo_F = round($this->_saldo_F, 2);
	}
	function __destruct()
	{
	}
	function savetotal()
	{
		$_zeilenvorschub = "\r\n";
		$totalfie = "./Data/" . $this->_ordnerpfad . "/Timetable/total.txt";
		$fp = fopen($totalfie, "w+");
		fputs($fp, $this->_saldo_t);
		fputs($fp, $_zeilenvorschub);
		fputs($fp, $this->_saldo_F);
		fclose($fp);
	}
	function calc_Ferien($i)
	{
		$monate = time_user::get_year_active_months($i, mktime(0, 0, 0, intval($this->_startmonat), 1, intval($this->_startjahr)), $this->_endzeit);
		if ($monate <= 0) {
			return 0;
		}
		if ($monate < 12) {
			return round((floatval($this->_Ferien_pro_Jahr) / 12 * floatval($monate)), 2);
		}
		return 	$this->_Ferien_pro_Jahr;
	}
	// TODO : löschen ist als Plugin gelöst
	function set_ueberschriften($jahr)
	{
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