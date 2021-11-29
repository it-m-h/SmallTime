<?php

/********************************************************************************
 * Small Time
/*******************************************************************************
 * Version 0.9.1
 * Author:  IT-Master
 * www.it-master.ch / info@it-master.ch
 * Copyright (c), IT-Master, All rights reserved
 * letzte Änderung: 24.8.2016
 *******************************************************************************/
//Session starten
if (!my_session_start()) {
	session_id(uniqid());
	session_start();
	session_regenerate_id();
}
function my_session_start()
{
	$sn = session_name();
	if (isset($_COOKIE[$sn])) {
		$sessid = $_COOKIE[$sn];
	} else
	if (isset($_GET[$sn])) {
		$sessid = $_GET[$sn];
	} else {
		return session_start();
	}

	if (!preg_match('/^[a-zA-Z0-9,\-]{22,40}$/', $sessid)) {
		return false;
	}
	return session_start();
}
define('DEBUG', false);
if (DEBUG == true) {
	error_reporting(E_ALL);
	//error_reporting(E_ALL ^ E_NOTICE);
	ini_set("display_errors", 1);
} else {
	error_reporting(0);
	ini_set("display_errors", 0);
}
// Zeitzone setzten, damit die Stunden richtig ausgerechnet werden
date_default_timezone_set("Europe/Paris");
@setlocale(LC_TIME, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de-DE', 'de', 'ge', 'de_DE.UTF-8', 'German');
//Memory - ab ca. 15 Usern auf 32 stellen, ab 30 auf 64 und ab 60 auf 128M usw.
@ini_set('memory_limit', '32M');
// Microtime für die Seitenanzeige (Geschwindigkeit des Seitenaufbaus)
$_start_time = explode(" ", microtime());
$_start_time = $_start_time[1] + $_start_time[0];

// ----------------------------------------------------------------------------
// PHP - Version Check - Meldung, falls PHP - version kleiner als 5.3:
// ----------------------------------------------------------------------------
if (version_compare(phpversion(), '5.3', '<')) {
	echo " PHP Version : " . phpversion() . " wird nicht unterst&uuml;tzt. (Version 5.4 oder h&ouml;her wird ben&ouml;tigt)";
}
// ----------------------------------------------------------------------------
// F5 verhindern dass daten zwei mal gespeichert werden kann
// ----------------------------------------------------------------------------
//$_write = true;         // Daten werden dann  gespeichert
if (isset($_GET['token'])) {
	$_now = $_GET['token'];
} else {
	$_now = NULL;
}

$token = md5(uniqid('SmallTime'));
if (trim(@$_SESSION['last']) == trim($_now) and isset($_SESSION['last'])) {
	$_write = true;
} else {
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
if (!defined('FPDF_FONTPATH')) define('FPDF_FONTPATH', FPDF_INSTALLDIR . '/font/');
include_once(FPDF_INSTALLDIR . '/fpdf.php');
include_once('./include/class_absenz.php');
include_once('./include/class_auszahlung.php');
include_once('./include/class_user.php');
include_once('./include/class_group.php');
include_once('./include/class_login.php');
include_once('./include/class_template.php');
include_once('./include/class_time.php');
include_once('./include/class_month.php');
include_once('./include/class_personalblatt.php');
include_once('./include/class_pausen.php');
include_once('./include/class_jahr.php');
include_once('./include/class_feiertage.php');
include_once('./include/class_filehandle.php');
include_once('./include/class_rapport.php');
include_once('./include/class_show.php');
include_once('./include/class_settings.php');
include_once('./include/class_pdfgenerate.php');
require_once('./include/class_table.php');
include("./include/time_funktionen.php");
// ----------------------------------------------------------------------------
// Im Admin - Bereich bis zum gewählten Monat berechnen
// ----------------------------------------------------------------------------
if (isset($_GET['calc']) and $_GET['calc']) {
	$_SESSION['calc'] = $_GET['calc'];
} else {
	$_SESSION['calc'] = true;
}
// ----------------------------------------------------------------------------
// Modler allgemeine Daten laden
// ----------------------------------------------------------------------------
$_users    = new time_filehandle("./Data/", "users.txt", ";");
$_groups   = new time_filehandle("./Data/", "group.txt", ";");
$_settings = new time_settings();
$_template = new time_template("index.php");
$_template->_user01 = "sites_admin/admin01.php";
$_template->_user02 = "sites_login/login_mehr_02.php";
$_template->_user04 = "sites_login/login_mehr_04.php";
$_template->_user03 = "sites_admin/admin03.php";
$_template->set_portal(0);
$_favicon = "./images/favicon_admin.ico";
// ----------------------------------------------------------------------------
// Controller für Login
// ----------------------------------------------------------------------------
$_logcheck = new time_login();
$_logcheck->_admins = true; //Nur Admins dürfen sich einloggen (ID = 0 oder Pos. 3 ein 1 oder in der ersten Gruppe die nicht angezeigt wird in der Gruppenansicht)
// ----------------------------------------------------------------------------
// Sicherheitsüberprüfung, gehört die Session zu einem Admin
// (falls bei index.php eingeloggt, existiert eine Session)
// ----------------------------------------------------------------------------
$_logcheck->checkadmin($_users->_array);
// ----------------------------------------------------------------------------
// falls eine Session exisitert und kein Action
if (@$_SESSION['admin'] and !@$_GET['action']) {
	$_logcheck->rapport(@$_SESSION['admin'], "korrekt", "Session");
}
// keine Session vorhanden
if (@$_SESSION['admin'] == NULL or @$_SESSION['admin'] == "") {
	$_Userpfad = @$_SESSION['admin'] . "/";
}
// Login über Cookie mit Datenüberprüfung
if (@$_COOKIE["lname"] and @$_COOKIE["lpass"] and (@$_SESSION['admin'] == NULL or @$_SESSION['admin'] == "")) {
	$_logcheck->login($_POST, $_users->_array);
}
// Loginformular - Datenüberprüfung
if (isset($_POST['login'])) {
	$_logcheck->login($_POST, $_users->_array);;
}
if (@$_GET['action'] == "logout") {
	$_logcheck->logout();
	header("Location: admin.php");
	exit();
}
// ----------------------------------------------------------------------------
// Controller für Action
// ----------------------------------------------------------------------------
// Session  vorhanden - Daten anzeigen
if (@$_SESSION['admin'] and !@$_GET['action']) {
	$_action = "show_admin";
} elseif (@$_GET['action'] && @$_SESSION['admin']) {
	$_action = @$_GET['action'];
	$_grpwahl = @$_GET['group'] - 1;
	//$_grpwahl = $_GET['group'] - 1;
} elseif (@$_GET['group']) {
	$_grpwahl = $_GET['group'] - 1;
	$_action  = "login_mehr";
	if (@$_GET['group'] == "-1") {
		$_action = "login_einzel";
	}
} elseif ($_settings->_array[19][1] == "1") {
	// Mehrbenutzersystem aktiviert wenn $_settings->_array[19][1])
}
// ----------------------------------------------------------------------------
// Modler Userdaten laden
// ----------------------------------------------------------------------------
if (@$_SESSION['admin']) {
	// ----------------------------------------------------------------------------
	// DEKLARATION DER VARIABLEN
	// ----------------------------------------------------------------------------
	include('./include/time_variablen_laden.php');
	$_template->_plugin = "modules/sites_plugin/plugin.php";
}
// ----------------------------------------------------------------------------
// Controller Templatedarstellung
// ----------------------------------------------------------------------------
switch (@$_action) {
	case "pdfgenerate":
		if (isset($_POST['jahr']) && isset($_POST['monat'])) {
			$_pdfgenerate = new pdfgenerate($_POST['monat'], $_POST['jahr'], $_users);
		} else {
			$_jahr        = date("Y", time());
			$_monat       = date("m", time());
			$_pdfgenerate = new pdfgenerate($_monat, $_jahr, $_users);
		}
		if (isset($_GET['function'])) {
			$_template->_ajaxhtml = $_pdfgenerate->output();
		} else {
			$_infotext = getinfotext("PDF für alle Mitarbeiter anzeigen und erstellen", "td_background_top");
			$_template->_user01 = "sites_admin/admin01.php";
			$_template->_user02 = "sites_admin/admin02.php";
			$_template->_user04 = "sites_admin/admin04_pdfgenerate.php";
			$_template->_user03 = "sites_admin/admin03.php";
		}
		break;
	case "edit_ausz":
		$auszahlung = new auszahlung($_GET['monat'], $_GET['jahr']);
		$_template->_user04 = "sites_admin/admin04_auszahlung.php";
		break;
	case "update_ausz":
		$auszahlung = new auszahlung($_GET['monat'], $_GET['jahr']);
		$auszahlung->save_auszahlung($_POST['anzahl']);
		$_infotext  = getinfotext("Jahres&uuml;bersicht", "td_background_top");
		$_template->_user02 = "sites_year/sites02_year.php";
		$_template->_user04 = "sites_year/sites04_year.php";
		break;
	case "plugins":
		if (@$_POST['plugin']) {
			$_SESSION['plugin'] = $_POST['plugin'];
		}
		if ($_POST['plugin'] == "zeiterfassung") header("Location: admin.php");
		$_infotext_org = getinfotext("<b>Plugins werden geladen</b> : " . $_SESSION['plugin'] . " wird geladen.", "td_background_top");
		if (isset($_GET['excel'])) {
			$_datei = $_GET['excel'];
			$_datei = $_datei . "-" . $_time->_jahr;
			$_datei = $_datei . "-" . $_time->_monat;
			$_datei = $_datei . ".xls";
			$_call  = $_GET['excel'];
			$_template->_user04 = "sites_admin/export_xls_" . $_call . ".php";
		} else {
			$_template->_modulpfad = "plugins/";
			include("plugins/" . $_SESSION['plugin'] . "/index.php");
		}
		break;
	case "idtime-generate":
		$_infotext = getinfotext("<b>QR-Codes/URLs/IDs zum direkten Stempeln via Barcode-Scanner(-App):</b>", "td_background_top");
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_admin/admin04_idtime_generate.php";
		break;
	case "zip_user":
		$_infotext = getinfotext("ZIP-Archiv vom Mitarbeiter", "td_background_top");
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_zip/sites04_zip_user.php";
		break;
	case "debug_info":
		$_infotext = getinfotext("Status - Meldungen", "td_background_top");
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_debug/admin04_debuginfo.php";
		break;
	case "show_year2":
		$show_user_only = true;
		$_infotext = getinfotext("Jahres&uuml;bersicht Detaills", "td_background_top");
		$_template->_user02 = "sites_year/sites02_year.php";
		$_template->_user03 = "sites_admin/admin03.php";
		$_template->_user04 = "sites_year/sites04_year.php";
		break;
	case "show_year":
		$auszahlung = new auszahlung(1, 2000);
		$_infotext  = getinfotext("Jahres&uuml;bersicht", "td_background_top");
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_year/user04_year.php";
		break;
	case "delete_user":
		if (@$_POST['absenden'] == "OK") {
			$id          = $_GET['delete_user_id'];
			$_infotext04 = $_users->delete_user($id, $_users->_array[$id][0]);
			header("Location: admin.php?action=delete_user&show=delete");
		} elseif ($_POST['absenden'] == "CANCEL") {
			$_infotext = getinfotext("User wurde nicht gel&ouml;scht.", "td_background_heute");
			$_template->_user02 = "sites_admin/admin02.php";
			$_template->_user04 = "sites_admin/admin04.php";
		} elseif (@$_GET['show'] == "delete") {
			$_infotext   = getinfotext("User wurde gel&ouml;scht.", "td_background_heute");
			$_infotext04 = "";
			$_infotext04 .= "<br><br>User wurde etfernt und die Dateien verschoben nach ./Data/_del_" . date("Y.n.d") . "_XXXXXXX!";
			$_infotext04 .= "<br> Sichen Sie bitte das Verzeichniss und l&ouml;schen Sie es.";
			$_infotext04 .= "<br>Falls einmal ein gleicher Benutzer erstellt und dieser wieder gel&ouml;scht wird k&ouml;nnte es zu einer Fehlermeldung kommen.";
			$_template->_user02 = "sites_admin/admin02.php";
			$_template->_user04 = "sites_admin/admin04.php";
		} else {
			$_infotext = getinfotext("User l&ouml;schen?", "td_background_heute");
			$_template->_user02 = "sites_admin/admin02.php";
			$_template->_user04 = "sites_admin/admin04_user_del.php";
		}
		break;
	case "import":
		$_infotext = getinfotext("CSV - Import (z.B.IPhone APP TimeOrg - timeorg.zimco.com)", "td_background_top");
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_admin/admin04_csv_import.php";
		break;
	case "anwesend":
		if ($_grpwahl == 0) $_grpwahl = 1;
		$_group   = new time_group($_grpwahl);
		if (@$id) $_grpwahl = $_group->get_usergroup($id);
		break;
	case "login_mehr":
		if (@$_POST['login'] == "Stempelzeit eintragen" and $_write) {
			$_logcheck->login($_POST, $_users->_array);
			if ($_SESSION['admin']) {
				$id = $_logcheck->_id;
				// Fehlerhandling bei F5 und dann sendenklick
				if ($_POST['_n'] <> "" and $_POST['_p'] <> "") {
					$_time->set_timestamp(time());
					$_time->save_time(time(), $_user->_ordnerpfad);
				}
			}
			$_logcheck->logout();
		}
		$_template->_user01 = "sites_time/null.php";
		$_template->_user02 = "sites_login/login_mehr_02.php";
		$_template->_user03 = "sites_login/login_mehr_03.php";
		$_template->_user04 = "sites_login/login_mehr_04.php";
		break;
	case "login_einzel":
		$_template->_user01 = "sites_time/null.php";
		$_template->_user02 = "sites_login/login_einzel_02.php";
		if ($_GET['group'] == "-1") $_template->_user03 = "login_einzel_03.php";
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
	case "anwesend":
		break;
	case "add_rapport":
		$_rapport = new time_rapport();
		$_template->_user02 = "sites_admin/admin02_user_cal.php";
		$_template->_user04 = "sites_time/rapport_add_04.php";
		break;
	case "insert_rapport":
		$_rapport = new time_rapport();
		if ($_POST['absenden'] == "UPDATE" and $_write) {
			$_rapport->insert_rapport($_user->_ordnerpfad, $_time->_timestamp);
		} elseif ($_POST['absenden'] == "DELETE" and $_write) {
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
		if (@$_POST['absenden'] == "OK" and $_write) {
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
		$_template->_user02 = "sites_admin/admin02_user_cal.php";
		$_template->_user04 = "sites_time/time_edit_04.php";
		break;
	case "update_time":
		$_oldtime = $_GET['timestamp'];
		$_newtime = $_time->mktime($_POST['_w_stunde'], $_POST['_w_minute'], 0, $_POST['_w_monat'], $_POST['_w_tag'], $_POST['_w_jahr']);
		if ($_POST['absenden'] == "UPDATE" and $_write) {
			// update oldtime, newtime, Ordner
			$_time->update_stempelzeit($_oldtime, $_newtime, $_user->_ordnerpfad);
		} elseif ($_POST['absenden'] == "DELETE" and $_write) {
			// delete //oldtime, Ordner
			$_time->delete_stempelzeit($_oldtime, $_user->_ordnerpfad);
		}
		$_template->_user02 = "sites_admin/admin02_user_cal.php";
		$_template->_user04 = "sites_user/admin04_timetable.php";
		break;
	case "insert_time_list":
		if (@$_POST['absenden'] == "OK" and $_write) {
			$_timestamp = $_GET['timestamp'];
			$_w_tag     = $_POST['_w_tag'];
			$_w_monat   = $_POST['_w_monat'];
			$_w_jahr    = $_POST['_w_jahr'];
			$_zeitliste = $_POST['_zeitliste'];
			if ($_zeitliste <> "") {
				$_w_sekunde = 0;
				$_zeitliste = trim($_zeitliste);
				$_zeitliste = str_replace(" ", "", $_zeitliste);
				$_zeitliste = str_replace(" ", "", $_zeitliste);
				$_zeitliste = str_replace(" ", "", $_zeitliste);
				$_zeitliste = str_replace(":", ".", $_zeitliste);
				$_zeitliste = str_replace(",", ".", $_zeitliste);
				$_zeitliste = explode("-", $_zeitliste);
				$_temptext  = "";
				foreach ($_zeitliste as $_zeiten) {
					$_tmp      = explode(".", $_zeiten);
					if (is_array($_tmp)) {
						$_w_stunde = $_tmp[0];
						if(isset($_tmp[1])) {
							$_w_minute = $_tmp[1];
						}else{
							$_w_minute = 0;
						}
					}
					if ($_w_minute == "") $_w_minute = 0;
					$tmp       = $_time->mktime($_w_stunde, $_w_minute, 0, $_w_monat, $_w_tag, $_w_jahr);
					$_time->save_time($tmp, $_user->_ordnerpfad);
				}
			}
		}
		$_template->_user02 = "sites_admin/admin02_user_cal.php";
		$_template->_user04 = "sites_user/admin04_timetable.php";
		break;
	case "insert_time":
		if (@$_POST['absenden'] == "OK" and $_write) {
			//if :falls eine Zeit fehlte / elseif : falls eine alte Zeit über Mitternacht geht
			if (isset($_POST['oldtime']) == 1) {
				$tmp2 = $_time->mktime($_POST['_w2_stunde'], $_POST['_w2_minute'], 0, $_POST['_w2_monat'], $_POST['_w2_tag'], $_POST['_w2_jahr']);
				$_time->set_timestamp($tmp2);
				$_time->save_time($tmp2, $_user->_ordnerpfad);
			} elseif (isset($_POST['oldtime']) == 2) {
				$tmp3 = $_time->mktime(23, 59, 59, $_POST['_w2_monat'], $_POST['_w2_tag'], $_POST['_w2_jahr']);
				$_time->set_timestamp($tmp3);
				$_time->save_time($tmp3, $_user->_ordnerpfad);

				$tmp2 = $_time->mktime(0, 0, 0, $_POST['_w_monat'], $_POST['_w_tag'], $_POST['_w_jahr']);
				$_time->set_timestamp($tmp2);
				$_time->save_time($tmp2, $_user->_ordnerpfad);
			}
			$tmp = $_time->mktime($_POST['_w_stunde'], $_POST['_w_minute'], 0, $_POST['_w_monat'], $_POST['_w_tag'], $_POST['_w_jahr']);
			$_time->set_timestamp($tmp);
			$_time->save_time($tmp, $_user->_ordnerpfad);
		}
		$_template->_user02 = "sites_admin/admin02_user_cal.php";
		$_template->_user04 = "sites_user/admin04_timetable.php";
		break;
	case "quick_time":
		$_time->set_runden((int) $_settings->_array[25][1]);
		$_time->save_quicktime($_user->_ordnerpfad);
		$_template->_user02 = "sites_admin/admin02_user_cal.php";
		$_template->_user04 = "sites_user/admin04_timetable.php";
		header("Location: admin.php");
		break;
	case "add_time":
		$_template->_user02 = "sites_admin/admin02_user_cal.php";
		$_template->_user04 = "sites_time/time_add_04.php";
		break;
	case "add_time_list":
		$_template->_user02 = "sites_admin/admin02_user_cal.php";
		$_template->_user04 = "sites_time/time_addlist_04.php";
		break;
	case "show_time":
		$_template->_user01 = "sites_admin/admin01.php";
		$_template->_user03 = "sites_admin/admin03.php";
		$_template->_user02 = "sites_admin/admin02_user_cal.php";
		$_template->_user04 = "sites_user/admin04_timetable.php";
		if (isset($_GET['excel'])) {
			$_datei = str_ireplace(" ", "-", trim($_user->_name));
			$_datei = $_datei . "-" . $_time->_jahr;
			$_datei = $_datei . "-" . $_time->_monat;
			$_datei = $_datei . ".xls";
			$_template->_user04 = "sites_admin/export_xls_monat.php";
		}
		break;
	case "show_pdf":
		$_infotext = getinfotext("PDF anzeigen", "td_background_top");
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_user/user04_pdf.php";
		break;
	case "print_month":
		include("./include/time_funktion_pdf.php");
		check_htaccess_pdf($_user->_ordnerpfad);
		$_jahr = date("Y", time());
		$_monat = date("n", time());
		$_tag  = date("j", time());
		erstelle_neu(0);
		$_template->_user02 = "sites_admin/admin02_user_cal.php";
		$_template->_user04 = "sites_user/user04_pdf_show.php";
		break;
	case "design":
		$_infotext = getinfotext("Design ausw&auml;hlen", "td_background_top");
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_user/user04_design.php";
		break;
	case "setdesign":
		$_infotext = getinfotext("<table><tr><td><img src='images/icons/error.png' border=0></td><td>Neues Design gew&auml;hlt</td></tr></table>", "td_background_heute");
		$_template = new time_template("index.php");
		$_template->set_templatepfad($_GET['designname']);
		$_template->_plugin = "modules/sites_plugin/plugin.php";
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
		$_infotext = getinfotext("Userdaten editieren", "td_background_top");
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_admin/admin04_user_einstellungen.php";
		break;
	case "user_einstellungen_update":
		$_a = $_POST['_a'];
		$_b = $_POST['_b'];
		$_c = $_POST['_c'];
		$_d = $_POST['_d'];
		$_user->set_user_data($_id, $_a, $_b, $_c, $_d);
		$_template->_user02 = "sites_admin/admin02_user_cal.php";
		$_template->_user04 = "sites_user/admin04_timetable.php";
		break;
	case "user_edit":
		$_infotext = getinfotext("User editieren", "td_background_top");
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_admin/admin04_user_edit.php";
		break;
	case "user_update":
		if (@$_POST['absenden'] == "OK") {
			$_user->set_user_details();
		}
		$_infotext = getinfotext("<table><tr><td><img src='images/icons/error.png' border=0></td><td>Userdaten wurden aktualisiert</td></tr></table>", "td_background_heute");
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_admin/admin04_user_edit.php";
		break;
	case "user_edit_absenzen":
		$_infotext = getinfotext("Absenzen editieren", "td_background_top");
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_admin/admin04_user_editabsenzen.php";
		break;
	case "user_update_absenzen":
		if ($_POST['absenden'] == "OK") {
			$_user->set_user_absenzen();
			$_user->load_data_session();
		}
		$_infotext = getinfotext("<table><tr><td><img src='images/icons/error.png' border=0></td><td>Absenzen wurde aktualisiert</td></tr></table>", "td_background_heute");
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_admin/admin04_user_editabsenzen.php";
		break;
	case "user_personalkarte":
		if (isset($_POST['update'])) {
			$_infotext = getinfotext("<img src='images/icons/error.png' border=0> Personalkarte von " . $_user->_name . " wurde aktualisiert", "td_background_heute");
		} else {
			$_infotext = getinfotext("Personalkarte von " . $_user->_name, "td_background_top");
		}
		$_personaldaten = new time_personalblatt();
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_admin/admin04_personalkarte.php";
		break;
	case "group";
		$_infotext = getinfotext("Gruppen editieren", "td_background_top");
		$_group    = new time_group(-1);
		//-----------------------------------------------
		//löschen einer Gruppe
		//-----------------------------------------------
		if (@$_GET['del'] <> "") {
			$_infotext = getinfotext("<table><tr><td><img src='images/icons/error.png' border=0></td><td>Gruppe gel&ouml;scht</td></tr></table>", "td_background_heute");
			$_group->del_group($_GET['del']);
		}
		//-----------------------------------------------
		//aktualisieren oder Gruppen hinzufügen
		//-----------------------------------------------
		if (@$_POST['senden']) {
			$_infotext = getinfotext("<table><tr><td><img src='images/icons/error.png' border=0></td><td>Gruppen gespeichert</td></tr></table>", "td_background_heute");
			$_group->save_group();
		}
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_admin/admin04_group_edit.php";
		break;
	case "settings";
		$_infotext = "Settings editieren";
		$_infotext = getinfotext($_infotext, "td_background_top");
		if (@$_POST['senden']) {
			$_infotext = getinfotext("<table><tr><td><img src='images/icons/error.png' border=0></td><td>Neue Settings gespeichert</td></tr></table>", "td_background_heute");
			$_settings->save_settings();
		}
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_admin/admin04_settings_edit.php";
		break;
	case "feiertage";
		$_infotext = getinfotext("Individuelle Feiertage mit einem festen Datum", "td_background_top");
		$_feiertage = new time_feiertage($_time->_jahr, $_settings->_array[12][1], $_user->_feiertage);
		if (@$_POST['senden']) {
			$_infotext = getinfotext("<table><tr><td><img src='images/icons/error.png' border=0></td><td>Feiertage gespeichert</td></tr></table>", "td_background_heute");
			$_feiertage->save_feiertage();
		} elseif (@$_GET['del'] <> "") {
			$_infotext = getinfotext("<table><tr><td><img src='images/icons/error.png' border=0></td><td>Feiertag gel&ouml;scht</td></tr></table>", "td_background_heute");
			$_feiertage->delete_feiertag($_GET['del']);
		}
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_admin/admin04_feiertage_edit.php";
		break;
	case "user_add":
		if (@$_POST['absenden'] == "OK") {
			$_a = $_POST['_a'];
			$_b = $_POST['_b'];
			$_c = sha1($_POST['_c']);
			$_d = $_POST['_d'];
			if ($_a <> "" && $_b <> "" && $_POST['_c'] <> "") {
				if ($_users->user_exist($_a)) {
					$_infotext = "<table><tr><td><img src='images/icons/error.png' border=0></td><td><font color=red>Mitarbeiter <b>" . $_a . "</b> existiert bereits!</font></td></tr></table>";
					$_infotext = getinfotext($_infotext, "td_background_heute");
				} else {
					$_users->insert_user($_a . ";" . $_b . ";" . $_c . ";" . $_d);
					$_users->add_user($_a);
					header("Location: admin.php?action=user_edit&admin_id=" . $_users->get_anzahl());
					$_infotext = "<table><tr><td><img src='images/icons/error.png' border=0></td><td>Mitarbeiter <b>" . $_a . "</b> wurde erfolgreich erstellt.</td></tr></table>";
					$_infotext = getinfotext($_infotext, "td_background_heute");
					break;
				}
			} else {
				//echo "Daten falsch";
				$_infotext = "<table><tr><td><img src='images/icons/error.png' border=0></td><td>Mitarbeiter konnte NICHT erstellt werden.</td></tr></table>";
				$_infotext = getinfotext($_infotext, "td_background_heute");
			}
		} else {
			$_infotext = "Neuer Mitarbeiter erfassen.";
			$_infotext = getinfotext($_infotext, "td_background_top");
		}
		$_template->_user02 = "sites_admin/admin02.php";
		$_template->_user04 = "sites_admin/admin04_user_add.php";
		break;
	default:
		setLoginForm();
		break;
}
// ----------------------------------------------------------------------------
// Logion - Formular darstellen
// ----------------------------------------------------------------------------
function setLoginForm()
{
	global $_template;
	$_template->_user01 = "sites_time/null.php";
	$_template->_user02 = "sites_login/admin_login_einzel_02.php";
	$_template->_user03 = "sites_login/admin_login_einzel_03.php";
	$_template->_user04 = "sites_login/admin_login_einzel_04.php";
}
if (@$_SESSION['admin']) {
	// ----------------------------------------------------------------------------
	// Monatsdaten berechnen
	// ----------------------------------------------------------------------------
	$_monat = new time_month($_settings->_array[12][1], $_time->_letzterTag, $_user->_ordnerpfad, $_time->_jahr, $_time->_monat, $_user->_arbeitstage, $_user->_feiertage, $_user->_SollZeitProTag, $_user->_BeginnDerZeitrechnung, $_settings->_array[21][1], $_settings->_array[22][1], $_settings->_array[27][1], $_settings->_array[28][1]);
	$_monat->_modal = $_template->_modal;
	// Falls automatische Pause eingestellt
	// TODO : wurde anderst gelöst, entfernen
	if ($_settings->_array[21][1] > 0) {
	}
	// ----------------------------------------------------------------------------
	// Jahresdaten berechnen
	// ----------------------------------------------------------------------------
	// Berechnung Endjahr = aktuelles Jahr, dann 0 sonst $_time->_jahr
	$_jahr = new time_jahr($_user->_ordnerpfad, 0, $_user->_BeginnDerZeitrechnung, $_user->_Stunden_uebertrag, $_user->_Ferienguthaben_uebertrag, $_user->_Ferien_pro_Jahr, $_user->_Vorholzeit_pro_Jahr, $_user->_modell, $_time->_timestamp);
}
$_copyright   = '<div class="copyright">';
//-----------------------------------------------------------------------------
//Seitenladezeit
//-----------------------------------------------------------------------------
$_time_end    = explode(" ", microtime());
$_time_end    = $_time_end[1] + $_time_end[0];
// ^^ Jetzt wird wieder die Aktuelle Zeit gemessen
$_zeitmessung = $_time_end - $_start_time;
// ^^ Endzeit minus Startzeit = die Differenz der beiden Zeiten
$_zeitmessung = substr($_zeitmessung, 0, 4);
// ^^ Die Zeit wird auf X Kommastellen gekürzt
$_copyright .= '<hr color="#DFDFDF" size="1">Ladezeit der Seite: ' . $_zeitmessung . ' Sekunden.<br>';
// ----------------------------------------------------------------------------
// copyright Text
// ----------------------------------------------------------------------------
$_arr = file("./include/Settings/copyright.txt");
$_ver = file("./include/Settings/smalltime.txt");
$_copyright .= "";
$_mem_usage = round((memory_get_peak_usage(true) / 1048576), 3);
if ($_mem_usage > 19.9) {
	$_debug = new time_filehandle("./debug/", "time.txt", ";");
	$_seite = explode('?', $_SERVER['HTTP_REFERER']);
	$_debug->insert_line("Memory Fehler ;" . date('d.m.Y', time()) . "; File:  admin.php?" . $_seite[1] . "; RAM:" . $_mem_usage);
}
foreach ($_arr as $_zeile) {
	$_tmp = str_replace("##ver##", $_ver[0], $_zeile);
	$_tmp = str_replace("##phpver##", phpversion(), $_tmp);
	$_tmp = str_replace("##memory##", $_mem_usage, $_tmp);
	$_copyright .= $_tmp;
}
$_copyright .= "</div>";


// ----------------------------------------------------------------------------
// Viewer - Anzeige der Seite
// ----------------------------------------------------------------------------
if (isset($_GET['modal'])) {
	// bei Modal nur DIV04 anzeigen
	include($_template->get_user04());
} elseif (isset($_GET['excel'])) {
	if (!$_datei)  $_datei = 'excel.xls';
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=" . $_datei);
	include($_template->get_user04());
} elseif (isset($_GET['function'])) {
	echo $_template->_ajaxhtml;
} else {
	include($_template->get_template());
}
