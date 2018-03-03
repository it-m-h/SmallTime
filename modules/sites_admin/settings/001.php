<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.016
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
$_info[0]="Title der Webseite";
$_info[1]="meta:Author";
$_info[2]="meta:Editor";
$_info[3]="meta:Content-language";
$_info[4]="meta:Content-Type";
$_info[5]="meta:Content-Script-Type";
$_info[6]="meta:Page-type";
$_info[7]="meta:Page-topic";
$_info[8]="meta:Description";
$_info[9]="meta:Keywords";
$_info[10]="meta:Copyright";
$_info[11]="Monatsanzeige im Menue";
$_info[12]="1=Schweiz / 2=Deutschland / 3=Oesterreich / 4=Lichtenstein";
$_anzeige= "";
$_anzeige = $_anzeige .'<form method="POST" action="?action=settings">';
$_anzeige = $_anzeige . '<table border="0" width="100%" cellpadding=3 cellspacing=1>';
$y=0;
foreach($_settings->_array as $_zeile){
	//------------------------------------------------------------------------------------
	//Webseiten - Einstellungen
	//------------------------------------------------------------------------------------
	$_anzeige = $_anzeige . "<tr width=50%>";
	if($y<12){
		$_anzeige = $_anzeige . "<td class='td_background_tag' align=left width=180>". $_zeile[0] . "</td>";
		$_anzeige = $_anzeige . '<td class="td_background_tag"><input class="biginput" type="text" name="'.$y.'" value="'.$_zeile[1].'" size="74"></td>';
		$_anzeige = $_anzeige . "<td class='td_background_tag'><img title='".$_zeile[2]."' src='images/icons/information.png' border=0></td></tr>";
	}
	//------------------------------------------------------------------------------------
	//Landeseinstellung für Bundesfeiertag - Zeile 13
	//------------------------------------------------------------------------------------
	if($y==12){
		$_anzeige = $_anzeige . "<tr><td colspan='3'><hr></td></tr>";
		$_anzeige = $_anzeige . "<tr class='td_background_tag'><td align=left>". $_settings->_array[$y][0] . "</td>"        ;
		if($_settings->_array[$y][1]==1) $check1=" checked ";
		if($_settings->_array[$y][1]==2) $check2=" checked ";
		if($_settings->_array[$y][1]==3) $check3=" checked ";
		if($_settings->_array[$y][1]==4) $check4=" checked ";
		$_anzeige = $_anzeige . '<td><table border="0" cellspacing="0" cellpadding="0" ><tr>
		<td><input type="radio" value="1" name="'.$y.'" title="Switzerland"'. @$check1 .'></td>
		<td><img src="images/country/24/Switzerland.png"></td>
		<td><input type="radio" value="2" name="'.$y.'" title="Germany"'. @$check2 .'></td>
		<td><img src="images/country/24/Germany.png"></td>
		<td><input type="radio" value="3" name="'.$y.'" title="Austria"'. @$check3 .'></td>
		<td><img src="images/country/24/Austria.png"></td>
		<td><input type="radio" value="4" name="'.$y.'" title="Liechtenstein"'. @$check4 .'></td>
		<td><img src="images/country/24/Liechtenstein.png"></td>
		</tr></table></td>';
		$_anzeige = $_anzeige .  "<td><img title='".$_settings->_array[$y][2]."' src='images/icons/information.png' border=0></td></tr>";
		$_anzeige = $_anzeige . "<tr><td colspan='3'><hr></td></tr>";
	}
	//------------------------------------------------------------------------------------
	//Berechtigungs - Einstellungen
	//------------------------------------------------------------------------------------
	if($y>=13 && $y<=19){
		$_anzeige = $_anzeige . "<tr><td align=left class='td_background_tag'>". $_settings->_array[$y][0] . "</td>"        ;
		if($_settings->_array[$y][1]==1){ $check1=" checked ";}else{$check1="";}
		if($_settings->_array[$y][1]==0){ $check2=" checked ";}else{$check2="";}
		$_anzeige = $_anzeige . '<td class="td_background_tag"><table border="0" cellspacing="0" cellpadding="0" ><tr>
		<td><input type="radio" value="1" name="'.$y.'" '. $check1 .'></td>
		<td>ja</td>
		<td><input type="radio" value="0" name="'.$y.'" '. $check2 .'></td>
		<td>nein</td>
		</tr></table></td>';
		$_anzeige = $_anzeige .  "<td class=td_background_tag><img title='".$_settings->_array[$y][2]."' src='images/icons/information.png' border=0></td></tr>";
	}
	$y++;
}
//------------------------------------------------------------------------------------
// Dürfen die Design gewählt werden
//------------------------------------------------------------------------------------
$_anzeige = $_anzeige . "<td class=td_background_tag align=left align=left>". $_settings->_array[24][0] . "</td>";
if($_settings->_array[24][1]==1){ $check1=" checked ";}else{$check1="";}
if($_settings->_array[24][1]==0){ $check2=" checked ";}else{$check2="";}
$_anzeige = $_anzeige . '<td class="td_background_tag"><table border="0" cellspacing="0" cellpadding="0" ><tr>
		<td><input type="radio" value="1" name="24" '. $check1 .'></td>
		<td>ja</td>
		<td><input type="radio" value="0" name="24" '. $check2 .'></td>
		<td>nein</td>
		</tr></table></td>';
$_anzeige = $_anzeige .  "<td class=td_background_tag align=left><img title='".$_settings->_array[24][2]."' src='images/icons/information.png' border=0></td></tr>";
 //------------------------------------------------------------------------------------
 // Admin Full Edit
//------------------------------------------------------------------------------------
$_anzeige = $_anzeige . "<td class=td_background_tag align=left align=left>". $_settings->_array[26][0] . "</td>";
if($_settings->_array[26][1]==1){ $check1=" checked ";}else{$check1="";}
if($_settings->_array[26][1]==0){ $check2=" checked ";}else{$check2="";}
$_anzeige = $_anzeige . '<td class="td_background_tag"><table border="0" cellspacing="0" cellpadding="0" ><tr>
		<td><input type="radio" value="1" name="26" '. $check1 .'></td>
		<td>ja</td>
		<td><input type="radio" value="0" name="26" '. $check2 .'></td>
		<td>nein</td>
		</tr></table></td>';
$_anzeige = $_anzeige .  "<td class=td_background_tag align=left><img title='".$_settings->_array[26][2]."' src='images/icons/information.png' border=0></td></tr>";
//------------------------------------------------------------------------------------
//Zeit Edit - Einstellungen, wie lange zurück darf der User Zeiten editieren - Zeile 23
//------------------------------------------------------------------------------------
$_anzeige = $_anzeige . "<tr><td colspan='3' class=td_background_top>Berechtigung f&uuml;r Zeit edit der Mitarbeiter. (inaktiv wenn oben nein eingestellt ist)</td></tr>";
$_anzeige = $_anzeige . "<tr width=50%>";
$_anzeige = $_anzeige . "<td class=td_background_tag align=left align=left>". $_settings->_array[23][0] . "</td>";
$_anzeige = $_anzeige . '<td class=td_background_tag align=left><input type="text" name="23" value="'.$_settings->_array[23][1].'" size="2"> (Wie viele Tage zur&uuml;ck darf eine Zeit ver&auml;ndert werden)</td>';
$_anzeige = $_anzeige . "<td class=td_background_tag align=left><img title='".$_settings->_array[23][2]."' src='images/icons/information.png' border=0></td></tr>";
//------------------------------------------------------------------------------------
// Quick Time runden auf Minuten - Zeile 25
//------------------------------------------------------------------------------------
$_anzeige = $_anzeige . "<tr><td colspan='3' class=td_background_top>QuickTime runden </td></tr>";
$_anzeige = $_anzeige . "<tr width=50%>";
$_anzeige = $_anzeige . "<td class=td_background_tag align=left align=left>". $_settings->_array[25][0] . "</td>";
$_anzeige = $_anzeige . '<td class=td_background_tag align=left><input type="text" name="25" value="'.$_settings->_array[25][1].'" size="2"> (Minuten - Rundung)</td>';
$_anzeige = $_anzeige . "<td class=td_background_tag align=left><img title='".$_settings->_array[25][2]."' src='images/icons/information.png' border=0></td></tr>";
//------------------------------------------------------------------------------------
//Drucken - Einstellungen, bis zu welchem Datum der userDrucken darf - Zeile 20
//------------------------------------------------------------------------------------
$_anzeige = $_anzeige . "<tr><td colspan='3' class=td_background_top>Berechtigung f&uuml;r Drucken der Mitarbeiter.</td></tr>";
$_anzeige = $_anzeige . "<tr width=50%>";
$_anzeige = $_anzeige . "<td class=td_background_tag align=left align=left>". $_settings->_array[20][0] . "</td>";
$_anzeige = $_anzeige . '<td class=td_background_tag align=left><input type="text" name="20" value="'.$_settings->_array[20][1].'" size="2"> (Bis zum XX. des folgenden Monats aktiv, sichtbar)</td>';
$_anzeige = $_anzeige . "<td class=td_background_tag align=left><img title='".$_settings->_array[20][2]."' src='images/icons/information.png' border=0></td></tr>";

//------------------------------------------------------------------------------------
//Automatische Pausen - Zeile 21 und 22 - ab Version 0.9.007 separate Einstellmöglichkeit
//------------------------------------------------------------------------------------
/*
$_anzeige = $_anzeige . "<tr><td colspan='3' class=td_background_top>Einstellungen f&uuml;r automatische Pausen ab einer Stempelzeit von X Stunden.</td></tr>";
$_anzeige = $_anzeige . "<tr width=50%>";
$_anzeige = $_anzeige . "<td class=td_background_tag align=left align=left>". $_settings->_array[21][0] . "</td>";
$_anzeige = $_anzeige . '<td class=td_background_tag align=left><input type="text" name="21" value="'.$_settings->_array[21][1].'" size="2"> (Automatische Pause, 0=inaktiv, Dez. z.B. 3.75)</td>';
$_anzeige = $_anzeige . "<td class=td_background_tag align=left><img title='".$_settings->_array[21][2]."' src='images/icons/information.png' border=0></td></tr>";
$_anzeige = $_anzeige . "<tr width=50%>";
$_anzeige = $_anzeige . "<td class=td_background_tag align=left align=left>". $_settings->_array[22][0] . "</td>";
$_anzeige = $_anzeige . '<td class=td_background_tag align=left><input type="text" name="22" value="'.$_settings->_array[22][1].'" size="2"> (Dezimalangabe z.B. 0.75)</td>';
$_anzeige = $_anzeige . "<td class=td_background_tag align=left><img title='".$_settings->_array[22][2]."' src='images/icons/information.png' border=0></td></tr>";
*/
//------------------------------------------------------------------------------------
// Absenzen - Berechnung (alle oder nur bis zum aktuellen Datum) (Zeile 28)
//------------------------------------------------------------------------------------
$_anzeige = $_anzeige . "<tr><td colspan='3' class=td_background_top>Absenzen - Berechnung : alle oder nur bis zum aktuellen Datum</td></tr>";
$_anzeige = $_anzeige . "<tr width=50%>";
$_anzeige = $_anzeige . "<td class=td_background_tag align=left align=left>". $_settings->_array[27][0] . "</td>";
if($_settings->_array[27][1]==1){ $check1=" checked ";}else{$check1="";}
if($_settings->_array[27][1]==0){ $check2=" checked ";}else{$check2="";}
		$_anzeige = $_anzeige . '
		<td class=td_background_tag align=left>
			<table border="0" cellspacing="0" cellpadding="0" ><tr>
				<td><input type="radio" value="1" name="27" '. $check1 .'></td>
				<td>nur bis heute</td>
				<td><input type="radio" value="0" name="27" '. $check2 .'></td>
				<td>alle eingetragenen</td>
			</tr></table>
		</td>';
$_anzeige = $_anzeige .  "<td class=td_background_tag align=left><img title='".$_settings->_array[27][2]."' src='images/icons/information.png' border=0></td></tr>";

//------------------------------------------------------------------------------------
// Absenzen - Arbeitszeit wird von der Absenz abgezogen (Zeile 29)
//------------------------------------------------------------------------------------
$_anzeige = $_anzeige . "<tr><td colspan='3' class=td_background_top>Absenzen - Arbeitszeit wird von der Absenz abgezogen</td></tr>";
$_anzeige = $_anzeige . "<tr width=50%>";
$_anzeige = $_anzeige . "<td class=td_background_tag align=left align=left>". $_settings->_array[28][0] . "</td>";
if($_settings->_array[28][1]==1){ $check1=" checked ";}else{$check1="";}
if($_settings->_array[28][1]==0){ $check2=" checked ";}else{$check2="";}
$_anzeige = $_anzeige . '
		<td class=td_background_tag align=left>
			<table border="0" cellspacing="0" cellpadding="0" ><tr>
				<td><input type="radio" value="1" name="28" '. $check1 .'></td>
				<td>ja (Standard)</td>
				<td><input type="radio" value="0" name="28" '. $check2 .'></td>
				<td>nein,  ACHTUNG: <img title="Mit dieser Einstellung könnten Ferien in Überzeit umgewandelt werden. Bitte beachten Sie die gesetzlichen Richtlinien!" src="images/icons/information.png" border="0"></td>
			</tr></table>
		</td>';
$_anzeige = $_anzeige .  "<td class=td_background_tag align=left><img title='".$_settings->_array[28][2]."' src='images/icons/information.png' border=0></td></tr>";


$y--;
$_anzeige = $_anzeige . "	
	<tr><td class=td_background_heute colspan='3' align=center >
		<input name=anzahl value=". $y ." type=hidden>
		<input name=senden value=senden type=submit>
	</td></tr>";
$_anzeige = $_anzeige . "</table>";
$_anzeige = $_anzeige . "</form>";
echo $_anzeige;