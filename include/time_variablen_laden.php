<?php
/*******************************************************************************
* Small Time Start, Variablen deklarieren
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
// ----------------------------------------------------------------------------
// TIMESTAMP
// ----------------------------------------------------------------------------
$_time = new time();
if(isset($_GET["timestamp"])){
	$_time->set_timestamp($_GET["timestamp"]);
}
$_time->set_monatsname($_settings->_array[11][1]);
// ----------------------------------------------------------------------------
// USERDATEN
// ----------------------------------------------------------------------------
$_user = new time_user();
if(isset($_GET['admin_id'])){
	$_id = $_GET['admin_id'];
	$_SESSION['id'] = $_id;
	$_SESSION['username'] = $_users->_array[$_id][1];
	$_SESSION['passwort'] = $_users->_array[$_id][2];
	$_SESSION['datenpfad'] = $_users->_array[$_id][0];	
}
$_user->load_data_session();	
// ----------------------------------------------------------------------------
// Absenzen - array
// ----------------------------------------------------------------------------
$_absenz = new time_absenz($_user->_ordnerpfad, $_time->_jahr);