<?php
/*******************************************************************************
* Version 0.85
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
//-----------------------------------------------------------------------------
// Quick Time - Schnelle zeiterfassung
//-----------------------------------------------------------------------------
//echo "--".$_time->_timestamp."--";
echo "<a title='Quick Time erfassung' href='?action=quick_time&timestamp=".$_time->_timestamp."'>";
echo "<img src='./".$_template->get_templatepfad() ."images/quicktime.jpg' border=0>";
echo "</a>";
echo "<br>";
echo "<br>";
//-----------------------------------------------------------------------------
// Logout - Button anzeigen
//-----------------------------------------------------------------------------
echo "<Form action='?action=logout' method='post' target='_self'>";
echo "<input id='logout_button' src='./".$_template->get_templatepfad() ."images/logout.jpg' type='image' name='logout' value='Logout' >";
echo "</form>";
//echo "<br>";
//-----------------------------------------------------------------------------
// Anzeige der Summen aus Statistik
//-----------------------------------------------------------------------------

echo "<table width=100% border=0 cellpadding=3 cellspacing=1>";

echo "<tr>";
echo "<td class=td_background_top width=100 align=left colspan=2>Mitarbeiterdaten</td>";
echo "</tr>";

echo "<tr>";
echo "<td class=td_background_tag width=100 align=left>Name</td>";
echo "<td class=td_background_tag align=left>$_user->_name</td>";
echo "</tr>";

echo "<tr>";
echo "<td class=td_background_tag width=100 align=left>Start - Datum</td>";
echo "<td class=td_background_tag align=left>".date("d.m.Y",$_user->_BeginnDerZeitrechnung)."</td>";
echo "</tr>";

echo "<tr>";
echo "<td class=td_background_tag width=100 align=left>Anstellung</td>";
echo "<td class=td_background_tag align=left>$_user->_SollZeitProzent %</td>";
echo "</tr>";

echo "<tr>";
echo "<td class=td_background_tag width=100 align=left>Sollstd. / Wo</td>";
echo "<td class=td_background_tag align=left>$_user->_SollZeitProWoche h</td>";
echo "</tr>";


echo "<tr>";
echo "<td class=td_background_top width=100 align=left colspan=2>Aktuelle Total - Saldi</td>";
echo "</tr>";

/*
echo "<tr>";
echo "<td class=td_background_tag width=100>Vorholzeit</td>";
echo "<td class=td_background_tag >$user->_Vorholzeit_pro_Jahr h</td>";
echo "</tr>";
*/

if($_user->_modell==2) {
	$str = "Monatssaldo";
}elseif($_user->_modell==1) {
	$str = "Jahressaldo";
}else {
	$str = "Zeitsaldo";
}

	echo "<tr>";
	echo "<td class='alert";
	echo $_jahr->_saldo_t >= 0 ? " alert-success" : " alert-error";
	echo "' align=left>".$str."</td>";
	echo "<td class=td_background_tag align=left>$_jahr->_saldo_t Std.</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td class='alert";
	echo $_jahr->_saldo_F >= 0 ? " alert-success" : " alert-error";
	echo "' align=left>Feriensaldo</td>";
	echo "<td class=td_background_tag align=left>$_jahr->_saldo_F Tage</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td class='td_background_top' align=left colspan=2>Monats - Summen</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td class=td_background_tag align=left>Monat&nbsp;</td>";
	echo "<td class=td_background_tag align=left>";
	echo $_time->_monatname . " ". $_time->_jahr. "</td>";
	echo "</tr>";


	echo "<tr>";
	echo "<td class='alert";
	echo $_monat->_SummeSaldoProMonat >= 0 ? " alert-success" : " alert-error";
	echo "' align=left>Saldo</td>";
	echo "<td class=td_background_tag align=left>$_monat->_SummeSaldoProMonat Std.</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td class=td_background_tag align=left>Soll</td>";
	echo "<td class=td_background_tag align=left>$_monat->_SummeSollProMonat Std.</td>";
	echo "</tr>";

	//Absenzen anzeigen


if($_monat->_SummeFerien > 0){
echo "<tr>";
echo "<td class=td_background_wochenende width=100 align=left>Ferienbezug</td>";
echo "<td class=td_background_wochenende align=left>$_monat->_SummeFerien Tage (F)</td>";
echo "</tr>";
}
if($_monat->_SummeKrankheit > 0){
echo "<tr>";
echo "<td class=td_background_wochenende width=100 align=left>Krankheit</td>";
echo "<td class=td_background_wochenende align=left>$_monat->_SummeKrankheit Tage (K)</td>";
echo "</tr>";
}
if($_monat->_SummeUnfall > 0){
echo "<tr>";
echo "<td class=td_background_wochenende width=100 align=left>Unfall</td>";
echo "<td class=td_background_wochenende align=left>$_monat->_SummeUnfall Tage (U)</td>";
echo "</tr>";
}
if($_monat->_SummeMilitaer > 0){
echo "<tr>";
echo "<td class=td_background_wochenende width=100 align=left>Milit&auml;r</td>";
echo "<td class=td_background_wochenende align=left>$_monat->_SummeMilitaer Tage (M)</td>";
echo "</tr>";
}
if($_monat->_SummeIntern > 0){
echo "<tr>";
echo "<td class=td_background_wochenende width=100 align=left>Intern</td>";
echo "<td class=td_background_wochenende align=left>$_monat->_SummeIntern Tage (I)</td>";
echo "</tr>";
}
if($_monat->_SummeWeiterbildung > 0){
echo "<tr>";
echo "<td class=td_background_wochenende width=100 align=left>Weiterbildung</td>";
echo "<td class=td_background_wochenende align=left>$_monat->_SummeWeiterbildung Tage (W)</td>";
echo "</tr>";
}
if($_monat->_SummeExtern > 0){
echo "<tr>";
echo "<td class=td_background_wochenende width=100 align=left>Extern</td>";
echo "<td class=td_background_wochenende align=left>$_monat->_SummeExtern Tage (E)</td>";
echo "</tr>";
}
echo "</table>";
/*//-----------------------------------------------------------------------------
// Anzeige eines Monatskalenders
//-----------------------------------------------------------------------------
echo "<br>";
echo monatskalender(0);
echo "<br>";
echo monatskalender(+1);
//-----------------------------------------------------------------------------
//Seitenladezeit und Copyright
//-----------------------------------------------------------------------------
$_time_end = explode(" ",microtime());
$_time_end = $_time_end[1] + $_time_end[0];
// ^^ Jetzt wird wieder die Aktuelle Zeit gemessen
$_zeitmessung = $_time_end - $_start_time;
// ^^ Endzeit minus Startzeit = die Differenz der beiden Zeiten
$_zeitmessung = substr($_zeitmessung,0,4);
//echo "-----------------".$_time_end." - ".$_start_time." = ". $_zeitmessung. " Sekunden";
// ^^ Die Zeit wird auf X Kommastellen gekürzt
echo "<br><hr color=#DFDFDF size=1><font size='-2'>Ladezeit der Seite: $_zeitmessung Sekunden.</font><br>";
echo $_copyright;*/
?>
</br>
<link rel="stylesheet" media="screen" href="./css/calendar_js.css" type="text/css" />
<script type="text/javascript" src="./js/calendar_js.js"></script>
<div id="calendar"></div>
 