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
$_datenarr[0][2] 	= "Std.";
$_farbe[0][2] 		= "95ade8";
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
	$i++;
}

for($i=1; $i<count($_monat->_MonatsArray); $i++){
	$_datenarr[0][($i+2)] = $i;
	if($_monat->_MonatsArray[$i][2]==6 or $_monat->_MonatsArray[$i][2]==0 or $_monat->_MonatsArray[$i][5] >=0){
		// Wochenende
		$_farbe[0][($i+2)] = "7f7f7f";	
	}else{
		// Top
		$_farbe[0][($i+2)] = "95ade8";
	}
	$tmp = explode(".", $_monat->_MonatsArray[$i][1]);
}

$_benutzer = file("./Data/users.txt");
$y=1;
unset($_benutzer[0]);
foreach($_benutzer as $string){
	echo '<tr>';
	$string = explode(";", $string);
	$_userdaten_tmp = file("./Data/".$string[0]."/userdaten.txt");
	//Absenzen laden
	$_user_absenzen = array();
	if (file_exists("./Data/".$string[0]."/Timetable/A". $_time->_jahr)){
		$_user_absenzen = file("./Data/".$string[0]."/Timetable/A". $_time->_jahr);
	}
	// Monatsanzeige
	for($i=1; $i<count($_monat->_MonatsArray); $i++){
		$tmp = explode(".", $_monat->_MonatsArray[$i][1]);
		// Absenzeintrag anzeigen
		$_text="";
		$z=0;
		if($_user_absenzen){
			foreach($_user_absenzen as $_eintrag){
				$_eintrag = explode(";", $_eintrag);
				if ($_eintrag[0] == $_monat->_MonatsArray[$i][0]){
					$_text=$_eintrag[1];
				}
				$z++;
			} 
		}			
		//Arbeitstag, falls nein Wochenende anzeigen
		$_arbeitstag = explode(";",$_userdaten_tmp[7]);
		if($_arbeitstag[$_monat->_MonatsArray[$i][2]]==0 or $_monat->_MonatsArray[$i][2]==6 or $_monat->_MonatsArray[$i][5] >=0){
			// Wochenende
			$_farbe[$y][($i+2)] = "7f7f7f";
		}elseif($_text){
			// Info - z.B. bei Abwesenheit
			$_farbe[$y][($i+2)] = "d89696";
		}else{
			$_prozent = $_arbeitstag[$_monat->_MonatsArray[$i][2]];
			if($_prozent <= 0.5){
				// kein 100% Arbeitstag';
				$_farbe[$y][($i+2)] = "ebebeb";
			}else{
			 	//Arbeitstag	
			 	$_farbe[$y][($i+2)] = "";
			}
				
		}
		// Arbeitstag - in Prozent wenn nicht 0 oder 1
		if($_arbeitstag[$_monat->_MonatsArray[$i][2]]>0 && $_arbeitstag[$_monat->_MonatsArray[$i][2]]< 1 && !$_text){
			$_datenarr[$y][($i+2)] = $_arbeitstag[$_monat->_MonatsArray[$i][2]];
		}else{
			$_datenarr[$y][($i+2)] = $_text;
		}			
	}
	$y++;
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