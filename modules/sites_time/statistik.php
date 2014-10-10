<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.898
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
echo "<table width=100% border=0 cellpadding=3 cellspacing=1>";
echo "	<tr>";
echo "		<td class=td_background_top width=100 align=left colspan=2>Mitarbeiterdaten</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td class=td_background_tag width=100 align=left>Name</td>";
echo "		<td class=td_background_tag align=left>$_user->_name</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td class=td_background_tag width=100 align=left>Start - Datum</td>";
echo "		<td class=td_background_tag align=left>".date("d.m.Y",$_user->_BeginnDerZeitrechnung)."</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td class=td_background_tag width=100 align=left>Anstellung</td>";
echo "		<td class=td_background_tag align=left>$_user->_SollZeitProzent %</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td class=td_background_tag width=100 align=left>Sollstd. / Wo</td>";
echo "		<td class=td_background_tag align=left>$_user->_SollZeitProWoche h</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td class=td_background_top width=100 align=left colspan=2>Total - Saldi ende Monat</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td class=td_background_info width=100 align=left>Zeitsaldo</td>";
echo "		<td class=td_background_tag align=left>$_jahr->_saldo_t Std.</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td class=td_background_info width=100 align=left>Feriensaldo</td>";
echo "		<td class=td_background_tag align=left>$_jahr->_saldo_F Tage</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td class=td_background_top width=100 align=left colspan=2>Monats - Summen</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td class=td_background_tag width=100 align=left>Monat&nbsp;</td>";
echo "		<td class=td_background_tag align=left>";
echo $_time->_monatname . " ". $_time->_jahr. "</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td class=td_background_info width=100 align=left>Saldo</td>";
echo "		<td class=td_background_tag align=left>$_monat->_SummeSaldoProMonat Std.</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td class=td_background_tag width=100 align=left>Soll</td>";
echo "		<td class=td_background_tag align=left>$_monat->_SummeSollProMonat Std.</td>";
echo "	</tr>";
//Absenzen anzeigen
if($_monat->_SummeFerien > 0){
echo "	<tr>";
echo "		<td class=td_background_wochenende width=100 align=left>Ferienbezug</td>";
echo "		<td class=td_background_wochenende align=left>$_monat->_SummeFerien Tage (F)</td>";
echo "	</tr>";
}
if($_monat->_SummeKrankheit > 0){
echo "	<tr>";
echo "		<td class=td_background_wochenende width=100 align=left>Krankheit</td>";
echo "		<td class=td_background_wochenende align=left>$_monat->_SummeKrankheit Tage (K)</td>";
echo "	</tr>";
}
if($_monat->_SummeUnfall > 0){
echo "	<tr>";
echo "		<td class=td_background_wochenende width=100 align=left>Unfall</td>";
echo "		<td class=td_background_wochenende align=left>$_monat->_SummeUnfall Tage (U)</td>";
echo "	</tr>";
}
if($_monat->_SummeMilitaer > 0){
echo "	<tr>";
echo "		<td class=td_background_wochenende width=100 align=left>Milit&auml;r</td>";
echo "		<td class=td_background_wochenende align=left>$_monat->_SummeMilitaer Tage (M)</td>";
echo "	</tr>";
}
if($_monat->_SummeIntern > 0){
echo "	<tr>";
echo "		<td class=td_background_wochenende width=100 align=left>Intern</td>";
echo "		<td class=td_background_wochenende align=left>$_monat->_SummeIntern Tage (I)</td>";
echo "	</tr>";
}
if($_monat->_SummeWeiterbildung > 0){
echo "	<tr>";
echo "		<td class=td_background_wochenende width=100 align=left>Weiterbildung</td>";
echo "		<td class=td_background_wochenende align=left>$_monat->_SummeWeiterbildung Tage (W)</td>";
echo "	</tr>";
}
if($_monat->_SummeExtern > 0){
echo "	<tr>";
echo "		<td class=td_background_wochenende width=100 align=left>Extern</td>";
echo "		<td class=td_background_wochenende align=left>$_monat->_SummeExtern Tage (E)</td>";
echo "	</tr>";
}
echo "</table>";