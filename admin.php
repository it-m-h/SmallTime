<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.87
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
//Session starten
session_start();
// Caching verhindern
//header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
//header("Pragma: no-cache");
//header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Datum in der Vergangenheit
//header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
//header("Cache-Control: no-store, no-cache, must-revalidate");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");
//error_reporting(0);
error_reporting(E_ALL ^ E_NOTICE);
// Zeitzone setzten , damit die Stunden richtig ausgerechnet werden
date_default_timezone_set("Europe/Paris");
@setlocale(LC_TIME, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de-DE', 'de', 'ge', 'de_DE.UTF-8', 'German');  
//Memory - ab ca. 15 Usern auf 32 stellen, ab 30 auf 64 und ab 60 auf 128M usw.
@ini_set('memory_limit', '32M');
header("Content-Type: text/html; charset=iso-8859-1"); 
// Microtime für die Seitenanzeige (Geschwindigkeit des Seitenaufbaus)
$_start_time = explode(" ",microtime());
$_start_time = $_start_time[1] + $_start_time[0];
// ----------------------------------------------------------------------------
// F5 verhindern dass daten zwei mal gespeichert werden kann
// ----------------------------------------------------------------------------
//$_write = true;         // Daten werden dann  gespeichert
$_now = $_GET['token'];
$token = md5(uniqid('SmallTime'));
//echo  $_SESSION['last'] . " - " . $_now ;
//echo "<br>";
if(trim($_SESSION['last'])== trim($_now ) and isset($_SESSION['last'])){
	//echo "Speichern erlaubt";
	$_write = true;
}else{
	//echo "token sind identisch, speichern nicht erlaubt";
	$_write = false;
}
$_SESSION['last'] = $token;
/*
$_SESSION['now'] = $_now;
//echo  $_SESSION['now'];
//echo "<br>";
if(!isset($_SESSION['last'])) {
$_SESSION['last']= $_SESSION['now'];

}
echo "Formtoken = ". $_SESSION['now'] . "<br>";
echo "Session = ". $_SESSION['last'] . "<br>";

echo  $_SESSION['last'] . " - " . $_SESSION['now'] ;
echo "<br>";
$_SESSION['last'] = $_SESSION['now'];

echo "write = ".$_write."<hr>";*/
// ----------------------------------------------------------------------------
// Debugg - Ionformationen
// ----------------------------------------------------------------------------
// 0 = Session - Daten anzeigen
// 1 = alle Daten - Array werden angezeigt
// 2 = Controller - Informationen
// 3 = USER - Daten anzeigen
// 4 = TIME - Daten anzeigen
// 5 = Monat - Daten anzeigen
// 6 = Jahres - Daten anzeigen
// 7 = Template - Daten anzeigen (Gruppen????)
// 8 = Settings - Daten anzeigen
// 9 = ALLE VARIABLEN AUSGEBEN
// 11 = GET und POST anzeigen
// 12 = absenz anzeigen
// 13 = feiertage anzeigen
// eingabe : array(1,2,9)
$show = array();
if(!empty($_GET['debug']) and $_SESSION['admin']) $show = array($_GET['debug']); 
// ----------------------------------------------------------------------------
// DEKLARATION DER VARIABLEN
// ----------------------------------------------------------------------------
//include ('./include/time_variablen_deklaration.php');

global $_modal;
$_modal = (isset($_GET['modal']) == true ? true : false);


// ----------------------------------------------------------------------------
// Modler laden
// ----------------------------------------------------------------------------
define('FPDF_INSTALLDIR', './fpdf');
if(!defined('FPDF_FONTPATH')) define('FPDF_FONTPATH', FPDF_INSTALLDIR.'/font/');
include_once(FPDF_INSTALLDIR.'/fpdf.php');	
//include_once ('./include/class_controller.php');
include_once ('./include/class_absenz.php');
include_once ('./include/class_auszahlung.php');
include_once ('./include/class_user.php');
include_once ('./include/class_group.php');
include_once ('./include/class_login.php');
include_once ('./include/class_template.php');
include_once ('./include/class_time.php');
include_once ('./include/class_month.php');
include_once ('./include/class_personalblatt.php');
include_once ('./include/class_jahr.php');
include_once ('./include/class_feiertage.php');
include_once ('./include/class_filehandle.php');
include_once ('./include/class_rapport.php');
include_once ('./include/class_show.php');
include_once ('./include/class_settings.php');
require_once	('./include/class_table.php');
include ("./include/time_funktionen.php");
//$controller = new time_controller();
// ----------------------------------------------------------------------------
// MGET und POST Daten anzeigen
// ----------------------------------------------------------------------------
if(in_array(11,$show)){
	echo "GET : ";
	$zeig = new time_show($_GET);
	echo "POST : ";
	$zeig = new time_show($_POST);
}
// ----------------------------------------------------------------------------
// Modler allgemeine Daten laden
// ----------------------------------------------------------------------------
$_users         	= new time_filehandle("./Data/","users.txt",";");
$_groups         	= new time_filehandle("./Data/","group.txt",";");
//$_absenz        	= new time_filehandle("./Data/","absenz.txt",";");
$_settings         	= new time_settings();
if(in_array(8,$show)){
	txt("Settings - Daten füllen und anzeigen : \$_settings");
	showClassVar($_settings);
	txt("<hr color=red>");
}
$_template          = new time_template("index.php");
$_template->_user01 = "sites_admin/admin01.php";
$_template->_user02 = "sites_login/login_mehr_02.php";
$_template->_user04 = "sites_login/login_mehr_04.php";
$_template->_user03 = "sites_admin/admin03.php";
$_template->set_portal(0);
//include ('./include/setting.php');
//echo $_template->get_template();
$_favicon = "./images/favicon_admin.ico";
// ----------------------------------------------------------------------------
// Session - Variablen anzeigen
// ----------------------------------------------------------------------------
if(in_array(0,$show)){
	txt("Session und Cookie - Daten:");
	echo "\$_SESSION['admin'] : ". $_SESSION['admin'] . "<br>";
	echo "\$_SESSION['id']".$_SESSION['id']."<br>";
	echo "\$_SESSION['datenpfad']".$_SESSION['datenpfad']."<br>";
	echo "\$_SESSION['username']".$_SESSION['username']."<br>";
	echo "\$_SESSION['passwort']".$_SESSION['passwort']."<br>";
	echo "\$_SESSION['login']".$_SESSION['login']."<br>";
	echo "\$_COOKIE['lpass']".$_COOKIE["lpass"]."<br>";
	echo "\$_COOKIE['lname']".$_COOKIE["lname"]."<br>";
}
// ----------------------------------------------------------------------------
// Controller für Login
// ----------------------------------------------------------------------------
$_logcheck = new time_login();
$_logcheck->_admins = true; //Nur Admins dürfen sich einloggen (ID = 0 oder Pos. 3 ein 1 oder in der ersten Gruppe die nicht angezeigt wird in der Gruppenansicht)
// ----------------------------------------------------------------------------
// Sicherheitsüberprüfung, gehört die Session zu einem Admin 
// (falls bei index.php eingeloggt, existiert eine Session)
// ----------------------------------------------------------------------------
//echo "<hr>";
$_logcheck->checkadmin( $_users->_array);
//echo "<hr>";
// ----------------------------------------------------------------------------
// keine Session vorhanden
if($_SESSION['admin']==NULL OR $_SESSION['admin']==""){
	if(in_array(2,$show)) txt("keine Session, Login durchführen");
	$_Userpfad = $_SESSION['admin']."/";
	//$_action = "";
}
// Login über Cookie mit Datenüberprüfung
if($_COOKIE["lname"] and $_COOKIE["lpass"] and ($_SESSION['admin']==NULL OR $_SESSION['admin']=="")){
	if(in_array(2,$show)) txt("Cookie gesetzt - Autologin prüfen");
	$_logcheck->login($_POST, $_users->_array);
	//$_action = "";
}
// Loginformular - Datenüberprüfung
if($_POST['login']){
	if(in_array(2,$show)) txt("Formular - Login geklickt");
	$_logcheck->login($_POST, $_users->_array);
	//$_action = "";
}
if($_GET['action']=="logout"){
	if(in_array(2,$show)) txt("Formular - Logout geklickt");
	$_logcheck->logout();
	//$_action = "";
	header("Location: admin.php");
	exit();
}
if(in_array(2,$show)) showClassVar($_logcheck);
// ----------------------------------------------------------------------------
// Controller für Action
// ----------------------------------------------------------------------------
// Session  vorhanden - Daten anzeigen
if($_SESSION['admin'] and !$_GET['action']){
	if(in_array(2,$show)){
		txt("Session vorhanden - normale Anzeige");
		txt("User: ". $_SESSION['admin']);
	}
	$_action = "show_admin";
	//$_logcheck->login($_POST, $_users->_array);
}elseif($_GET['action'] && $_SESSION['admin']){
	$_action = $_GET['action'];
	$_grpwahl = $_GET['group']-1;
	if(array_search(2,$show)) txt("GET_Action gewählt : ". $_action);
}elseif($_GET['group']){
	if(in_array(2,$show)) txt("GET Group gewählt : ". $_GET['group']);
	$_grpwahl = $_GET['group']-1;
	$_action = "login_mehr";
	if($_GET['group']=="-1"){
		$_action = "login_einzel";
	}
}elseif($_settings->_array[19][1]=="1"){
	//if (in_array(2,$show)) txt("Mehrbenutzersystem aktiviert : ". $_settings->_array[19][1]);
	//Falls Mehrbenutzersystem eingestellt wurde
	//$_action = "login_mehr"; //bei Admin kein Gruppen - Login
}
// ----------------------------------------------------------------------------
// Modler Userdaten laden
// ----------------------------------------------------------------------------
if($_SESSION['admin']){
	include ('./include/time_variablen_laden.php');
	$_template->_plugin	= "modules/sites_plugin/plugin.php";
}
// ----------------------------------------------------------------------------
// Controller Templatedarstellung
// ----------------------------------------------------------------------------
if(in_array(2,$show)) txt("SWITCH von \$_action = ". $_action);
//Design = Mobile, dann AJAX aktivieren
//if (stristr($_template->get_templatepfad(),"mobile")) {
//	$_action="mobile";
//}
switch($_action){
	case "mobile":
	//echo "spezial";
	break;
	case "edit_ausz":
		//echo "Auszahlung editieren";
		$auszahlung = new auszahlung($_GET['monat'],$_GET['jahr']);
		$_template->_user04 = "sites_admin/admin04_auszahlung.php";
	break;
	case "update_ausz":
		//echo "Auszahlung speichern";
		$auszahlung = new auszahlung($_GET['monat'],$_GET['jahr']);
		$auszahlung->save_auszahlung($_POST['anzahl']);		
		$_infotext = getinfotext("Jahres&uuml;bersicht"  ,"td_background_top");
		$_template->_user02 = "sites_year/sites02_year.php";
		$_template->_user04 = "sites_year/sites04_year.php";
		break;
	case "plugins":
	if($_POST['plugin']){
		$_SESSION['plugin'] = $_POST['plugin'];
	}
	if($_POST['plugin']=="zeiterfassung") header("Location: admin.php");
	$_infotext_org = getinfotext("<b>Plugins werden geladen</b> : ".$_SESSION['plugin'] . " wird geladen.","td_background_top");
	$_template->_modulpfad = "plugins/";
	include("plugins/".$_SESSION['plugin']."/index.php");
	break;
	case "idtime-generate":
	$_infotext = getinfotext("<b>QR-Codes/URLs/IDs zum direkten Stempeln via Barcode-Scanner(-App):</b>"  ,"td_background_top");
	$_template->_user02 = "sites_admin/admin02.php";
	$_template->_user04 = "sites_admin/admin04_idtime_generate.php";
	break;
	case "zip_user":
	$_infotext = getinfotext( "ZIP-Archiv vom Mitarbeiter" ,"td_background_top");
	$_template->_user02 = "sites_admin/admin02.php";
	$_template->_user04 = "sites_zip/sites04_zip_user.php";
	break;
	case "debug_info":
	$_infotext = getinfotext("Status - Meldungen"  ,"td_background_top");
	$_template->_user02 = "sites_admin/admin02.php";
	$_template->_user04 = "sites_debug/admin04_debuginfo.php";
	break;
	case "show_year2":
		//include("./include/import_csv.php");
		$_infotext = getinfotext("Jahres&uuml;bersicht Detaills"  ,"td_background_top");
		//$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user02 = "sites_year/sites02_year.php";
		$_template->_user04 = "sites_year/sites04_year.php";
		break;
	case "show_year":
		$auszahlung = new auszahlung(1,2000);
		$_infotext = getinfotext("Jahres&uuml;bersicht"  ,"td_background_top");
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_year/user04_year.php";
		break;
	case "delete_user":
		if($_POST['absenden'] == "OK"){
			$id = $_GET['delete_user_id'];
			$_infotext04 = $_users->delete_user($id, $_users->_array[$id][0]);
			header("Location: admin.php?action=delete_user&show=delete");		
		}elseif($_POST['absenden'] == "CANCEL"){
			$_infotext = getinfotext( "User wurde nicht gelöscht."  ,"td_background_heute"); 
			$_template->_user02 = "sites_admin/admin02.php";
			$_template->_user04 = "sites_admin/admin04.php";
		}elseif(@$_GET['show']=="delete"){
			$_infotext = getinfotext("User wurde gelöscht."  ,"td_background_heute");
			$_infotext04 = "";	
			$_infotext04 .= "<br><br>User wurde etfernt und die Dateien verschoben nach ./Data/_del_".date("Y.n.d")."_XXXXXXX!";
			$_infotext04 .= "<br> Sichen Sie bitte das Verzeichniss und l&ouml;schen Sie es.";
			$_infotext04 .=  "<br>Falls einmal ein gleicher Benutzer erstellt und dieser wieder gel&ouml;scht wird k&ouml;nnte es zu einer Fehlermeldung kommen.";
			$_template->_user02 = "sites_admin/admin02.php";
			$_template->_user04 = "sites_admin/admin04.php";		
		}else{
			$_infotext = getinfotext("User löschen?"  ,"td_background_heute");
			$_template->_user02 = "sites_admin/admin02.php";
			$_template->_user04 = "sites_admin/admin04_user_del.php";
		}			
		break;
	case "import":
		//include("./include/import_csv.php");
		$_infotext = getinfotext("CSV - Import (z.B.IPhone APP TimeOrg - timeorg.zimco.com)"  ,"td_background_top");		
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_admin/admin04_csv_import.php";
		break;
	case "anwesend":
		if(in_array(2,$show)) txt("Anwesenheitsliste");
		if($_grpwahl==0) $_grpwahl = 1;
		$_group = new time_group($_grpwahl);
		if($id) $_grpwahl = $_group->get_usergroup($id);
		//$_template->_user02 = "sites_login/login_mehr_02.php";
		//$_template->_user04 = "sites_login/login_mehr_04.php";
		//$_template->_user03 = "sites_user/user03_stat.php";
		break;
		case "login_mehr":
		if(in_array(2,$show)) txt("Mehrbenutzer - Login");
		//echo "<hr> login, daten schreiben, logout",
		//if ($_SESSION['admin'] ) echo "<hr>  erfolgreich eingeloggt";
		if($_POST['login'] == "Stempelzeit eintragen" and $_write){
			$_logcheck->login($_POST, $_users->_array);
			//echo "<hr>" . $_SESSION['admin'];
			if($_SESSION['admin']){
				$id = $_logcheck->_id;
				//echo "<hr>";
				//echo $id;
				//echo "<hr>";
				// Fehlerhandling bei F5 und dann sendenklick
				if($_POST['_n']<>"" and $_POST['_p']<>""){
					$_time->set_timestamp(time());
					$_time->save_time(time(), $_user->_ordnerpfad);
				}
			}
			$_logcheck->logout();
		}
		//if($_grpwahl==0) $_grpwahl = 1;
		//$_group = new time_group($_grpwahl);
		//echo " - ist in Gruppe : " . $_group->get_usergroup($id);
		//if($id) $_grpwahl = $_group->get_usergroup($id);
		//echo "<hr> Logout";
		$_template->_user01 = "sites_time/null.php";
		$_template->_user02 = "sites_login/login_mehr_02.php";
		$_template->_user03 = "sites_login/login_mehr_03.php";
		$_template->_user04 = "sites_login/login_mehr_04.php";
		break;
	case "login_einzel":
	if(in_array(2,$show)) txt("Einzel - Login");
	$_template->_user01 = "sites_time/null.php";
	$_template->_user02 = "sites_login/login_einzel_02.php";
	if($_GET['group']=="-1") $_template->_user03 = "login_einzel_03.php";
	$_template->_user04 = "sites_login/login_einzel_04.php";
	break;
	case "login":
	if(in_array(2,$show)) txt("Login - Check");
	$_logcheck = new time_login($_POST, $_users->_array);
	break;
	case "logout":
	if(in_array(2,$show)) txt("Logout und Formular anzeigen");
	//$_SESSION['admin']=NULL;
	//session_destroy();
	//setcookie("lname","",time()-1);
	//setcookie("lpass","",time()-1);
	$_logcheck->logout();
	$_grpwahl = 1;
	$_group = new time_group($_grpwahl);
	setLoginForm();
	break;
	case "anwesend":
	if(in_array(2,$show)) txt("Anwesenheitsliste");
	break;
	case "add_rapport":
	$_rapport = new time_rapport();
	$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user04 = "sites_time/rapport_add_04.php";
	break;
	case "insert_rapport":
	$_rapport = new time_rapport();
	if($_POST['absenden'] == "UPDATE" and $_write){
		$_rapport->insert_rapport($_user->_ordnerpfad, $_time->_timestamp);
	}elseif($_POST['absenden'] == "DELETE" and $_write){
		$_rapport->delete_rapport($_user->_ordnerpfad, $_time->_timestamp);
	}
	$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user04 = "sites_user/admin04_timetable.php";
	break;
	case "add_absenz":
	$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user04 = "sites_time/absenz_add_04.php";
	break;
	case "insert_absenz":
	if($_POST['absenden'] == "OK" and $_write){
		$_absenz->insert_absenz($_user->_ordnerpfad, $_time->_jahr);
	}
	$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user04 = "sites_user/admin04_timetable.php";
	break;
	case "delete_absenz":
	$_absenz->delete_absenz($_user->_ordnerpfad, $_time->_jahr);
	$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user04 = "sites_user/admin04_timetable.php";
	break;
	case "edit_time":
	if(in_array(2,$show)) txt("Zeit editieren - Formular");
	$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user04 = "sites_time/time_edit_04.php";
	break;
	case "update_time":
	$_oldtime = $_GET['timestamp'];
	$_newtime = $_time->mktime($_POST['_w_stunde'],$_POST['_w_minute'],0,$_POST['_w_monat'], $_POST['_w_tag'],$_POST['_w_jahr']);
	//echo "<hr> \$_write = " . $_write;
	if($_POST['absenden'] == "UPDATE" and $_write){
		if(in_array(2,$show)) txt("Zeit updaten : ". $_oldtime);
		// update oldtime, newtime, Ordner
		$_time->update_stempelzeit($_oldtime, $_newtime, $_user->_ordnerpfad);
	}elseif($_POST['absenden'] == "DELETE" and $_write){
		if(in_array(2,$show)) txt("Zeit löschen :".$_oldtime);
		// delete //oldtime, Ordner
		$_time->delete_stempelzeit($_oldtime, $_user->_ordnerpfad);
	}else{
		if(in_array(2,$show)) txt("Zeit updaten und löschen fehlgeschlagen");
	}
	$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user04 = "sites_user/admin04_timetable.php";
	break;
	case "insert_time_list":
	if($_POST['absenden'] == "OK" and $_write){
		$_timestamp                = $_GET['timestamp'];
		$_w_tag                        = $_POST['_w_tag'];
		$_w_monat                = $_POST['_w_monat'];
		$_w_jahr                = $_POST['_w_jahr'];
		$_zeitliste                = $_POST['_zeitliste'];
		$_w_sekunde                = 0;
		$_zeitliste = trim($_zeitliste);
		$_zeitliste = str_replace(" ", "", $_zeitliste);
		$_zeitliste = str_replace(" ", "", $_zeitliste);
		$_zeitliste = str_replace(" ", "", $_zeitliste);
		$_zeitliste = explode("-",$_zeitliste);
		$_temptext = "";
		foreach($_zeitliste as $_zeiten){
			//$_zeiten = str($_zeiten);
			//if(strstr(":",$_zeiten)){
			$_tmp = explode(".",$_zeiten);
			$_w_stunde = $_tmp[0];
			$_w_minute = $_tmp[1];
			if($_w_minute=="")$_w_minute=0;

			$tmp = $_time->mktime($_w_stunde,$_w_minute,0,$_w_monat, $_w_tag,$_w_jahr);
			//} else {
			//        $_w_stunde = $_zeiten;
			//        $_w_minute = 0;
			//}
			//$_temptext = $_temptext . $_w_stunde. "." . $_w_minute . "#";
			//echo $tmp;
			$_time->save_time($tmp, $_user->_ordnerpfad);
			//$_time->save_time_list($_user->_ordnerpfad);
		}
		//$_temptext = $_zeitliste[0]. " bis ". $_zeitliste[1];
		//echo "Variablen ".$_timestamp ." / ". $_w_tag ." / ".$_w_monat ." / ". $_w_jahr." / ". $_temptext ." / ".$_w_sekunde;
	}
	$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user04 = "sites_user/admin04_timetable.php";
	break;
	case "insert_time":
	if(in_array(2,$show)) txt("Zeit speichern");
	if($_POST['absenden'] == "OK" and $_write){
		$tmp = $_time->mktime($_POST['_w_stunde'],$_POST['_w_minute'],0,$_POST['_w_monat'], $_POST['_w_tag'],$_POST['_w_jahr']);
		$_time->set_timestamp($tmp);
		$_time->save_time($tmp, $_user->_ordnerpfad);
	}
	$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user04 = "sites_user/admin04_timetable.php";
	break;
	case "quick_time":
		if(in_array(2,$show)) txt("Quick Time wird gestempelt");
		$_time->set_runden((int) $_settings->_array[25][1]);	
		$_time->save_quicktime($_user->_ordnerpfad);
		$_template->_user02 = "sites_admin/admin02_user_cal.php";
		$_template->_user04 = "sites_user/admin04_timetable.php";
		header("Location: admin.php");
		break;
	case "add_time":
	if(in_array(2,$show)) txt("Zeit eintragen - Formular");
	$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user04 = "sites_time/time_add_04.php";
	break;
	case "add_time_list":
	if(in_array(2,$show)) txt("Zeit eintragen - Formular");
	$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user04 = "sites_time/time_addlist_04.php";
	break;
	case "show_time":
	if(in_array(2,$show)) txt("User - Anzeige seiner Daten");			
	$_template->_user01 = "sites_admin/admin01.php";
	$_template->_user03 = "sites_admin/admin03.php";
	$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user04 = "sites_user/admin04_timetable.php";
	//$_template->_user03 = "user03_stat.php";
	break;
	case "show_pdf":
	$_infotext = getinfotext( "PDF anzeigen" ,"td_background_top");
	if(in_array(2,$show)) txt("PDF - Anzeigen");
	//$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user02 = "sites_admin/admin02.php";
	$_template->_user04 = "sites_user/user04_pdf.php";
	break;
	case "show_jear":
	if(in_array(2,$show)) txt("Jahresübersicht - Anzeigen");
	$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user04 = "sites_user/user04_jear.php";
	break;
	case "print_month":
	if(in_array(2,$show)) txt("PDF - Drucken");
	include ("./include/time_funktion_pdf.php");
	check_htaccess_pdf($_user->_ordnerpfad);
	$_print = $_GET['print'];
	$_druck = $_print;
	$_jahr = date("Y", time());
	$_monat = date("n", time());
	$_tag = date("j", time());
	if($_druck){
		erstelle_pdf_more($_MonatsArray);
	}else{
		erstelle_neu(0); 
	}
	$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user04 = "sites_user/user04_pdf_show.php";
	break;
	case "design":
	$_infotext = getinfotext("Design auswählen"  ,"td_background_top");
	$_template->_user02 = "sites_admin/admin02.php";
	$_template->_user04 = "sites_user/user04_design.php";
	break;
	case "setdesign":
		$_infotext = getinfotext( "<table><tr><td><img src='images/icons/error.png' border=0></td><td>Neues Design gewählt</td></tr></table>" ,"td_background_heute");
		$_template         = new time_template("index.php");
		$_template->set_templatepfad($_GET['designname']);	
		$_template->_plugin	= "modules/sites_plugin/plugin.php";
		$_template->_user01 = "sites_admin/admin01.php";
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user03 = "sites_admin/admin03.php";
		$_template->_user04 = "sites_user/user04_design.php";
		header('Location: admin.php?action=design');	
	break;
	case "show_admin":
	$_template->_user01 = "sites_admin/admin01.php";
	$_template->_user02 = "sites_login/login_mehr_02.php";
	$_template->_user04 = "sites_login/login_mehr_04.php";
	$_template->_user03 = "sites_admin/admin03.php";
	break;
	case "user_einstellungen":
	$_infotext = getinfotext( "Userdaten editieren" ,"td_background_top");
	$_template->_user02 = "sites_admin/admin02.php";
	$_template->_user04 = "sites_admin/admin04_user_einstellungen.php";
	break;
	case "user_einstellungen_update":
	$_a = $_POST['_a'];
	$_b = $_POST['_b'];
	$_c = $_POST['_c'];
	$_d = $_POST['_d'];
	$_user->set_user_data($_id,$_a,$_b,$_c,$_d);
	$_template->_user02 = "sites_admin/admin02_user_cal.php";
	$_template->_user04 = "sites_user/admin04_timetable.php";
	//$_template->_user04 = "sites_admin/admin04_user_einstellungen.php";
	break;
	case "user_edit":
	$_infotext = getinfotext("User editieren"  ,"td_background_top");
	$_template->_user02 = "sites_admin/admin02.php";
	$_template->_user04 = "sites_admin/admin04_user_edit.php";
	break;
	case "user_update":
	if($_POST['absenden'] == "OK"){
		$_user->set_user_details();
	}
	$_infotext = getinfotext( "<table><tr><td><img src='images/icons/error.png' border=0></td><td>Userdaten wurden aktualisiert</td></tr></table>" ,"td_background_heute");
	$_template->_user02 = "sites_admin/admin02.php";
	//$_template->_user02 = "sites_admin/admin02_user_cal.php";
	//$_template->_user04 = "sites_user/admin04_timetable.php";
	$_template->_user04 = "sites_admin/admin04_user_edit.php";
	break;
	case "user_edit_absenzen":
	$_infotext = getinfotext("Absenzen editieren"  ,"td_background_top");
	$_template->_user02 = "sites_admin/admin02.php";
	$_template->_user04 = "sites_admin/admin04_user_editabsenzen.php";
	break;
	case "user_update_absenzen":
	if($_POST['absenden'] == "OK"){
		$_user->set_user_absenzen();
		$_user->load_data_session();		
	}
	$_infotext = getinfotext( "<table><tr><td><img src='images/icons/error.png' border=0></td><td>Absenzen wurde aktualisiert</td></tr></table>" ,"td_background_heute");
	$_template->_user02 = "sites_admin/admin02.php";
	$_template->_user04 = "sites_admin/admin04_user_editabsenzen.php";
	break;
	case "user_personalkarte":
	if(in_array(2,$show)) txt("Personalkarte wird dargestellt");
	if($_POST['update']){
		$_infotext = getinfotext("<img src='images/icons/error.png' border=0> Personalkarte von ". $_user->_name ." wurde aktualisiert","td_background_heute");
	}else{
		$_infotext = getinfotext("Personalkarte von ". $_user->_name,"td_background_top");
	}
	$_personaldaten = new time_personalblatt();
				
	$_template->_user02 = "sites_admin/admin02.php";
	$_template->_user04 = "sites_admin/admin04_personalkarte.php";
	//if (in_array(2,$show)) txt("Quick Time wird gestempelt");
	//$_nr	= $_GET['nr'];
	//$_time->save_quicktime($_user->_ordnerpfad);
	//$_template->_user02 = "sites_admin/admin02_user_cal.php";
	//$_template->_user04 = "sites_user/admin04_timetable.php";
	break;
	case "group";
	$_infotext = getinfotext("Gruppen editieren"  ,"td_background_top");
	$_group = new time_group(-1);
	//-----------------------------------------------
	//löschen einer Gruppe
	//-----------------------------------------------
	if($_GET['del']<>""){
		$_infotext = getinfotext(  "<table><tr><td><img src='images/icons/error.png' border=0></td><td>Gruppe gelöscht</td></tr></table>" ,"td_background_heute");
		$_group->del_group($_GET['del']);	
	}
	//-----------------------------------------------
	//aktualisieren oder Gruppen hinzufügen
	//-----------------------------------------------	
	if($_POST['senden']){
		$_infotext = getinfotext( "<table><tr><td><img src='images/icons/error.png' border=0></td><td>Gruppen gespeichert</td></tr></table>" ,"td_background_heute");
		$_group->save_group();
	}
	$_template->_user02 = "sites_admin/admin02.php";
	$_template->_user04 = "sites_admin/admin04_group_edit.php";
	break;
	case "settings";
	$_infotext = "Settings editieren";
	$_infotext = getinfotext( $_infotext ,"td_background_top");	
	if($_POST['senden']){
		$_infotext = getinfotext("<table><tr><td><img src='images/icons/error.png' border=0></td><td>Neue Settings gespeichert</td></tr></table>"  ,"td_background_heute");
		$_settings->save_settings();
	}
	$_template->_user02 = "sites_admin/admin02.php";
	$_template->_user04 = "sites_admin/admin04_settings_edit.php";
	break;
	case "feiertage";
	$_infotext = getinfotext( "Individuelle Feiertage mit einem festen Datum"  ,"td_background_top");
	$_feiertage = new time_feiertage($_time->_jahr, $_settings->_array[12][1], $_user->_feiertage);
	if(in_array(13,$show)){
		txt("Feiertage - anzeigen : \$_feiertage");
		showClassVar($_absenz);
		txt("<hr color=red>");
	}
	if($_POST['senden']){
		$_infotext = getinfotext( "<table><tr><td><img src='images/icons/error.png' border=0></td><td>Feiertage gespeichert</td></tr></table>" ,"td_background_heute");
		$_feiertage->save_feiertage();
	}elseif($_GET['del']<>""){
		$_infotext = getinfotext("<table><tr><td><img src='images/icons/error.png' border=0></td><td>Feiertag gelöscht</td></tr></table>"  ,"td_background_heute");
		$_feiertage->delete_feiertag($_GET['del']);
	}
	$_template->_user02 = "sites_admin/admin02.php";
	$_template->_user04 = "sites_admin/admin04_feiertage_edit.php";
	break;
	case "user_add":
	if($_POST['absenden'] == "OK"){
		//echo "update";
		$_a    = $_POST['_a'];
		$_b    = $_POST['_b'];
		$_c    = sha1($_POST['_c']);
		if($_a<>"" && $_b<>"" && $_POST['_c']<>""){
			//echo "schreibe";
					
			//$_infotext =  $_users->user_exist($_a);
			//$_users->insert_line($_a.";".$_b.";".$_c);
			//$_users->add_user($_a);
			if($_users->user_exist($_a)){
				$_infotext = "<table><tr><td><img src='images/icons/error.png' border=0></td><td><font color=red>Mitarbeiter <b>".$_a."</b> existiert bereits!</font></td></tr></table>";
				$_infotext = getinfotext( $_infotext ,"td_background_heute");	
			}else{
				$_users->insert_line($_a.";".$_b.";".$_c);
				$_users->add_user($_a);
				header("Location: admin.php?action=user_edit&admin_id=". $_users->get_anzahl());
						
				$_infotext = "<table><tr><td><img src='images/icons/error.png' border=0></td><td>Mitarbeiter <b>".$_a."</b> wurde erfolgreich erstellt.</td></tr></table>";
				$_infotext = getinfotext( $_infotext ,"td_background_heute");	
				break;	
			}
					
		}else{
			//echo "Daten falsch";
			$_infotext = "<table><tr><td><img src='images/icons/error.png' border=0></td><td>Mitarbeiter konnte NICHT erstellt werden.</td></tr></table>";
			$_infotext = getinfotext( $_infotext ,"td_background_heute");		
		}	
			 	
	}else{
		$_infotext = "Neuer Mitarbeiter erfassen.";	
		$_infotext = getinfotext( $_infotext ,"td_background_top");	
	}
	$_template->_user02 = "sites_admin/admin02.php";
	$_template->_user04 = "sites_admin/admin04_user_add.php";		
	break;
	default:
	if(in_array(2,$show)) txt("Defaultanzeige");
	setLoginForm();
	break;
}
// ----------------------------------------------------------------------------
// Logion - Formular darstellen
// ----------------------------------------------------------------------------
function setLoginForm(){
	global $_template;
	if($_settings->_array[19][1]==0){
		$_template->_user01 = "sites_time/null.php";
		$_template->_user02 = "sites_login/admin_login_einzel_02.php";
		$_template->_user03 = "sites_login/admin_login_einzel_03.php";
		$_template->_user04 = "sites_login/admin_login_einzel_04.php";
	}else{
		$_template->_user01 = "sites_time/null.php";
		$_template->_user02 = "sites_login/login_mehr_02.php";
		$_template->_user03 = "sites_login/login_mehr_03.php";
		$_template->_user04 = "sites_login/login_mehr_04.php";
	}
}


if($_SESSION['admin']){
	// ----------------------------------------------------------------------------
	// Monatsdaten berechnen
	// ----------------------------------------------------------------------------
	//define('user2','<hr>--------------------------------------------------------bla<hr>');
	//echo user2;
	$_monat         = new time_month( $_settings->_array[12][1] , $_time->_letzterTag, $_user->_ordnerpfad, $_time->_jahr, $_time->_monat, $_user->_arbeitstage, $_user->_feiertage, $_user->_SollZeitProTag, $_user->_BeginnDerZeitrechnung, $_settings->_array[21][1],$_settings->_array[22][1],$_settings->_array[27][1]);
	$_monat->_modal = $_template->_modal;

	
	//Falls automatische Pause eingestellt
	if($_settings->_array[21][1] > 0){
		//$_monat->check_autopause($_settings->_array[21][1],$_settings->_array[22][1])
	}
		
	if(in_array(5,$show)){
		txt("Monatsdaten - Daten füllen und anzeigen : \$_monat");
		showClassVar($_monat);
		//txt("Daten : \$_monat->_monate");
		//$zeig = new time_show($_monat->_monate);
		//echo "<hr>";
		//txt("Daten : \$_monat->_wochentag");
		//$zeig = new time_show($_monat->_wochentage);
		txt("Daten : \$_monat->_MonatsArray");
		$zeig = new time_show($_monat->_MonatsArray);
		txt("<hr color=red>");
	}
	// ----------------------------------------------------------------------------
	// Jahresdaten berechnen
	// ----------------------------------------------------------------------------
	// Berechnung Endjahr = aktuelles Jahr, dann 0 sonst $_time->_jahr
	$_jahr = new time_jahr($_user->_ordnerpfad, 0, $_user->_BeginnDerZeitrechnung, $_user->_Stunden_uebertrag, $_user->_Ferienguthaben_uebertrag, $_user->_Ferien_pro_Jahr, $_user->_Vorholzeit_pro_Jahr, $_user->_modell, $_time->_timestamp);
	if(in_array(6,$show)){
		txt("Jahres - Daten füllen und anzeigen : \$_jahr");
		showClassVar($_jahr);
		//txt("Daten : \$_monat->_monate");
		//$zeig = new time_show($_monat->_monate);
		//echo "<hr>";
		//txt("Daten : \$_monat->_wochentag");
		//$zeig = new time_show($_monat->_wochentage);
		txt("Daten : \$_jahr->_data");
		print_r(array_keys($_jahr->_data));
		$zeig = new time_show($_jahr->_data);
		txt("<hr color=red>");
		txt("Daten : \$_jahr->_array");
		$zeig = new time_show($_jahr->_array);
		txt("<hr color=red>");
	}
}
$_copyright = "<div class=copyright>";
//-----------------------------------------------------------------------------
//Seitenladezeit 
//-----------------------------------------------------------------------------
$_time_end = explode(" ",microtime());
$_time_end = $_time_end[1] + $_time_end[0];
// ^^ Jetzt wird wieder die Aktuelle Zeit gemessen
$_zeitmessung = $_time_end - $_start_time;
// ^^ Endzeit minus Startzeit = die Differenz der beiden Zeiten
$_zeitmessung = substr($_zeitmessung,0,4);
//echo "-----------------".$_time_end." - ".$_start_time." = ". $_zeitmessung. " Sekunden";
// ^^ Die Zeit wird auf X Kommastellen gekÃ¼rzt
$_copyright .= "<hr color=#DFDFDF size=1>Ladezeit der Seite: $_zeitmessung Sekunden.<br>";
// ----------------------------------------------------------------------------
// copyright Text
// ----------------------------------------------------------------------------
$_arr = file("./include/Settings/copyright.txt");
$_ver = file("./include/Settings/smalltime.txt");
$_copyright .="";
foreach($_arr as $_zeile){
	$_tmp = str_replace("##ver##",$_ver[0], $_zeile);
	$_copyright .= $_tmp;
}
$_copyright .= "</div>";

// ----------------------------------------------------------------------------
// Anzeige für Entwickler
// ----------------------------------------------------------------------------
include ('./include/_debug_data.php');

// ----------------------------------------------------------------------------
// Viewer - Anzeige der Seite
// ----------------------------------------------------------------------------
//echo "--".$_GET[timestamp] ."--".$_GET[modal] ."---------------------<hr>";
if(isset($_GET[modal])){
	// bei Modal nur DIV04 anzeigen
	include($_template->get_user04()); 
}else{
	include ($_template->get_template());
}
?>