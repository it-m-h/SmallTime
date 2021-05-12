<?php
session_start();
date_default_timezone_set("Europe/Paris");
@setlocale(LC_TIME, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de-DE', 'de', 'ge', 'de_DE.UTF-8', 'German');
header("Content-Type: text/html; charset=utf-8");
header('Access-Control-Allow-Origin: *');
//error_reporting(E_ALL ^ E_NOTICE);
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.1
* Author:  IT-Master
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master, All rights reserved
*******************************************************************************/
$login = false;
if(isset($_GET['rfid'])){
	// Mac Adresse = RFID
	// in den Settings einzustellen bei jedem Mitarbeiter, damit er mit seinem Android - Gerät stempeln kann
	$rfid = $_GET['rfid'];
	// Test ohne rfid
	// $rfid = bcf5acfaf028;
	// ----------------------------------------------------------------------------------------------
	// Benutzerdaten in Array ( RFID => Pfad ) lesen:
	// Mac - Adresse des Gerätes ist die RFID
	// ----------------------------------------------------------------------------------------------
	$fp   = @fopen('./Data/users.txt', 'r');
	@fgets($fp); // erste Zeile überspringen
	$u    = 1;
	while(($logindata = fgetcsv($fp, 0, ';')) != false){
		if(@$logindata[3] == $rfid ){
			//erste Spalte der Datei "./Data / user.txt" ist der Pfad des users
			$user = $logindata[0];
			$login= true;
			$_SESSION['admin'] = $logindata[0]; //$this->_datenpfad;
			$_SESSION['id'] = $u;
			$_SESSION['datenpfad'] = $logindata[0]; //$this->_datenpfad;
			$_SESSION['username'] = $logindata[1]; //$this->_username;
			$_SESSION['passwort'] = $logindata[2]; //$this->_passwort;
			$_SESSION['login'] = true; //$this->_login;
		}
		$u++;
	}
	fclose($fp);
	// ----------------------------------------------------------------------------------------------
}else{
	$login = false;
}
// ----------------------------------------------------------------------------------------------
// controller - login true or false und action handling
// ----------------------------------------------------------------------------------------------
if($login){
	include_once ('./include/class_absenz.php');
	include_once ('./include/class_user.php');
	include_once ('./include/class_group.php');
	include_once ('./include/class_login.php');
	include_once ('./include/class_template.php');
	include_once ('./include/class_time.php');
	include_once ('./include/class_month.php');
	include_once ('./include/class_pausen.php');
	include_once ('./include/class_jahr.php');
	include_once ('./include/class_feiertage.php');
	include_once ('./include/class_filehandle.php');
	include_once ('./include/class_rapport.php');
	include_once ('./include/class_show.php');
	include_once ('./include/class_settings.php');
	include ("./include/time_funktionen.php");
	$_users    = new time_filehandle("./Data/","users.txt",";");
	$_groups   = new time_filehandle("./Data/","group.txt",";");
	$_settings = new time_settings();
	$_time     = new time();
	$_time->set_monatsname($_settings->_array[11][1]);
	$_user     = new time_user();
	$_user->load_data_session();
	$_absenz   = new time_absenz($_user->_ordnerpfad, $_time->_jahr);
	$_monat    = new time_month( $_settings->_array[12][1], $_time->_letzterTag, $_user->_ordnerpfad, $_time->_jahr, $_time->_monat, $_user->_arbeitstage, $_user->_feiertage, $_user->_SollZeitProTag, $_user->_BeginnDerZeitrechnung, $_settings->_array[21][1],$_settings->_array[22][1],$_settings->_array[27][1], $_settings->_array[28][1]);
	$_jahr     = new time_jahr($_user->_ordnerpfad, 0, $_user->_BeginnDerZeitrechnung, $_user->_Stunden_uebertrag, $_user->_Ferienguthaben_uebertrag, $_user->_Ferien_pro_Jahr, $_user->_Vorholzeit_pro_Jahr, $_user->_modell, $_time->_timestamp);
	// ----------------------------------------------------------------------------------------------
	// Controller action - Handling
	// ----------------------------------------------------------------------------------------------
	$_action   = "";
	if(isset($_GET['action'])){
		$_action = $_GET['action'];
	}
	switch($_action){
		case "monat":
		get_statistik();
		break;
		case "tag":
		get_tag();
		break;
		case "quicktime":
		$_time->save_quicktime($_user->_ordnerpfad);
		break;
		case "getmitarbeiter":
		get_mitarbeiter();
		break;
		case "getvar":
		// einzelne Variable abfragen und ausgeben, class und var (Klassenneme und Variablenname)
		// android.php?rfid = 1234 & action = getvar & class = _monat & var = _SummeSollProMonat
		// MIt array - Angaben : (immer x und y)
		// android.php?rfid = 1234 & action = getvar & class = _monat & var = _MonatsArray & arr = 17,20
		get_var();
		break;
		default:
		get_statistik();
		break;
	}
}else{
	echo "Keine Daten vorhanden!";
	echo "\n";
	echo "Bitte Settings korrekt einstellen.";
}
function get_var(){
	global $_users;
	global $_groups;
	global $_settings;
	global $_time;
	global $_user;
	global $_absenz;
	global $_monat;
	global $_jahr;
	if(isset($_GET['class']) && isset($_GET['var'])){
		if(isset($_GET['arr'])){
			$koordinaten = explode(",", $_GET['arr']);
			$referenz    =&  $
			{
				$_GET['class']
			}->$_GET['var'];
			$temp = $referenz[$koordinaten[0]][$koordinaten[1]];
			if(is_array($temp)){
				foreach($temp as $zeile){
					echo $zeile . "\n";
				}
			}else{
				echo $temp;
			}
		}else{
			$referenz =& ${$_GET['class']}->{$_GET['var']};
			echo $referenz;
		}
	}
}
function get_xml(){
	//Example object
	$x->name->first = "John";
	$x->name->last = "Smith";
	$x->arr['Fruit'] = 'Bannana';
	$x->arr['Veg'] = 'Carrot';
	$y->customer = $x;
	$y->customer->__attr->id = '176C4';
	$z = get_defined_vars();
	obj2xml($z['y']);
}
function obj2xml($v, $indent = ''){
	while(list($key, $val) = each($v)){
		if($key == '__attr') continue;
		// Check for __attr
		if(is_object($val->__attr)){
			while(list($key2, $val2) = each($val->__attr)){
				$attr .= " $key2=\"$val2\"";
			}
		}
		else $attr = '';
		if(is_array($val) || is_object($val)){
			print("$indent<$key$attr>\n");
			obj2xml($val, $indent.'  ');
			print("$indent</$key>\n");
		}
		else print("$indent<$key$attr>$val</$key>\n");
	}
}
function get_tag(){
	global $_monat;
	global $_time;
	$_heute        = $_time->_tag;
	$_zeiten       = $_monat->_MonatsArray[$_heute][12];
	$_anzehlzeiten = $_monat->_MonatsArray[$_heute][11];
	$x             = 0;
	echo $_monat->_MonatsArray[$_heute][3]. " / " .$_monat->_MonatsArray[$_heute][1]. "." . $_time->_jahr;
	if($_monat->_MonatsArray[$_heute][11] > 0){
		foreach($_zeiten as $einzel){
			if($x % 2 == 0){
				echo "\n";
				echo trim($einzel);
			}else{
				echo " - ";
				echo trim($einzel);
			}
			$x++;
		}
		if($_anzehlzeiten % 2 != 0){
			echo " - ";
			echo "Zeit fehlt!";
		}
	}else{
		echo "\nkeine Stempelzeiten.";
	}
}
function get_statistik(){
	global $_monat;
	global $_time;
	global $_user;
	global $_jahr;
	echo "Monats - Summen : ";
	echo "\n";
	echo "Sollstunden : ". $_monat->_SummeSollProMonat . " Std.";
	echo "\n";
	echo "Gearbeitet : ". $_monat->_SummeWorkProMonat . " Std.";
	echo "\n";
	echo "Saldo : ". $_monat->_SummeSaldoProMonat . " Std.";
	echo "\n";
	if($_monat->_SummeFerien > 0){
		echo "Ferienbezug : ". $_monat->_SummeFerien  . "  Tage (F)";
		echo "\n";
	}
	if($_monat->_SummeKrankheit > 0){
		echo "Krankheit : ". $_monat->_SummeKrankheit  . " Tage (K)";
		echo "\n";
	}
	if($_monat->_SummeUnfall > 0){
		echo "Unfall : ". $_monat->_SummeUnfall . "  Tage (U)";
		echo "\n";
	}
	if($_monat->_SummeMilitaer > 0){
		echo "Militär : ". $_monat->_SummeMilitaer  . " Tage (M)";
		echo "\n";
	}
	if($_monat->_SummeIntern > 0){
		echo "Intern : ". $_monat->_SummeIntern  . " Tage (I)";
		echo "</tr>";
	}
	if($_monat->_SummeWeiterbildung > 0){
		echo "Weiterbildung : ". $_monat->_SummeWeiterbildung  . " Tage (W)";
		echo "\n";
	}
	if($_monat->_SummeExtern > 0){
		echo "Extern : ". $_monat->_SummeExtern  . " Tage (E)";
		echo "\n";
	}
}
function get_mitarbeiter(){
	//In Arbeit
	if(!$_grpwahl) $_grpwahl = 1;
	if($_grpwahl == - 1)$_grpwahl = 1;
	$_group   = new time_group($_grpwahl);
	if($id) $_grpwahl = $_group->get_usergroup($id);
	$anzMA    = count($_group->_array[1][$_grpwahl]);
	for($x = 0; $x < $anzMA ;$x++){
		$count_time = count($_group->_array[5][$_grpwahl][$x]);
		$anwesend   = $count_time % 2;
		if($anwesend) $pic = "anw.";else $pic = "----";
		echo $pic . " | ". $_group->_array[4][$_grpwahl][$x];
		// TODO : erweitern damit die Mitarbeiter - Liste angezeigt werden kann im Android
		/*
		f($anwesend){
		echo " /  " . $_group->_array[5][$_grpwahl][$x][count($_group->_array[5][$_grpwahl][$x])-1];
		}else{
		echo " / Abwesend";
		}        */
		echo "\n";
	}
}