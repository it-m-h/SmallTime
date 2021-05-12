<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.1
* Author:  IT-Master
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master, All rights reserved
*******************************************************************************/
//Session starten
if( !my_session_start() ){
	session_id( uniqid() );
	session_start();
	session_regenerate_id();
}
function my_session_start(){
	$sn = session_name();
	if(isset($_COOKIE[$sn])){
		$sessid = $_COOKIE[$sn];
	} else
	if(isset($_GET[$sn])){
		$sessid = $_GET[$sn];
	} else{
		return session_start();
	}

	if(!preg_match('/^[a-zA-Z0-9,\-]{22,40}$/', $sessid)){
		return false;
	}
	return session_start();
}
define('DEBUG', false);
if(DEBUG == true){
	error_reporting(E_ALL);
	//error_reporting(E_ALL ^ E_NOTICE);
	ini_set("display_errors", 1);
}
else{
	error_reporting(0);
	ini_set("display_errors", 0);
}
// Zeitzone setzten, damit die Stunden richtig ausgerechnet werden
date_default_timezone_set("Europe/Paris");
@setlocale(LC_TIME, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de-DE', 'de', 'ge', 'de_DE.UTF-8', 'German');
//Memory - ab ca. 15 Usern auf 32 stellen, ab 30 auf 64 und ab 60 auf 128M usw.
@ini_set('memory_limit', '32M');
// Microtime für die Seitenanzeige (Geschwindigkeit des Seitenaufbaus)
$_start_time = explode(" ",microtime());
$_start_time = $_start_time[1] + $_start_time[0];
// ----------------------------------------------------------------------------
// F5 verhindern dass daten zwei mal gespeichert werden kann
// ----------------------------------------------------------------------------
//$_write = true;         // Daten werden dann  gespeichert
if(isset($_GET['token'])){
	$_now = $_GET['token'];
}else{
	$_now = NULL;
}
$token = md5(uniqid('SmallTime'));
//echo  $_SESSION['last'] . " - " . $_now ;
//echo " < br > ";
if(isset($_SESSION['last']) && trim($_SESSION['last']) == trim($_now )){
	//echo "Speichern erlaubt";
	$_write = true;
}else{
	//echo "token sind identisch, speichern nicht erlaubt";
	$_write = false;
}
$_SESSION['last'] = $token;
// ----------------------------------------------------------------------------
// Anzeige Bootstrap - Modal
// ----------------------------------------------------------------------------
global $_modal;
$_modal = (isset($_GET['modal']) == true ? true : false);
// ----------------------------------------------------------------------------
// Modler laden
// ----------------------------------------------------------------------------
define('FPDF_INSTALLDIR', './fpdf');
if(!defined('FPDF_FONTPATH')) define('FPDF_FONTPATH', FPDF_INSTALLDIR.'/font/');
include_once(FPDF_INSTALLDIR.'/fpdf.php');
//include_once ('./include / class_controller.php');
include_once ('./include/class_absenz.php');
include_once ('./include/class_auszahlung.php');
include_once ('./include/class_user.php');
include_once ('./include/class_group.php');
include_once ('./include/class_login.php');
include_once ('./include/class_template.php');
include_once ('./include/class_time.php');
include_once ('./include/class_month.php');
include_once ('./include/class_personalblatt.php');
include_once ('./include/class_pausen.php');
include_once ('./include/class_jahr.php');
include_once ('./include/class_feiertage.php');
include_once ('./include/class_filehandle.php');
include_once ('./include/class_rapport.php');
include_once ('./include/class_show.php');
include_once ('./include/class_settings.php');
require_once ('./include/class_table.php');
include ("./include/time_funktionen.php");
//$controller = new time_controller();
// ----------------------------------------------------------------------------
// Im Admin - Bereich bis zum gewählten Monat berechnen (für Druck und Anzeige)
// ----------------------------------------------------------------------------
if(isset($_GET['calc'])){
	$_SESSION['calc'] = $_GET['calc'];
}else{
	$_SESSION['calc'] = NULL;
}
// ----------------------------------------------------------------------------
// Modler allgemeine Daten laden
// ----------------------------------------------------------------------------
$_users    = new time_filehandle("./Data/","users.txt",";");
$_groups   = new time_filehandle("./Data/","group.txt",";");
$_settings = new time_settings();
$_template = new time_template("index.php");
$_template->set_portal(1);
$_favicon  = "./images/favicon.ico";
// ----------------------------------------------------------------------------
// .htaccess - Dateien überprüfen und setzten
// bei der Übernahme von alten Daten notwendig
// ----------------------------------------------------------------------------
$id        = 0;
foreach($_users->_array as $tmpuser){
	if($tmpuser[0] <> "Pfad"){
		create_htaccess($tmpuser[0]);
	}
}

// ----------------------------------------------------------------------------
// Controller für Login
// ----------------------------------------------------------------------------
$_logcheck = new time_login();
// falls eine Session exisitert und kein Action
if(isset($_SESSION['admin']) and !isset($_GET['action'])){
	$_logcheck->rapport($_SESSION['admin'],"korrekt", "Session");
}
// keine Session vorhanden
if(!$_POST){
	if(!isset($_SESSION['admin'])){
		$_Userpfad = "administrator/";
	}else{
		$_Userpfad = $_SESSION['admin']."/";
	}
}
// Login über Cookie mit Datenüberprüfung - bei Mehrbenutzerbetrieb sollte nicht über sookie eingeloggt werden
if($_COOKIE["lname"] and $_COOKIE["lpass"] and $_settings->_array[19][1] == "0" and ($_SESSION['admin'] == NULL OR $_SESSION['admin'] == "")){
	$_logcheck->login($_POST, $_users->_array);
}
// Loginformular - Datenüberprüfung
if(isset($_POST['login'])){
	$_logcheck->login($_POST, $_users->_array);
}
if(isset($_GET['action']) && $_GET['action'] == "logout"){
	$_logcheck->logout();
	header("Location: index.php");
	exit();
}

//if(in_array(2,$show)) showClassVar($_logcheck);
// ----------------------------------------------------------------------------
// Controller für Action
// ----------------------------------------------------------------------------
if(isset($_GET['group'])) {
	$_grpwahl = $_GET['group'] - 1;
}else{
	$_grpwahl = 0;
}
// Session  vorhanden - Daten anzeigen
if(isset($_SESSION['admin']) and !isset($_GET['action'])){
	$_action = "show_time";
}elseif(isset($_GET['action']) && isset($_SESSION['admin'])){
	$_action = $_GET['action'];
}elseif(isset($_GET['group'])){
	$_action = "login_mehr";
	if($_GET['group'] == "-1"){
		$_action = "login_einzel";
	}
}elseif($_settings->_array[19][1] == "1"){
	//Falls Mehrbenutzersystem eingestellt wurde
	$_action = "login_mehr";
}
// ----------------------------------------------------------------------------
// Modler Userdaten laden
// ----------------------------------------------------------------------------
if(isset($_SESSION['admin'])){
	// ----------------------------------------------------------------------------
	// DEKLARATION DER VARIABLEN
	// ----------------------------------------------------------------------------
	include ('./include/time_variablen_laden.php');
}
// ----------------------------------------------------------------------------
// Sicherheit, darf der Mitarbeiter editieren -> alte timestamp
// ----------------------------------------------------------------------------
// $_settings->_array[23][1] = wie viel Tage zurück
// TODO : falls in den Settings eingestellt wurde wie lange zurück Änderungen vorgenommen werden können, timestamp vergleichen
$edit = true;
// ----------------------------------------------------------------------------
// Controller Templatedarstellung
// ----------------------------------------------------------------------------

switch($_action){
	case "password":
	$_infotext = "";
	if(isset($_POST['senden'])){
		if($_POST['new1'] <> $_POST['new2']  OR $_POST['new1'] == "" OR $_POST['new2'] == ""){
			$_infotext = getinfotext('Neue Passw&ouml;rter nicht identisch','alert-error');
		}elseif(sha1($_POST['old']) <> $_SESSION['passwort'] and $_POST['old'] <> ""){
			$_infotext = getinfotext('Altes Passwort nicht korrekt','alert-error');
		}else{
			$_infotext = getinfotext('Neues Passwort wurde gespeichert','alert-error');
			$tmpusers  = file("./Data/users.txt");
			for($u = 0; $u <= count($tmpusers); $u++){
				$zeilen = explode(";", $tmpusers[$u]);
				if($zeilen[1] == $_user->_loginname){
					$tmpusers[$u] = str_replace(sha1($_POST['old']),sha1($_POST['new1']),$tmpusers[$u]);
				}
			}
			$neu = implode( "", $tmpusers);
			$open= fopen("./Data/users.txt","w+");
			fwrite ($open, $neu);
			fclose($open);
		}
	}else{
		$_infotext = getinfotext('Passwort ver&auml;ndern','td_background_top');
	}
	$_template->_user02 = "sites_user/user02.php";
	$_template->_user04 = "sites_user/user04_password.php";
	$_template->_user03 = "sites_user/user03_stat.php";
	break;
	case "show_year":
	$auszahlung = new auszahlung(1,2000);
	$_template->_user02 = "sites_user/user02_cal.php";
	$_template->_user04 = "sites_year/user04_year.php";
	$_template->_user03 = "sites_user/user03_stat.php";
	break;
	case "anwesend":
	if($_grpwahl == 0) $_grpwahl = 1;
	$_group   = new time_group($_grpwahl);
	if($id) $_grpwahl = $_group->get_usergroup($id);
	$_template->_user02 = "sites_login/login_mehr_02.php";
	$_template->_user04 = "sites_login/login_mehr_04.php";
	$_template->_user03 = "sites_user/user03_stat.php";
	break;
	case "login_mehr":
	if(isset($_SESSION['save'])) $_SESSION['save'] = 8;
	if(isset($_POST['login']) && $_POST['login'] == "Stempelzeit eintragen" && $_write){
		$_logcheck->login($_POST, $_users->_array);
		if($_SESSION['admin']){
			$id = $_logcheck->_id;
			// Fehlerhandling bei F5 und dann sendenklick
			if($_POST['_n'] <> "" and $_POST['_p'] <> ""){
				$_time->set_timestamp(time());
				$_time->save_time(time(), $_user->_ordnerpfad);
			}else{
			}
			$_logcheck->logout();
			header("Location: index.php?action=login_mehr&tmp=1");
		}else{
			header("Location: index.php?action=login_mehr&tmp=2");
		}
		exit();
	}
	$_infotext02 = getinfotext( "Stempel - Pannel","td_background_top");
	if(isset($_GET['tmp'] ) && $_GET['tmp'] == "1"){
		$_infotext04 = getinfotext( "Stempelzeit erfasst!","alert alert-success");
		//$_infotext04 = "";
	}elseif(isset($_GET['tmp']) && $_GET['tmp'] == "2"){
		$_infotext04 = getinfotext( "Falscher Login!","alert alert-danger");
	}else{
		//$_infotext04 = getinfotext( "Bitte Username und Passwort eingeben!","td_background_top");
		$_infotext04 = "";
	}
	$_template->_user01 = "sites_time/null.php";
	$_template->_user02 = "sites_login/login_mehr_02.php";
	$_template->_user03 = "sites_login/login_mehr_03.php";
	$_template->_user04 = "sites_login/login_mehr_04.php";
	break;
	case "login_einzel":
	$_template->_user01 = "sites_time/null.php";
	$_template->_user02 = "sites_login/login_einzel_02.php";
	if($_GET['group'] == "-1") $_template->_user03 = "sites_login/login_einzel_03.php";
	$_template->_user04 = "sites_login/login_einzel_04.php";
	break;
	case "login":
	$_logcheck = new time_login($_POST, $_users->_array);
	break;
	case "logout":
	$_logcheck->logout();
	$_grpwahl = 1;
	$_group   = new time_group($_grpwahl);
	setLoginForm();
	break;
	case "add_rapport":
	// Sicherheitscheck : Settings - 18 : Darf der User einen Rapport eintragen
	if($_settings->_array[18][1] && $edit){
		$_rapport = new time_rapport();
	}
	$_template->_user02 = "sites_user/user02_cal.php";
	$_template->_user04 = "sites_time/rapport_add_04.php";
	$_template->_user03 = "sites_user/user03_stat.php";
	break;
	case "insert_rapport":
	// Sicherheitscheck : Settings - 18 : Darf der User einen Rapport eintragen
	if($_settings->_array[18][1] && $edit){
		$_rapport = new time_rapport();
		if($_POST['absenden'] == "UPDATE" and $_write){
			$_rapport->insert_rapport($_user->_ordnerpfad, $_time->_timestamp);
		}elseif($_POST['absenden'] == "DELETE" and $_write){
			$_rapport->delete_rapport($_user->_ordnerpfad, $_time->_timestamp);
		}
	}
	$_template->_user02 = "sites_user/user02_cal.php";
	$_template->_user04 = "sites_user/user04_timetable.php";
	$_template->_user03 = "sites_user/user03_stat.php";
	break;
	case "add_absenz":
	// Sicherheitscheck : Settings - 17 : Darf der User eine Absenz eintragen
	if($_settings->_array[17][1] && $edit){
		$_template->_user02 = "sites_user/user02_cal.php";
		$_template->_user04 = "sites_time/absenz_add_04.php";
		$_template->_user03 = "sites_user/user03_stat.php";
	}
	break;
	case "insert_absenz":
	// Sicherheitscheck : Settings - 17 : Darf der User eine Absenz eintragen
	if($_settings->_array[17][1] && $edit){
		if($_POST['absenden'] == "OK" and $_write){
			$_absenz->insert_absenz($_user->_ordnerpfad, $_time->_jahr);
		}
		$_template->_user02 = "sites_user/user02_cal.php";
		$_template->_user04 = "sites_user/user04_timetable.php";
		$_template->_user03 = "sites_user/user03_stat.php";
	}
	break;
	case "delete_absenz":
	// Sicherheitscheck : Settings - 17 : Darf der User eine Absenz eintragen
	if($_settings->_array[17][1] && $edit){
		$_absenz->delete_absenz($_user->_ordnerpfad, $_time->_jahr);
		$_template->_user02 = "sites_user/user02_cal.php";
		$_template->_user04 = "sites_user/user04_timetable.php";
		$_template->_user03 = "sites_user/user03_stat.php";
	}
	break;
	case "edit_time":
	// Sicherheitscheck : Settings - 14 : darf der Mitarbeiter alte Stempelzeiten editieren
	if($_settings->_array[14][1] && $edit){
		$_template->_user02 = "sites_user/user02_cal.php";
		$_template->_user04 = "sites_time/time_edit_04.php";
		$_template->_user03 = "sites_user/user03_stat.php";
	}
	break;
	case "update_time":
	// Sicherheitscheck : Settings - 14 : darf der Mitarbeiter alte Stempelzeiten editieren
	if($_settings->_array[14][1] && $edit){
		$_oldtime = $_GET['timestamp'];
		$_newtime = $_time->mktime($_POST['_w_stunde'],$_POST['_w_minute'],0,$_POST['_w_monat'], $_POST['_w_tag'],$_POST['_w_jahr']);
		if($_POST['absenden'] == "UPDATE" and $_write){
			// update oldtime, newtime, Ordner
			$_time->update_stempelzeit($_oldtime, $_newtime, $_user->_ordnerpfad);
		}elseif($_POST['absenden'] == "DELETE" and $_write){
			// delete //oldtime, Ordner
			$_time->delete_stempelzeit($_oldtime, $_user->_ordnerpfad);
		}
	}
	$_template->_user02 = "sites_user/user02_cal.php";
	$_template->_user04 = "sites_user/user04_timetable.php";
	$_template->_user03 = "sites_user/user03_stat.php";
	break;
	case "insert_time_list":
	// Sicherheitscheck : Settings - 16 : Falls der User mehrere Zeiten eintragen darf
	if($_settings->_array[16][1] && $edit){
		if($_POST['absenden'] == "OK" and $_write){
			$_timestamp = $_GET['timestamp'];
			$_w_tag     = $_POST['_w_tag'];
			$_w_monat   = $_POST['_w_monat'];
			$_w_jahr    = $_POST['_w_jahr'];
			$_zeitliste = $_POST['_zeitliste'];
			if($_zeitliste <> ""){
				$_w_sekunde = 0;
				$_zeitliste = trim($_zeitliste);
				$_zeitliste = str_replace(" ", "", $_zeitliste);
				$_zeitliste = str_replace(" ", "", $_zeitliste);
				$_zeitliste = str_replace(" ", "", $_zeitliste);
				$_zeitliste = str_replace(":", ".", $_zeitliste);
				$_zeitliste = str_replace(",", ".", $_zeitliste);
				$_zeitliste = explode("-",$_zeitliste);
				$_temptext  = "";
				foreach($_zeitliste as $_zeiten){
					$_tmp      = explode(".",$_zeiten);
					$_w_stunde = $_tmp[0];
					$_w_minute = $_tmp[1];
					if($_w_minute == "")$_w_minute = 0;
					$tmp       = $_time->mktime($_w_stunde,$_w_minute,0,$_w_monat, $_w_tag,$_w_jahr);
					$_time->save_time($tmp, $_user->_ordnerpfad);
				}
			}
		}
	}
	$_template->_user02 = "sites_user/user02_cal.php";
	$_template->_user04 = "sites_user/user04_timetable.php";
	$_template->_user03 = "sites_user/user03_stat.php";
	break;
	case "insert_time":
	// Sicherheitscheck : Settings - 15 : Falls der User eine Zeit eintragen darf
	if($_settings->_array[15][1] && $edit){
		if($_POST['absenden'] == "OK" and $_write){
			//if :falls eine Zeit fehlte / elseif : falls eine alte Zeit über Mitternacht geht
			if($_POST['oldtime'] == 1){
				$tmp2 = $_time->mktime($_POST['_w2_stunde'],$_POST['_w2_minute'],0,$_POST['_w2_monat'], $_POST['_w2_tag'],$_POST['_w2_jahr']);
				$_time->set_timestamp($tmp2);
				$_time->save_time($tmp2, $_user->_ordnerpfad);
			} elseif($_POST['oldtime'] == 2){
				$tmp3 = $_time->mktime(23,59,59,$_POST['_w2_monat'], $_POST['_w2_tag'],$_POST['_w2_jahr']);
				$_time->set_timestamp($tmp3);
				$_time->save_time($tmp3, $_user->_ordnerpfad);

				$tmp2 = $_time->mktime(0,0,0,$_POST['_w_monat'], $_POST['_w_tag'],$_POST['_w_jahr']);
				$_time->set_timestamp($tmp2);
				$_time->save_time($tmp2, $_user->_ordnerpfad);
			}
			$tmp = $_time->mktime($_POST['_w_stunde'],$_POST['_w_minute'],0,$_POST['_w_monat'], $_POST['_w_tag'],$_POST['_w_jahr']);
			$_time->set_timestamp($tmp);
			$_time->save_time($tmp, $_user->_ordnerpfad);
		}
	}
	$_template->_user02 = "sites_user/user02_cal.php";
	$_template->_user04 = "sites_user/user04_timetable.php";
	$_template->_user03 = "sites_user/user03_stat.php";
	break;
	case "quick_time":
	$_time->set_runden((int) $_settings->_array[25][1]);
	$_time->save_quicktime($_user->_ordnerpfad);
	$_template->_user02 = "sites_user/user02_cal.php";
	$_template->_user04 = "sites_user/user04_timetable.php";
	$_template->_user03 = "sites_user/user03_stat.php";
	header("Location: index.php");
	break;
	case "add_time":
	// Sicherheitscheck : Settings - 15 : Falls der User eine Zeit eintragen darf
	if($_settings->_array[15][1] && $edit){
		$_template->_user02 = "sites_user/user02_cal.php";
		$_template->_user04 = "sites_time/time_add_04.php";
		$_template->_user03 = "sites_user/user03_stat.php";
	}
	break;
	case "add_time_list":
	// Sicherheitscheck : Settings - 16 : Falls der User mehrere Zeiten eintragen darf
	if($_settings->_array[16][1] && $edit){
		$_template->_user02 = "sites_user/user02_cal.php";
		$_template->_user04 = "sites_time/time_addlist_04.php";
		$_template->_user03 = "sites_user/user03_stat.php";
	}
	break;
	case "show_time":
	$_template->_user02 = "sites_user/user02_cal.php";
	$_template->_user04 = "sites_user/user04_timetable.php";
	$_template->_user03 = "sites_user/user03_stat.php";
	break;
	case "show_pdf":
	$_template->_user02 = "sites_user/user02_cal.php";
	$_template->_user04 = "sites_user/user04_pdf.php";
	$_template->_user03 = "sites_user/user03_stat.php";
	break;
	case "print_month":
	include ("./include/time_funktion_pdf.php");
	check_htaccess_pdf($_user->_ordnerpfad);
	$_print = $_GET['print'];
	$_druck = $_print;
	$_jahr  = date("Y", time());
	$_monat = date("n", time()) - 1;
	$_tag   = date("j", time());
	if($_druck){
		//erstelle_pdf_more($_MonatsArray); // TODO: undefined function
	}else{
		if($_settings->_array[20][1] >= $_tag){
			$_drucktime = mktime(0,0,0,$_monat,$_tag,$_jahr);
			$_time->set_timestamp($_drucktime);
			$_time->set_monatsname($_settings->_array[11][1]);
			erstelle_neu($_drucktime);
			$_template->_user04 = "sites_user/user04_pdf_show.php";
		}elseif($_settings->_array[20][1] == 0 ){
			erstelle_neu(0);
			$_template->_user04 = "sites_user/user04_pdf_show.php";
		}else{
			$_infotext04 = "Leider ist ein Drucken nicht mehr m&ouml;glich, wende Dich bitte an den Admin.";
			$_template->_user04 = "sites_user/user04.php";
		}
	}
	$_template->_user02 = "sites_user/user02_cal.php";
	$_template->_user03 = "sites_user/user03_stat.php";
	break;
	case "design":
	$_template->_user02 = "sites_user/user02_cal.php";
	$_template->_user04 = "sites_user/user04_design.php";
	$_template->_user03 = "sites_user/user03_stat.php";
	break;
	case "setdesign":
	$_design   = $_GET['designname'];
	setcookie ("designname", $_design, time() + 2592000);
	$_template = NULL;
	unset($_template);
	$_template = new time_template("index.php");
	$_template->set_templatepfad($_design);
	$_template->set_user02("sites_user/user02_cal.php");
	$_template->set_user03("sites_user/user03_stat.php");
	$_template->set_user04("sites_user/user04_design.php");
	break;
	default:
	setLoginForm();
	break;
}
// ----------------------------------------------------------------------------
// Logion - Formular darstellen
// ----------------------------------------------------------------------------
function setLoginForm(){
	global $_template, $_settings;
	if($_settings->_array[19][1] == 0){
		$_template->_user01 = "sites_time/null.php";
		$_template->_user02 = "sites_login/login_einzel_02.php";
		$_template->_user03 = "sites_login/login_einzel_03.php";
		$_template->_user04 = "sites_login/login_einzel_04.php";
	}else{
		$_template->_user01 = "sites_time/null.php";
		$_template->_user02 = "sites_login/login_mehr_02.php";
		$_template->_user03 = "sites_login/login_mehr_03.php";
		$_template->_user04 = "sites_login/login_mehr_04.php";
	}
}


if(isset($_SESSION['admin'])){
	// ----------------------------------------------------------------------------
	// Monatsdaten berechnen
	// ----------------------------------------------------------------------------
	$_monat = new time_month( $_settings->_array[12][1], $_time->_letzterTag, $_user->_ordnerpfad, $_time->_jahr, $_time->_monat, $_user->_arbeitstage, $_user->_feiertage, $_user->_SollZeitProTag, $_user->_BeginnDerZeitrechnung, $_settings->_array[21][1],$_settings->_array[22][1],$_settings->_array[27][1], $_settings->_array[28][1]);
	$_monat->_modal = $_template->_modal;
	// ----------------------------------------------------------------------------
	// Jahresdaten berechnen
	// ----------------------------------------------------------------------------
	// berechnung Endjahr = aktuelles jahr, dann 0 sonst $_time->_jahr
	$_jahr = new time_jahr($_user->_ordnerpfad, 0, $_user->_BeginnDerZeitrechnung, $_user->_Stunden_uebertrag, $_user->_Ferienguthaben_uebertrag, $_user->_Ferien_pro_Jahr, $_user->_Vorholzeit_pro_Jahr, $_user->_modell, $_time->_timestamp);
}

$_copyright   = "<div class=copyright>";
//-----------------------------------------------------------------------------
//Seitenladezeit
//-----------------------------------------------------------------------------
$_time_end    = explode(" ",microtime());
$_time_end    = $_time_end[1] + $_time_end[0];
// ^^ Jetzt wird wieder die Aktuelle Zeit gemessen
$_zeitmessung = $_time_end - $_start_time;
// ^^ Endzeit minus Startzeit = die Differenz der beiden Zeiten
$_zeitmessung = substr($_zeitmessung,0,4);
// ^^ Die Zeit wird auf X Kommastellen gekürzt
$_copyright .= "<hr color=#DFDFDF size=1>Ladezeit der Seite: $_zeitmessung Sekunden.<br>";
// ----------------------------------------------------------------------------
// copyright Text
// ----------------------------------------------------------------------------
$_arr = file("./include/Settings/copyright.txt");
$_ver = file("./include/Settings/smalltime.txt");
$_copyright .= "";
$_mem_usage = round((memory_get_peak_usage(true) / 1048576), 3);
if($_mem_usage > 19.9){
	$_debug = new time_filehandle("./debug/","time.txt",";");
	$_seite = explode('?', $_SERVER['HTTP_REFERER']);
	$_debug->insert_line("Memory Fehler ;". date('d.m.Y', time()) ."; File:  admin.php?".$_seite[1]."; RAM:".$_mem_usage);
}
foreach($_arr as $_zeile){
	$_tmp = str_replace("##ver##",$_ver[0], $_zeile);
	$_tmp = str_replace("##phpver##", phpversion(), $_tmp);
	$_tmp = str_replace("##memory##", $_mem_usage, $_tmp);
	$_copyright .= $_tmp;
}
$_copyright .= "</div>";
// ----------------------------------------------------------------------------
// Viewer - Anzeige der Seite
// ----------------------------------------------------------------------------
if(isset($_GET['modal'])){
	// bei Modal nur DIV04 anzeigen
	include($_template->get_user04());
}elseif(isset($_GET['excel'])){
	$_datei = str_ireplace(" ", "-", trim($_user->_name));
	$_datei = $_datei . "-" . $_time->_jahr;
	$_datei = $_datei . "-" . $_time->_monat;
	$_datei = $_datei . ".xls";
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=". $_datei);
	$_template->_user04 = "sites_user/user04_xls_monat.php";
	include($_template->get_user04());
}else{
	include ($_template->get_template());
}