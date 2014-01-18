<?php
/*******************************************************************************
* Small Time Controller
/*******************************************************************************
* Version 0.8
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
// ----------------------------------------------------------------------------
// Modler laden
// ----------------------------------------------------------------------------
define('FPDF_INSTALLDIR', './fpdf');
if(!defined('FPDF_FONTPATH')) define('FPDF_FONTPATH', FPDF_INSTALLDIR.'/font/');
include_once(FPDF_INSTALLDIR.'/fpdf.php');
include_once ('./include/class_absenz.php');
include_once ('./include/class_user.php');
include_once ('./include/class_group.php');
include_once ('./include/class_login.php');
include_once ('./include/class_template.php');
include_once ('./include/class_time.php');
include_once ('./include/class_month.php');
include_once ('./include/class_jahr.php');
include_once ('./include/class_feiertage.php');
include_once ('./include/class_filehandle.php');
include_once ('./include/class_rapport.php');
include_once ('./include/class_show.php');
include_once ('./include/class_settings.php');
//include ("./include/funktionen.php"); 
//include ('./include/setting.php');

class time_controller{
	public $_write 	= false;
	public $_token 	= NULL;
	
	public $_now 	= NULL;
	public $_show 	= NULL;
	
	
	
	function __construct(){	
		$this->set_show();
		$this->set_token();
		$this->echo_show();
		// ----------------------------------------------------------------------------
		// Modler allgemeine Daten laden
		// ----------------------------------------------------------------------------
		$_users 	= new time_filehandle("./Data/","users.txt",";");
		$_groups 	= new time_filehandle("./Data/","group.txt",";");
		//$_absenz 	= new time_filehandle("./Data/","absenz.txt",";");
		$_settings 	= new time_settings();
		$_template 	= new time_template();
		//include ('./include/setting.php');
		// ----------------------------------------------------------------------------
		// Controller für Login
		// ----------------------------------------------------------------------------
		$_logcheck = new time_login();
		// keine Session vorhanden
		if($_SESSION['admin']==NULL OR $_SESSION['admin']==""){
			if (in_array(2,$this->_show)) txt("keine Session, Login durchführen");
			$_Userpfad = $_SESSION['admin']."/";
			//$_action = "";
		}
		// Login über Cookie mit Datenüberprüfung
		if($_COOKIE["lname"] and $_COOKIE["lpass"] and ($_SESSION['admin']==NULL OR $_SESSION['admin']=="")){
			if (in_array(2,$this->_show)) txt("Cookie gesetzt - Autologin prüfen");
			$_logcheck->login($_POST, $_users->_array);
			//$_action = "";
		}
		// Loginformular - Datenüberprüfung
		if($_POST['login']){
			if (in_array(2,$this->_show)) txt("Formular - Login geklickt"); 
			$_logcheck->login($_POST, $_users->_array);
			//$_action = "";
		} 
		if($_GET['action']=="logout"){
			if (in_array(2,$this->_show)) txt("Formular - Logout geklickt"); 
			$_logcheck->logout();
			//$_action = "";
		} 
		if (in_array(2,$this->_show)) showClassVar($_logcheck);
		// ----------------------------------------------------------------------------
		// Controller für Action
		// ----------------------------------------------------------------------------
		// Session  vorhanden - Daten anzeigen
		if($_SESSION['admin'] and !$_GET['action']){
			if (in_array(2,$this->_show)) {
				txt("Session vorhanden - normale Anzeige");
				txt("User: ". $_SESSION['admin']);
			}
			$_action = "show_time";
			//$_logcheck->login($_POST, $_users->_array);
		}elseif ($_GET['action']){
			$_action = $_GET['action'];
			$_grpwahl = $_GET['group']-1;
			if (array_search(2,$this->_show)) txt("GET_Action gewählt : ". $_action);
		}elseif($_GET['group']){
			if (in_array(2,$this->_show)) txt("GET Group gewählt : ". $_GET['group']);
			$_grpwahl = $_GET['group']-1;
			$_action = "login_mehr";
			if ($_GET['group']=="-1") {
				$_action = "login_einzel";
			}
		}elseif($_settings->_array[19][1]=="1"){
			if (in_array(2,$this->_show)) txt("Mehrbenutzersystem aktiviert : ". $_settings->_array[19][1]);
			//Falls Mehrbenutzersystem eingestellt wurde 
			$_action = "login_mehr";
		}
		// ----------------------------------------------------------------------------
		// Modler Userdaten laden
		// ----------------------------------------------------------------------------
		if($_SESSION['admin']) {
			include ('./include/time_variablen_laden.php');
		}
		// ----------------------------------------------------------------------------
		// Controller Templatedarstellung
		// ----------------------------------------------------------------------------
		if (in_array(2,$this->_show)) txt("SWITCH von \$_action = ". $_action);
		switch($_action){
			case "anwesend":
				if (in_array(2,$this->_show)) txt("Anwesenheitsliste");
				if($_grpwahl==0) $_grpwahl = 1;
				$_group = new time_group($_grpwahl);
				if($id) $_grpwahl = $_group->get_usergroup($id);
				$_template->_user02 = "login_mehr_02.php";
				$_template->_user04 = "login_mehr_04.php";
				$_template->_user03 = "user03_stat.php";
				break;
			case "login_mehr":
				if (in_array(2,$this->_show)) txt("Mehrbenutzer - Login");		
				//echo "<hr> login, daten schreiben, logout",
				//if ($_SESSION['admin'] ) echo "<hr>  erfolgreich eingeloggt";
				if ($_POST['login'] == "Stempelzeit eintragen" and $_write){
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
				if($_grpwahl==0) $_grpwahl = 1;					
				$_group = new time_group($_grpwahl);
				//echo " - ist in Gruppe : " . $_group->get_usergroup($id);
				if($id) $_grpwahl = $_group->get_usergroup($id);
				//echo "<hr> Logout";
				$_template->_user01 = "null.php";	
				$_template->_user02 = "login_mehr_02.php";
				$_template->_user03 = "login_mehr_03.php";
				$_template->_user04 = "login_mehr_04.php";
				break;
			case "login_einzel":
				if (in_array(2,$this->_show)) txt("Einzel - Login");
				$_template->_user01 = "null.php";
				$_template->_user02 = "login_einzel_02.php";
				if ($_GET['group']=="-1") $_template->_user03 = "login_einzel_03.php";
				$_template->_user04 = "login_einzel_04.php";
				break;
			case "login":
				if (in_array(2,$this->_show)) txt("Login - Check");
				$_logcheck = new time_login($_POST, $_users->_array);
				break;
			case "logout":
				if (in_array(2,$this->_show)) txt("Logout und Formular anzeigen");
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
				if (in_array(2,$this->_show)) txt("Anwesenheitsliste");
				break;
			case "add_rapport":
				$_rapport = new time_rapport();
				$_template->_user02 = "user02_cal.php";
				$_template->_user04 = "rapport_add_04.php";
				$_template->_user03 = "user03_stat.php";
				break;
			case "insert_rapport":
				$_rapport = new time_rapport();
				if ($_POST['absenden'] == "UPDATE" and $_write){	
					$_rapport->insert_rapport($_user->_ordnerpfad, $_time->_timestamp);
				} elseif ($_POST['absenden'] == "DELETE" and $_write){
					$_rapport->delete_rapport($_user->_ordnerpfad, $_time->_timestamp);
				}
				$_template->_user02 = "user02_cal.php";
				$_template->_user04 = "user04_timetable.php";
				$_template->_user03 = "user03_stat.php";
				break;
			case "add_absenz":
				$_template->_user02 = "user02_cal.php";
				$_template->_user04 = "absenz_add_04.php";
				$_template->_user03 = "user03_stat.php";
				break;
			case "insert_absenz":
				if ($_POST['absenden'] == "OK" and $_write){
					$_absenz->insert_absenz($_user->_ordnerpfad, $_time->_jahr);
				}
				$_template->_user02 = "user02_cal.php";
				$_template->_user04 = "user04_timetable.php";
				$_template->_user03 = "user03_stat.php";
				break;
			case "delete_absenz":
				$_absenz->delete_absenz($_user->_ordnerpfad, $_time->_jahr);
				$_template->_user02 = "user02_cal.php";
				$_template->_user04 = "user04_timetable.php";
				$_template->_user03 = "user03_stat.php";
				break;
			case "edit_time":
				if (in_array(2,$this->_show)) txt("Zeit editieren - Formular");
				$_template->_user02 = "user02_cal.php";
				$_template->_user04 = "time_edit_04.php";
				$_template->_user03 = "user03_stat.php";	
				break;
			case "update_time":	
				$_oldtime = $_GET['timestamp'];
				$_newtime = $_time->mktime($_POST['_w_stunde'],$_POST['_w_minute'],0,$_POST['_w_monat'], $_POST['_w_tag'],$_POST['_w_jahr']);
				//echo "<hr> \$_write = " . $_write;
				if ($_POST['absenden'] == "UPDATE" and $_write){
					if (in_array(2,$this->_show)) txt("Zeit updaten : ". $_oldtime);
					// update oldtime, newtime, Ordner
					$_time->update_stempelzeit($_oldtime, $_newtime, $_user->_ordnerpfad);
				} elseif ($_POST['absenden'] == "DELETE" and $_write){
					if (in_array(2,$this->_show)) txt("Zeit löschen :".$_oldtime);
					// delete //oldtime, Ordner
					$_time->delete_stempelzeit($_oldtime, $_user->_ordnerpfad);
				}else{
					if (in_array(2,$this->_show)) txt("Zeit updaten und löschen fehlgeschlagen");
				}
				$_template->_user02 = "user02_cal.php";
				$_template->_user04 = "user04_timetable.php";
				$_template->_user03 = "user03_stat.php";
				break;
			case "insert_time_list":
				if ($_POST['absenden'] == "OK" and $_write){
					$_timestamp		= $_GET['timestamp'];
		        	$_w_tag			= $_POST['_w_tag'];
		        	$_w_monat		= $_POST['_w_monat'];
		        	$_w_jahr		= $_POST['_w_jahr'];
		        	$_zeitliste		= $_POST['_zeitliste'];
					$_w_sekunde		= 0;
					$_zeitliste = trim($_zeitliste);
					$_zeitliste = str_replace(" ", "", $_zeitliste);
					$_zeitliste = str_replace(" ", "", $_zeitliste);
					$_zeitliste = str_replace(" ", "", $_zeitliste);			
					$_zeitliste = explode("-",$_zeitliste);
					$_temptext = "";
					foreach ($_zeitliste as $_zeiten){			
						//$_zeiten = str($_zeiten);
						//if(strstr(":",$_zeiten)){
							$_tmp = explode(".",$_zeiten);
							$_w_stunde = $_tmp[0];
							$_w_minute = $_tmp[1];
							if  ($_w_minute=="")$_w_minute=0;	
							
							$tmp = $_time->mktime($_w_stunde,$_w_minute,0,$_w_monat, $_w_tag,$_w_jahr);				
						//} else {
						//	$_w_stunde = $_zeiten;
						//	$_w_minute = 0; 
						//}
						//$_temptext = $_temptext . $_w_stunde. "." . $_w_minute . "#";	
						//echo $tmp;
						$_time->save_time($tmp, $_user->_ordnerpfad);
						//$_time->save_time_list($_user->_ordnerpfad);
					}
					//$_temptext = $_zeitliste[0]. " bis ". $_zeitliste[1];		
					//echo "Variablen ".$_timestamp ." / ". $_w_tag ." / ".$_w_monat ." / ". $_w_jahr." / ". $_temptext ." / ".$_w_sekunde;	
				}
				$_template->_user02 = "user02_cal.php";
				$_template->_user04 = "user04_timetable.php";
				$_template->_user03 = "user03_stat.php";
				break;
			case "insert_time":
				if (in_array(2,$this->_show)) txt("Zeit speichern");
				if ($_POST['absenden'] == "OK" and $_write){
					$tmp = $_time->mktime($_POST['_w_stunde'],$_POST['_w_minute'],0,$_POST['_w_monat'], $_POST['_w_tag'],$_POST['_w_jahr']);
					$_time->set_timestamp($tmp);
					$_time->save_time($tmp, $_user->_ordnerpfad);
				}
				$_template->_user02 = "user02_cal.php";
				$_template->_user04 = "user04_timetable.php";
				$_template->_user03 = "user03_stat.php";
				break;
			case "add_time":
				if (in_array(2,$this->_show)) txt("Zeit eintragen - Formular");
				$_template->_user02 = "user02_cal.php";
				$_template->_user04 = "time_add_04.php";
				$_template->_user03 = "user03_stat.php";
				break;
			case "add_time_list":
				if (in_array(2,$this->_show)) txt("Zeit eintragen - Formular");
				$_template->_user02 = "user02_cal.php";
				$_template->_user04 = "time_addlist_04.php";
				$_template->_user03 = "user03_stat.php";
				break;
			case "show_time":
				if (in_array(2,$this->_show)) txt("User - Anzeige seiner Daten");
				$_template->_user02 = "user02_cal.php";
				$_template->_user04 = "user04_timetable.php";
				$_template->_user03 = "user03_stat.php";
				break;
			default:
				if (in_array(2,$this->_show)) txt("Defaultanzeige");
				setLoginForm();
				break;	
		}
		// ----------------------------------------------------------------------------
		// Logion - Formular darstellen
		// ----------------------------------------------------------------------------
		function setLoginForm(){
			global $_template;
			if($_settings->_array[19][1]==0){
				$_template->_user01 = "null.php";
				$_template->_user02 = "login_einzel_02.php";
				$_template->_user03 = "login_einzel_03.php";
				$_template->_user04 = "login_einzel_04.php";			
			} else{
				$_template->_user01 = "null.php";
				$_template->_user02 = "login_mehr_02.php";
				$_template->_user03 = "login_mehr_03.php";
				$_template->_user04 = "login_mehr_04.php";
			}
		}
		// ----------------------------------------------------------------------------
		// Anzeige für Entwickler 
		// ----------------------------------------------------------------------------
		include ('./include/_debug_data.php');

		if($_SESSION['admin']) {
			// ----------------------------------------------------------------------------
			// Monatsdaten berechnen
			// ----------------------------------------------------------------------------
			//define('user2','<hr>--------------------------------------------------------bla<hr>');
			//echo user2;
			$_monat 	= new time_month( $_settings->_array[12][1] , $_time->_letzterTag, $_user->_ordnerpfad, $_time->_jahr, $_time->_monat, $_user->_arbeitstage, $_user->_feiertage, $_user->_SollZeitProTag, $_user->_BeginnDerZeitrechnung);
			if (in_array(5,$this->_show)) {
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
			// berechnung Endjahr = aktuelles jahr, dann 0 sonst $_time->_jahr
			$_jahr = new time_jahr($_user->_ordnerpfad, 0, $_user->_BeginnDerZeitrechnung, $_user->_Stunden_uebertrag, $_user->_Ferienguthaben_uebertrag, $_user->_Ferien_pro_Jahr, $_user->_Vorholzeit_pro_Jahr);
			if (in_array(6,$this->_show)) {
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
		// ----------------------------------------------------------------------------
		// Viewer - Anzeige der Seite
		// ----------------------------------------------------------------------------
		include ($_template->get_template());	
	}
	function __destruct(){
	}
	
	function set_golbalvar(){
		
	}
	function echo_show(){
		// ----------------------------------------------------------------------------
		// MGET und POST Daten anzeigen
		// ----------------------------------------------------------------------------
		if (in_array(11,$this->_show)) {
				echo "GET : ";
				$zeig = new time_show($_GET);
				echo "POST : ";
				$zeig = new time_show($_POST);
		}
		if (in_array(8,$this->_show)) {
			txt("Settings - Daten füllen und anzeigen : \$_settings");
			showClassVar($_settings);
			txt("<hr color=red>");
		}
		// ----------------------------------------------------------------------------
		// Session - Variablen anzeigen
		// ----------------------------------------------------------------------------
		if (in_array(0,$this->_show)){
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
	}
	function set_show(){
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
		// 7 = Gruppen - Daten anzeigen
		// 8 = Settings - Daten anzeigen
		// 9 = ALLE VARIABLEN AUSGEBEN
		// 11 = GET und POST anzeigen
		// eingabe : array(1,2,9)
		$this->_show = array(11);		
	}
	function set_token(){
		$this->_now = $_GET['token'];
		$this->_token = md5(uniqid('SmallTime')); 
		if (trim($_SESSION['last'])== trim($this->_now) and isset($_SESSION['last'])) {
			$this->_write = true;
		}else {
			$this->_write = false;
		}
		$_SESSION['last'] = $this->_token;
	}
}


function txt($txt){
	echo "<p style='color:red'>$txt</p>"; 
}
function showClassVar($class) {
  echo "<table>";
  echo "<tr><td colspan=3>";
  echo '<strong>'.get_class($class).' - Variablen:</strong></td><tr>';
  $aVars = get_class_vars(get_class($class));
  foreach($aVars as $name => $value) {
    echo "<tr><td>";
	echo "[$name]";
	echo "</td><td> : </td><td>";
	
	if(is_array ($class->$name)){
		echo "<p style='color:blue'>";
		print_r($class->$name);
		echo "</p></td></tr>";
	}else{
		echo "<p style='color:blue'>" . $class->$name . "</p></td></tr>";	
	}
	
  }
  echo '<tr><td colspan=3><strong>'.get_class($class).' - Methoden:</strong></td><tr>';
  $aMethods = get_class_methods(get_class($class));
  sort($aMethods);
  foreach($aMethods as $name => $value) {
	echo "<tr><td>";
	echo "[$name]";
	echo "</td><td> : </td><td>";
	echo "<p style='color:blue'>" . $value ."</p></td></tr>";
  }
  echo '</p>';
  echo "</table>";
}
?>