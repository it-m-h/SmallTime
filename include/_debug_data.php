<?php
/*******************************************************************************
* Debug der Applikation
/*******************************************************************************
* Version 0.8
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
// ----------------------------------------------------------------------------
// Anzeige der Daten für Entwickler 
// ----------------------------------------------------------------------------
if (in_array(9,$show)){
	txt("ALLE VARIABLEN AUFLISTEN");
	echo '<pre>';
	print_r($GLOBALS);
	echo '</pre>';
}
if (in_array(1,$show))
{
	txt("Daten");
	echo "<hr color=red>";
	//-----------------------------------------------------------------
	txt("Daten aus der Settings - Datei (\$_settings->_array)");
	$zeig = new time_show($_settings->_array);
	//print_r ($_setting->_array[0]);
	//echo $_settings->_array[1][1];
	//-----------------------------------------------------------------
	txt("Feiertage für die ganze Firma (\$_settings->_feiertage)");
	$zeig = new time_show($_settings->_feiertage);
	//-----------------------------------------------------------------
	txt("Alle User (\$_users->_array)");
	$zeig = new time_show($_users->_array);
	//echo $_users->_array[1][1];
	//print_r($_users->_array);
	//-----------------------------------------------------------------
	txt("Alle Gruppen (\$_groups->_array)");
	$zeig = new time_show($_groups->_array);
	//print_r($_groups->_array);
	//-----------------------------------------------------------------
	txt("Userdaten: (\$_userdaten)");
	if ($_userdaten) $zeig = new time_show($_userdaten);
	//print_r($_groups->_array);
	//-----------------------------------------------------------------
	echo "<hr color=red>";
}
if (in_array(7,$show)) {
	txt("\$_template - Variablen:");
	showClassVar($_template);
	txt("<hr color=red>");
}
?>