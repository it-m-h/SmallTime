<?php
/*******************************************************************************
* User - Daten
/*******************************************************************************
* Version 0.899
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_user{
	public $_loginname 			= NULL;
	public $_password 			= NULL;
	public $_ordnerpfad 			= NULL;	
	public $_name				= NULL;	
	public $_SollZeitProWoche	= NULL;
	public $_SollZeitProzent		= NULL;
	public $_WochenArbeiztsZeit	= NULL;
	//So = 0, Sa = 7
	public $_arbeitstage 		= array(0,0,0,0,0,0,0);
	public $_SummeArbeitstage 	= NULL;
	public $_SollZeitProTag		= NULL;
	public $_BeginnDerZeitrechnung	= NULL;	
	public $_Vorholzeit_pro_Jahr	= NULL;
	public $_Ferien_pro_Jahr		= NULL;		
	public $_Stunden_uebertrag 	= NULL;
	public $_Ferienguthaben_uebertrag 	= NULL;
	public $_feiertage 			= array(0,0,0,0,0,0,0,0,0,0,0);
	public $_absenzen			= array();
	public $_zuschlag			= array();
	public $_modell			= NULL;
	
	function __construct(){
		$this->check_htaccess();
	}
	function load_data_pfad($datenpfad){
		$_userdaten = file("./Data/".$datenpfad."/userdaten.txt");
		$this->_loginname 	= $_SESSION['username'];
		$this->_password 	= $_SESSION['passwort'];
		$this->_ordnerpfad	= $_SESSION['datenpfad'];
		$this->_name		= $_userdaten[0];
		$this->_WochenArbeiztsZeit = $_userdaten[3];
		$this->_SollZeitProWoche = ($_userdaten[3] / 100 * $_userdaten[2]);
		$this->_SollZeitProzent = $_userdaten[2];
		$this->_SummeArbeitstage = 0;
		$this->_arbeitstage = explode(";", $_userdaten[7]);
		foreach($this->_arbeitstage as $_tmp){
			$this->_SummeArbeitstage = $this->_SummeArbeitstage + $_tmp;
		}
		$this->_SollZeitProTag = round($this->_SollZeitProWoche / $this->_SummeArbeitstage,2);
		$this->_BeginnDerZeitrechnung = $_userdaten[1];
		$this->_Vorholzeit_pro_Jahr = $_userdaten[4];
		$this->_Ferien_pro_Jahr = $_userdaten[5];
		$tmp = explode(';',$_userdaten[6]);
		$this->_Stunden_uebertrag = $tmp[0];
		$this->_Ferienguthaben_uebertrag = $tmp[1];
		$this->_feiertage = explode(";", $_userdaten[8]);
		$this->_zuschlag[0] = explode(";", $_userdaten[9]);	
		$this->_zuschlag[1] = explode(";", $_userdaten[10]);
		$this->_zuschlag[2] = explode(";", $_userdaten[11]);
		$this->_zuschlag[3] = explode(";", $_userdaten[12]);
		$this->_zuschlag[4] = explode(";", $_userdaten[13]);
		$this->_zuschlag[5] = explode(";", $_userdaten[14]);
		$this->_zuschlag[6] = explode(";", $_userdaten[15]);
		$this->_modell = $_userdaten[16];
		if ($this->_modell==NULL) $this->_modell = 0;
	}
	function load_data_session(){
		if($_SESSION['datenpfad']){
			$_userdaten = file("./Data/".$_SESSION['datenpfad']."/userdaten.txt");
			$this->_loginname 	= $_SESSION['username'];
			$this->_password 	= $_SESSION['passwort'];
			$this->_ordnerpfad	= $_SESSION['datenpfad'];
			$this->_name		= $_userdaten[0];
			$this->_WochenArbeiztsZeit = $_userdaten[3];
			$this->_SollZeitProWoche = ($_userdaten[3] / 100 * $_userdaten[2]);
			$this->_SollZeitProzent = $_userdaten[2];
			$this->_SummeArbeitstage = 0;
			$this->_arbeitstage = explode(";", $_userdaten[7]);
			foreach($this->_arbeitstage as $_tmp){
				$this->_SummeArbeitstage = $this->_SummeArbeitstage + $_tmp;
			}
			$this->_SollZeitProTag = round($this->_SollZeitProWoche / $this->_SummeArbeitstage,2);
			$this->_BeginnDerZeitrechnung = $_userdaten[1];
			$this->_Vorholzeit_pro_Jahr = $_userdaten[4];
			$this->_Ferien_pro_Jahr = $_userdaten[5];
			$tmp = explode(';',$_userdaten[6]);
			$this->_Stunden_uebertrag = $tmp[0];
			$this->_Ferienguthaben_uebertrag = $tmp[1];
			$this->_feiertage = explode(";", $_userdaten[8]);
			$this->_zuschlag[0] = explode(";", $_userdaten[9]);	
			$this->_zuschlag[1] = explode(";", $_userdaten[10]);
			$this->_zuschlag[2] = explode(";", $_userdaten[11]);
			$this->_zuschlag[3] = explode(";", $_userdaten[12]);
			$this->_zuschlag[4] = explode(";", $_userdaten[13]);
			$this->_zuschlag[5] = explode(";", $_userdaten[14]);
			$this->_zuschlag[6] = explode(";", $_userdaten[15]);	
			$this->_modell = $_userdaten[16];
			if ($this->_modell==NULL) $this->_modell = 0;
		}	
	}
	function set_user_data($_id,$pfad,$loginname,$passwort,$rfid){
		$_zeilenvorschub = "\r\n";
		$_users= file("./Data/users.txt");
		if($_POST['absenden'] == "OK"){
			Global $_id;
			if (count($_users) == ($_id+1)){
				$_zeilenvorschub = "";
			}
			$passwort    = sha1($passwort);
			$_users[$_id] = $pfad.";".$loginname.";".$passwort.";".$rfid.$_zeilenvorschub;
			$neu = implode( "", $_users);
			$open = fopen("./Data/users.txt","w+");
			fwrite ($open, $neu);
			fclose($open);
		}		
	}
	function set_user_details(){
		$_zeilenvorschub = "\r\n";
		$_file = "./Data/".$this->_ordnerpfad."/userdaten.txt";
		$_a	= $_POST['_a'];
		$_b = $_POST['_b'];
		//$_b wird zu einem Timestamp
		$_b = explode(".", $_b);
		$_tmp = mktime(0, 0, 0, $_b[1], $_b[0], $_b[2]);
		$_m = $_POST['_m'];
		$_c 				= $_POST['_c'];
		$_d				= $_POST['_d'];
		$_e				= $_POST['_e'];
		$_f				= $_POST['_f'];
		$_g1			= $_POST['_g1'];
		$_g2			= $_POST['_g2'];
		$_tag 			= array();
		$_tag[]			= $_POST['wotag0'];
		$_tag[]			= $_POST['wotag1'];
		$_tag[]			= $_POST['wotag2'];
		$_tag[]			= $_POST['wotag3'];
		$_tag[]			= $_POST['wotag4'];
		$_tag[]			= $_POST['wotag5'];
		$_tag[]			= $_POST['wotag6'];
		$_anzahlFT		= $_POST['anzahlFT'];		
		$_FT 			= array();
		for ($u=1; $u<=$_anzahlFT; $u++){
			$_FT[]	= $_POST['feiertag'.$u];
		}
		$x=0;
		foreach($_FT as $_wert){
			if($_wert){
				$_FT[$x]=1;
			}else{
				$_FT[$x]=0;
			}
			$x++;
		}	
		$_ZT[]	= $_POST['zutagvon0'].";".$_POST['zutagbis0'].";".$_POST['zutagporzent0'];
		$_ZT[]	= $_POST['zutagvon1'].";".$_POST['zutagbis1'].";".$_POST['zutagporzent1'];
		$_ZT[]	= $_POST['zutagvon2'].";".$_POST['zutagbis2'].";".$_POST['zutagporzent2'];
		$_ZT[]	= $_POST['zutagvon3'].";".$_POST['zutagbis3'].";".$_POST['zutagporzent3'];
		$_ZT[]	= $_POST['zutagvon4'].";".$_POST['zutagbis4'].";".$_POST['zutagporzent4'];
		$_ZT[]	= $_POST['zutagvon5'].";".$_POST['zutagbis5'].";".$_POST['zutagporzent5'];
		$_ZT[]	= $_POST['zutagvon6'].";".$_POST['zutagbis6'].";".$_POST['zutagporzent6'];	
		$fp = fopen($_file,"w+");
		fputs($fp, $_a.$_zeilenvorschub);
		fputs($fp, $_tmp.$_zeilenvorschub);
		fputs($fp, $_c.$_zeilenvorschub);
		fputs($fp, $_d.$_zeilenvorschub);
		fputs($fp, $_e.$_zeilenvorschub);
		fputs($fp, $_f.$_zeilenvorschub);
		fputs($fp, $_g1.";".$_g2.$_zeilenvorschub);
		$_WT = implode(";", $_tag);
		fputs($fp, $_WT.$_zeilenvorschub);
		$_FT = implode(";", $_FT);
		fputs($fp, $_FT.$_zeilenvorschub);
		$_ZT = implode($_zeilenvorschub, $_ZT);
		fputs($fp, $_ZT.$_zeilenvorschub);
		fputs($fp, $_m.$_zeilenvorschub);
		fclose($fp);
		$this->load_data_pfad($_SESSION['datenpfad']);
	}
	function set_user_absenzen(){
		$i = $_POST['anzahl'];
		$_zeilenvorschub = "\r\n";
		$_file = "./Data/".$this->_ordnerpfad."/absenz.txt";
		$fp = fopen($_file,"w+");
		for($z=0; $z<=$i; $z++){
			$ab0 = "ab0_".$z;
			$ab1 = "ab1_".$z;
			$ab2 = "ab2_".$z;
			fputs($fp, $_POST[$ab0].";".$_POST[$ab1].";".$_POST[$ab2].$_zeilenvorschub);
		}
		fclose($fp);
	}
	function get_user_absenzen(){
		$_file = "./Data/".$this->_ordnerpfad."/absenz.txt";
		if(file_exists($_file)){
			$_absenzarr = file($_file);
		}else{
			// Falls ein neuer User erstellt wurde, wird eine neue Datei erstellt mit den vordefinierten Einträgen
			$_absenzarr[0] = "Ferien;F;100";
			$_absenzarr[1] = "Krankheit;K;100";
			$_absenzarr[2] = "Unfall;U;100";
			$_absenzarr[3] = "Militär;M;100";
			$_absenzarr[4] = "Intern;I;100";
			$_absenzarr[5] = "Weiterbildung;W;100";
			$_absenzarr[6] = "Extern;E;100";
		}
		return $_absenzarr;
	}
	function check_htaccess(){
		$_file = "./Data/.htaccess";
		if(!file_exists($_file)){
			$_zeilenvorschub = "\r\n";
			$fp = fopen($_file,"a+");
			fputs($fp, "Deny from all");
			fclose($fp);
			$_datum = date("d.m.Y",time());
			$_uhrzeit = date("H:i",time());
			$_datetime =  $_datum." - ".$_uhrzeit;
			$_debug 	= new time_filehandle("./debug/","time.txt",";");
			$_debug->insert_line("Time;" . $_datetime . ";Fehler in class_user;213;" .$this->_file.";htaccess nicht vorhanden, wurde erstellt.");
		}
	}
	public static function get_user_startyear(){
		return date('Y', time_user::get_user_starttime());
	}
	public static function get_user_startmonth(){
		return date('m', time_user::get_user_starttime());
	}
	public static function get_user_startdaty(){
		return date('d', time_user::get_user_starttime());
	}
	public static function get_user_starttime(){
		$_userdaten = file("./Data/".$_SESSION['datenpfad']."/userdaten.txt");
		$tmp = trim($_userdaten[1]);
		$tmp = str_ireplace('\r','', $tmp);
		$tmp = str_ireplace('\n','', $tmp);
		return $tmp;
	}
	public static function get_user_stempelzeiten($user, $jahr, $monat, $tag){
		$_userdaten = array();
		$_return = array();
		$_file = "./Data/".$user."/Timetable/" . $jahr.'.' .$monat;
		if(file_exists($_file)){
			$_userdaten = file($_file);
			foreach($_userdaten as $_stempelzeiten){
				if(date('j', $_stempelzeiten) == $tag){
					$_return[] = $_stempelzeiten;
				}
			}	
		}
		return $_return;
	}
}