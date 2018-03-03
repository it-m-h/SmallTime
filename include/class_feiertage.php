<?php
/*******************************************************************************
* Feiertage für das gewählte Jahr
/*******************************************************************************
* Version 0.9.020
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_feiertage
{
	public	$_w_jahr 	= NULL;
	public	$_country 	= NULL;
	public	$_easter 	= NULL;
	public	$_feiertageUSER = NULL;
	public  	$_feiertage 	= array();			// Feiertage die gültig sind
	private 	$_defineFT 	= array();			// definition der Feiertage
	private 	$_file 		= "./include/Settings/feiertage.txt";
	function __construct($_w_jahr, $_country, $_feiertageUSER)
	{
		$this->_w_jahr = $_w_jahr;
		$this->_country = $_country;
		$this->_feiertageUSER = $_feiertageUSER;
		$this->_easter = $this->easter2($_w_jahr);
		$this->_defineFT = $this->defineFeiertag($_w_jahr, $_country);
		$this->_feiertage = $this->getFeiertage($_w_jahr,$_country);
	}
	function __destruct()
	{
	}
	function getFeiertage($year = NULL, $_country)
	{
		//Feiertage All werden mit den Einstellungen beim User verglichen, ob true oder false
		$z = 0;
		foreach($this->_defineFT as $_bez => $_tag)
		{
			
			
			
			if(trim(@$this->_feiertageUSER[$z - 1]) == "1"){
				//echo 'feiertag';
				//echo $_bez .' : ' .$this->_feiertageUSER[$z-1].'<br>'; 
				$holidays[$_bez] = $_tag;
			}elseif($z == 0){
				$holidays[$_bez] = $_tag;
			}
			$z++; 
		}
		//Individuelle Feiertage laden
		$_userfeiertage = file($this->_file);
		foreach($_userfeiertage as $_eintrag){
			$_eintrag = explode(";", $_eintrag);
			$_datum   = date('d.n', $_eintrag[1]);
			$_datum   = explode(".", $_datum);
			$_datum   = mktime(0,0,0,$_datum[1],$_datum[0],$year) ;
			$holidays[$_eintrag[0]] = $_datum;
		}
		return $holidays;
	}
	function easter($year = null)
	{
		if(strlen(strval($year)) == 2){
			if($year < 70) $year += 2000;
			else $year += 1900;
		}
		if($year > 2038 || $year < 1901) return false;  // limitations of date() / mktime(), if OS == Win change 1901 to 1970!
		$d     = (((255 - 11 * ($year % 19)) - 21) % 30) + 21;
		$delta = $d + ($d > 48) + 6 - (($year + $year / 4 + $d + ($d > 48) + 1) % 7);
		$easter= strtotime("+$delta days", mktime(0,0,0,3,1,$year));
		return $easter;
	}
	function easter2($year)
	{
		if(strlen(strval($year)) == 2){
			if($year < 70) $year += 2000;
			else $year += 1900;
		}
		if($year > 2038 || $year < 1901) return false;
		//limitations of date() / mktime(), if OS == Win change 1901 to 1970!
		$a = $year % 19;
		$b = $year % 4;
		$c = $year % 7;
		$m = ((8 * ($year / 100) + 13) / 25) - 2;
		$s = ($year / 100) - ($year / 400) - 2;
		$M = (15 + $s - $m) % 30;
		$N = (6 + $s) % 7;
		$d = ($M + 19 * $a) % 30;
		if($d == 29) $D = 28;
		elseif($d == 28 && $a >= 11) $D = 27;
		else $D     = $d;
		$e     = (2 * $b + 4 * $c + 6 * $D + $N) % 7;
		$delta = $D + $e + 1;
		return strtotime("+$delta days", mktime(0,0,0,3,21,$year));
	}
	function getFeiertageALL($year = NULL, $_country)
	{
		return $this->_defineFT;
	}
	function getFeiertageUserEdit()
	{
		$z = 0;
		$holidays = array();
		foreach($this->_defineFT as $_bez => $_tag)
		{
			$holidays[$z] = array('_bez' => $_bez,'_tag' => $_tag,'_wahl'=> @$this->_feiertageUSER[$z - 1], '_id' => $z);
			$z++;
		}
		unset($holidays[0]);
		$holidays = $this->array_orderby($holidays, '_tag', SORT_ASC);
		return $holidays;
	}
	function save_feiertage()
	{
		$_anzahl = $_POST['anzahl'];
		$_tmparr = array();
		for($x = 0; $x <= $_anzahl; $x++){
			$_name = $_POST['e'.$x];
			$_datum= $_POST['v'.$x];
			if($_name <> "" && $_datum <> ""){
				$_datum = explode(".", $_datum);
				$_datum2= mktime(0,0,0,$_datum[1],$_datum[0],0);
				$_tmparr[$x] = $_name.";".$_datum2;
			}
		}
		$neu = implode( "\r\n", $_tmparr);
		$open= fopen($this->_file,"w+");
		fwrite ($open, $neu);
		fclose($open);
	}
	function delete_feiertag($id)
	{
		$_temp = file($this->_file);
		unset($_temp[$id]);
		$neu = implode( "", $_temp);
		$open= fopen($this->_file,"w+");
		fwrite ($open, $neu);
		fclose($open);
	}
	function get_firmenfeiertage()
	{
		$_tmparr = file($this->_file);
		$x       = 0;
		foreach($_tmparr as $_zeile){
			$_tmparr[$x] = explode(";", $_tmparr[$x]);
			$_tmparr[$x][1] = date('d.n', $_tmparr[$x][1]);
			$x++;
		}
		return $_tmparr;
	}
	function array_orderby()
	{
		$args = func_get_args();
		$data = array_shift($args);
		foreach($args as $n => $field)
		{
			if(is_string($field))
			{
				$tmp = array();
				foreach($data as $key => $row)
				$tmp[$key] = $row[$field];
				$args[$n] = $tmp;
			}
		}
		$args[] = & $data;
		call_user_func_array('array_multisort', $args);
		return array_pop($args);
	}
	function defineFeiertag($year = NULL, $_country)
	{
		// Feiertage kännen ergänzt werden (je nach Land oder Kanton usw.)
		// Formular beim User wird automatisch erweitert (sortiert nach Datum)
		// Speichern erfolgt ebenfalls automatisch
		if($easter = $this->easter($year))
		{
			//Landesfeiertag - aus den Settings------------------------------------------------------
			if($_country == 1) $holidays['Bundesfeier'] = mktime(0,0,0,8,1,$year);
			if($_country == 2) $holidays['Tag der deutschen Einheit'] = mktime(0,0,0,10,3,$year);
			if($_country == 3) $holidays['&ouml;sterreichische Nationalfeiertag'] = mktime(0,0,0,10,26,$year);
			if($_country == 4) $holidays['Staatsfeiertag in Liechtenstein'] = mktime(0,0,0,8,15,$year);
			//Landesfeiertag - aus den Settings------------------------------------------------------
			$holidays['Neujahr'] = mktime(0,0,0,1,1,$year);
			$holidays['Rosenmontag'] = strtotime("-48 days", $easter);
			$holidays['Aschermittwoch'] = strtotime("-46 days", $easter);
			$holidays['TagDerArbeit'] = mktime(0,0,0,5,1,$year);
			$holidays['Karfreitag'] = strtotime("-2 days", $easter);
			$holidays['Ostersonntag'] = $easter;
			$holidays['Ostermontag'] = strtotime("+1 day", $easter);
			$holidays['Himmelfahrt'] = strtotime("+39 days", $easter);
			$holidays['Pfingsten'] = strtotime("+49 days", $easter);
			$holidays['Pfingstsonntag'] = strtotime("+49 days", $easter);
			$holidays['Pfingstmontag'] = strtotime("+50 days", $easter);
			$holidays['Fronleichnam'] = strtotime("+60 days", $easter);
			$holidays['Allerheiligen'] = mktime(0,0,0,11,1,$year);
			$holidays['Bussbettag'] = strtotime("-11 days", strtotime("1 sunday", mktime(0,0,0,11,26,$year)));
			$holidays['Advent1'] = strtotime("1 sunday", mktime(0,0,0,11,26,$year));
			$holidays['Advent2'] = strtotime("2 sunday", mktime(0,0,0,11,26,$year));
			$holidays['Advent3'] = strtotime("3 sunday", mktime(0,0,0,11,26,$year));
			$holidays['Advent4'] = strtotime("4 sunday", mktime(0,0,0,11,26,$year));
			$holidays['Heiligabend'] = mktime(0,0,0,12,24,$year);
			$holidays['Weihnachten'] = mktime(0,0,0,12,25,$year);
			$holidays['Stephanstag'] = mktime(0,0,0,12,26,$year);
			$holidays['Silvester'] = mktime(0,0,0,12,31,$year);
			//$holidays['Berchtoldstag'] = mktime(0,0,0,1,2,$year);
			//$holidays['Mariä Himmelfahrt'] = mktime(0,0,0,8,15,$year);
			//$holidays['St. Leodegar'] = mktime(0,0,0,10,2,$year);
			return $holidays;
		}

	}
}