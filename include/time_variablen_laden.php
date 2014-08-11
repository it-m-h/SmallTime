<?php
/*******************************************************************************
* Small Time Start, Variablen deklarieren
/*******************************************************************************
* Version 0.872
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
// ----------------------------------------------------------------------------
// VARIABLEN mit Daten füllen
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------
// TIMESTAMP
// ----------------------------------------------------------------------------
$_time = new time();
//echo $_settings->_array[11][1];
if(isset($_GET["timestamp"])){
	//$_timestamp = $_GET["timestamp"];
	//echo "<hr>".$_GET["timestamp"]."<hr>";
	$_time->set_timestamp($_GET["timestamp"]);
}else{
	//$_timestamp = mktime($_w_stunde, $_w_minute, $_w_sekunde, $_w_monat, $_w_tag, $_w_jahr);
}
$_time->set_monatsname($_settings->_array[11][1]);


//$_letzterTag = idate(d,mktime(0, 0, 0, ($_w_monat+1), 0, $_w_jahr));
if(in_array(4,$show)){
	txt("\$_time : Time - Daten Setzen und anzeigen.");
	showClassVar($_time);
	txt("<hr color=red>");
}

// ----------------------------------------------------------------------------
// USERDATEN
// ----------------------------------------------------------------------------
$_user = new time_user();
if(isset($_GET['admin_id'])){
	$_id = $_GET['admin_id'];
	//Falls ein user gelöscht wurde und die id höher ist als die Anzahl der User
	//if($_id > count(file("./Data/users.txt"))-1) $_id=1;	
	//echo "<hr>";
	$_SESSION['id'] = $_id;
	$_SESSION['username'] = $_users->_array[$_id][1];
	$_SESSION['passwort'] = $_users->_array[$_id][2];
	$_SESSION['datenpfad'] = $_users->_array[$_id][0];	
}
$_user->load_data_session();	
if(in_array(3,$show)){
	txt("User - Daten f&uuml;llen und anzeigen : \$_user");
	showClassVar($_user);
	txt("<hr color=red>");
}

// ----------------------------------------------------------------------------
// Absenzen - array
// ----------------------------------------------------------------------------
$_absenz = new time_absenz($_user->_ordnerpfad, $_time->_jahr);
if(in_array(12,$show)){
	txt("Absenz - Daten f&uuml;llen und anzeigen : \$_absenz");
	showClassVar($_absenz);
	txt("<hr color=red>");
}


?>