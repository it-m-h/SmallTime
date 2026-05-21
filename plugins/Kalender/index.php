<?php
/*******************************************************************************
* Small Time - Plugin : Kalender Absenzenansicht der Mitarbeiter
/*******************************************************************************
* Version 0.9.205
* Author:  IT-Master
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master, All rights reserved
*******************************************************************************/
$plugin = 'Kalender';
if (isset($_POST['plugin']) && $_POST['plugin'] != '') {
	$plugin = $_POST['plugin'];
} elseif (isset($_GET['plugin']) && $_GET['plugin'] != '') {
	$plugin = $_GET['plugin'];
}
$_infotext = "<b>Plugins werden geladen</b> : ".$plugin . " wird geladen.";
$_template->_user01 = "Kalender/sites/div01.php";
$_template->_user02 = "Kalender/sites/div02.php";
$_template->_user03 = "Kalender/sites/div03.php";
$_template->_user04 = "Kalender/sites/div04.php";