<?php
/*******************************************************************************
* Small Time - Plugin : Statistik der Mitarbeiter (Überzeit, Ferien usw.)
/*******************************************************************************
* Version 0.896
* Author:  IT-Master
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master, All rights reserved
*******************************************************************************/
$pluginName = (isset($_POST['plugin']) ? $_POST['plugin'] : (isset($_SESSION['plugin']) ? $_SESSION['plugin'] : "Statistik"));
$_infotext = "<b>Plugins werden geladen</b> : ".$pluginName . " wird geladen.";
$_template->_user01 = "Statistik/sites/div01.php";
$_template->_user02 = "Statistik/sites/div02.php";
$_template->_user03 = "Statistik/sites/div03.php";
$_template->_user04 = "Statistik/sites/div04.php";