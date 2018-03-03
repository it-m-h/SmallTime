<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.020
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
echo "<div id='meineid'>";
echo "<form action='?action=user_update&admin_id=". $_id."' method='post' target='_self'>";
echo "<table width=100% border=0 cellpadding=3 cellspacing=1>";
echo "<tr>";
echo "<td align=left COLSPAN=3 class=td_background_top width=60>Userdaten Editieren</td>";
echo "</tr>";
echo "<tr>";
echo "<td align=left class=td_background_tag width=300>Name</td>";
echo "<td align=left class=td_background_tag ><input type='text' name='_a' value='".$_user->_name."' size='60'></td>";
echo "<td align=left class=td_background_tag width=16><img title='Name des Mitarbeiters.' src='images/icons/information.png' border=0></td>";
echo "</tr>";
echo "<tr>";
echo "<td align=left class=td_background_tag>Start - Datum</td>";
echo "<td align=left class=td_background_tag ><input type='text' name='_b' value='".@date("d.m.Y",$_user->_BeginnDerZeitrechnung)."' size='10'> (Beginn der Zeitrechnung)</td>";
echo "<td align=left class=td_background_tag width=16><img title='Einstellungsdatum, es wird nur jeder 1. des Monats unterst&uuml;tzt.' src='images/icons/information.png' border=0></td>";
echo "</tr>";
//------------------------------------------------------------------------------------
// Zeitberechnungsmodell (0 = normal, alle kumuliert, 1 = jährlich, 2 monatlich) (datei ./Data / user / userdaten.txt zeile 16 erweitern mit 0,1,2)
//------------------------------------------------------------------------------------
echo "<tr>";
echo "<td align=left class=td_background_tag>Zeitberechnungsmodell</td>";
echo "<td align=left class=td_background_tag ><select name='_m' size='1'><option value='0'";   
echo ($_user->_modell == 0) ? " selected " : "" ;
echo ">kumulierend</option><option value='1'";
echo ($_user->_modell == 1) ? " selected " : "" ;
echo ">J&auml;hrlich</option><option value='2'";
echo ($_user->_modell == 2) ?  " selected " : "" ;
echo ">Monatlich</option>	</select></td>
";
echo "<td align=left class=td_background_tag width=16><img title='&Uuml;berstungen - Zeitberechnung kumuliert, jeden Monat oder jedes Jahr zur&uuml;cksetzend.' src='images/icons/information.png' border=0></td>";
echo "</tr>";

echo "<tr>";
echo "<td align=left class=td_background_tag>Anstellung</td>";
echo "<td align=left class=td_background_tag ><input type='text' name='_c' value='".trim($_user->_SollZeitProzent)."' size='10'> %</td>";
echo "<td align=left class=td_background_tag width=16><img title='Anstellungsgrad.' src='images/icons/information.png' border=0></td>";
echo "</tr>";

echo "<tr>";
echo "<td align=left class=td_background_tag >Sollstunden pro Woche bei 100%</td>";
echo "<td align=left class=td_background_tag ><input type='text' name='_d' value='".$_user->_WochenArbeiztsZeit."' size='10'> Stunden</td>";
echo "<td align=left class=td_background_tag width=16><img title='Arbeitsstunden bei 100%.' src='images/icons/information.png' border=0></td>";
echo "</tr>";

echo "<tr>";
echo "<td align=left class=td_background_tag>Vorholzeit im Jahr</td>";
echo "<td align=left class=td_background_tag><input type='text' name='_e' value='".$_user->_Vorholzeit_pro_Jahr."' size='10'> Stunden</td>";
echo "<td align=left class=td_background_tag width=16><img title='Falls eine Vorholzeit obligatorisch ist, es wird dann als Minusstunden angezeigt.' src='images/icons/information.png' border=0></td>";
echo "</tr>";

echo "<tr>";
echo "<td align=left class=td_background_tag>Ferienguthaben pro Jahr </td>";
echo "<td align=left class=td_background_tag ><input type='text' name='_f' value='".$_user->_Ferien_pro_Jahr."' size='10'> Tage</td>";
echo "<td align=left class=td_background_tag width=16><img title='Falls keine 100% - Anstellung und bei den Arbeitstagen
halbe Tage eingestellt wird, bitte entsprechend setzen. Beispiel: 16 Tage Ferien bei 80%,
falls am Montag nicht gearbeitet wird ergibt dann 4 Wochen Ferien. Oder wenn eine
70% - Anstellung Montag - Freitag Arbeitet, ergibt das weniger Arbeitsstunden pro Tag, aber doch 5 Tage Ferien pro Woche.
' src='images/icons/information.png' border=0></td>";
echo "</tr>";
echo "<tr>";
echo "<td align=left class=td_background_tag>Guthaben - &Uuml;bertrag<br>(Stunden / Ferien)</td>";
echo "<td align=left class=td_background_tag><input type='text' name='_g1' value='".$_user->_Stunden_uebertrag."' size='10'> | <input type='text' name='_g2' value='".$_user->_Ferienguthaben_uebertrag."' size='10'></td>";
echo "<td align=left class=td_background_tag width=16><img title='Falls &Uuml;berzeitguthaben oder Ferienguthaben bei Beginn existiert, bitte hier eintragen.' src='images/icons/information.png' border=0></td>";
echo "</tr>";
echo "<tr>";
echo "<td COLSPAN=3 class=td_background_top width=60>Arbeitstage in der Woche</td>";
echo "</tr>";
//Wochentage an denen der User arbeitet und wie viel
echo "<tr>";
echo "<td align=left class=td_background_tag valign='top'>Arbeitstage</td>";
echo "<td align=left class=td_background_tag >";
echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>";
for($i = 0; $i <= 6; $i++)
{
	echo "<tr><td width='30'>";
	echo $_monat->get_wochentage($i);
	echo "</td><td>";
	echo "<input class='smallinput' type='text' name='wotag".$i."' value='".$_user->_arbeitstage[$i]."' size='4'>";
	echo "</td></tr>";
}
echo "</table>";
echo "</td>";
echo "<td align=left class=td_background_tag width='16' valign='top'><img title='Arbeitstage eintragen,
ob ein ganzer oder halber Tag gearbeitet wird. Eingabe mit Punkt und in Dezimal. (z.B. 0.5 und 1 und 0)' src='images/icons/information.png' border=0></td>";
echo "</tr>";
echo "<tr>";
echo "<td COLSPAN=3 class=td_background_top width=60>Feiertage</td>";
echo "</tr>";
echo "<tr>";
echo "<td align=left class=td_background_tag valign='top'>Feiertage:</td>";
echo "<td align=left class=td_background_tag >";
echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>";
$i          = 0;
$_feiertage = new time_feiertage($_time->_jahr, $_settings->_array[12][1], $_user->_feiertage);
$FT         = $_feiertage->getFeiertageUserEdit();
foreach($FT as $zeile)
{
	echo "\n<tr><td width='150'>". $zeile['_bez'];
	echo "</td><td width='100'>";
	echo date("d.m.Y",$zeile['_tag']);
	echo "</td><td>";
	$wahl = $zeile['_wahl'];
	$wahl = trim($wahl);
	$wahl = str_replace("/r","", $wahl);
	$wahl = str_replace("/n","", $wahl);
	if($wahl == "1")
	{
		echo '<input name="feiertag'.$zeile['_id'].'" type="checkbox" checked />';
	}
	else
	{
		echo '<input name="feiertag'.$zeile['_id'].'" type="checkbox" />';
	}
	echo "</td></tr>\n";
	$i++;
}
echo "<input type='hidden' name='anzahlFT' value='$i' >";
echo "</table>";
echo "</td>";
echo "<td align=left class=td_background_tag width='16' valign='top'><img title='Aktivieren oder deaktivieren Sie die Feiertage, je nach dem, welche g&uuml;ltig sind.' src='images/icons/information.png' border=0></td>";
echo "</tr>";
echo "<tr>";
echo "<td COLSPAN=3 class=td_background_top width=60>Zeitzuschlag</td>";
echo "</tr>";
//------------------------------------------------------------------------------------
//Arbeitszuschlag bei Abendeinsätzen oder Wochenenden
//------------------------------------------------------------------------------------
echo "<tr>";
echo "<td align=left class=td_background_tag valign='top'>Arbeitszuschlag :
<br>
Beispiel: 19.5 - 24 Uhr zu 150%
<br><br>
<b>Zur Info: </b>
<br>- nur eine Zeit pro Tag einstellbar
<br>- nicht M&ouml;glich ist: 23 - 6 Uhr
</td>";
echo "<td align=left class=td_background_tag >";

echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>";
for($i = 0; $i <= 6; $i++)
{
	echo "<tr><td width='50'>";
	echo $_monat->get_wochentage($i);
	if(!$_user->_zuschlag[$i][0]) $_user->_zuschlag[$i][0] = 0;
	if(!$_user->_zuschlag[$i][1]) $_user->_zuschlag[$i][1] = 0;
	if(!$_user->_zuschlag[$i][2]) $_user->_zuschlag[$i][2] = 0;
	echo " : </td>";
	echo "<td>";
	echo "Zeit : <input class='smallinput' type='text' name='zutagvon".$i."' value='".$_user->_zuschlag[$i][0]."' size='3'>";
	echo "</td>";
	echo "<td>";
	echo " - <input class='smallinput' type='text' name='zutagbis".$i."' value='".$_user->_zuschlag[$i][1]."' size='3'>";
	echo "</td>";
	echo "<td>";
	echo ", Zuschlag in % <input class='smallinput' type='text' name='zutagporzent".$i."' value='".$_user->_zuschlag[$i][2]."' size='3'>";
	echo "</td>";
	echo "</tr>";
}
echo "</table>";
echo "</td>";
echo "<td align=left class=td_background_tag width='16' valign='top'><img title='Arbeitszuschlag' src='images/icons/information.png' border=0></td>";
echo "</tr>";

echo "<tr>";
echo "<td COLSPAN=3 align=center class=td_background_top width=60>";
echo "<input type='submit' name='absenden' value='OK' >";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  ";
echo "<input type='submit'  name='absenden' value='CANCEL' > ";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</form>";
echo "</div>";