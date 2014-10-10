<?php
/********************************************************************************
* Small Time - Plugin - Kalender als XLS ausgeben
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
$_datenarr		= array();
$_farbe 			= array();
$_datenarr[0][0] 	= "ID";
$_farbe[0][0] 		= "95ade8";
$_datenarr[0][1] 	= "Name";
$_farbe[0][1] 		= "95ade8";
$_datenarr[0][2] 	= "Total - Std.";
$_farbe[0][2] 		= "95ade8";
$_datenarr[0][3] 	= "Jahres - Std.";
$_farbe[0][3] 		= "d89696";
$_benutzer 		= file("./Data/users.txt");
unset($_benutzer[0]);
$i=1;
foreach($_benutzer as $string){	
	$string = explode(";", $string);
	if(file_exists("./Data/".$string[0]."/Timetable/total.txt")){
		$totale = file("./Data/".$string[0]."/Timetable/total.txt");
		$time = round($totale[0],2);
	}else{
		$time = "xxx";
	}		
	$_userdaten_tmp = file("./Data/".$string[0]."/userdaten.txt");	
	$_datenarr[$i][0] = $i;
	$_farbe[$i][0] = "d89696";
	$_datenarr[$i][1] = $_userdaten_tmp[0];
	$_farbe[$i][1] = "";
	$_datenarr[$i][2] = $time;
	$_farbe[$i][2] = "";
	$_file 		= "./Data/".$string[0]."/Timetable/" .$_time->_jahr;
	$_file_absenz	= "./Data/".$string[0]."/Timetable/A" . $_time->_jahr;
	$_file_abstxt 	= "./Data/".$string[0]."/absenz.txt";
	$_sp = 4;
	if(file_exists($_file_abstxt)){
		$tmparr = file($_file_abstxt);
		foreach($tmparr as $zeile){
			$spalte = explode(";", $zeile);
			//-------------------------------------------------------------------------------------------
			// Spaltenüberschriften Absenzen
			//-------------------------------------------------------------------------------------------
			$_datenarr[0][$_sp] = $spalte[1];
			$_farbe[0][$_sp] = "d89696";
			$_sp++;
		}
	}	
	//-------------------------------------------------------------------------------------------
	// Saldo laden
	//-------------------------------------------------------------------------------------------
	$_datenarr[$i][3] =0;	
	if(file_exists($_file)){
		$tmparr = file($_file);
		for($j=1; $j<=12; $j++){
			$werte = explode(";", $tmparr[($j-1)]);
			$_datenarr[$i][3] += $werte[0];
		}	
	}	
	
	//-------------------------------------------------------------------------------------------
	// Absenzen laden
	//-------------------------------------------------------------------------------------------
	//0 werte füllen:
	for($c=4;$c<$_sp;$c++){
		$_datenarr[$i][$c] =0;	
	}
	if(file_exists($_file_absenz)){
		$tmparr = file($_file_absenz);
		$arrabs = NULL;
		foreach($tmparr as $zeile){
			$werte = explode(";", $zeile);
			for($c=4;$c<$_sp;$c++){
				if($werte[1] == $_datenarr[0][$c]) {
					$_datenarr[$i][$c] += $werte[2];
				}else{
				}
			}			
		}	
	}
	
	$i++;
}
//-------------------------------------------------------------------------
// Tabelle ausgeben
//-------------------------------------------------------------------------
echo "<table>";
$y=0;
foreach($_datenarr as $_zeilen){
	echo "<tr>";
	$z=0;
	foreach ($_zeilen as $_spalten){
		echo "<td bgcolor='".$_farbe[$y][$z]."'>";
		echo $_spalten;
		echo "</td>";
		$z++;
	}
	echo "</tr>";
	$y++;
}
echo "</table>";