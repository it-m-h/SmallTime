<?php
/*******************************************************************************
* Version 0.9.001
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
//-----------------------------------------------------------------------------
// Quick Time - schnelle Zeiterfassung
//-----------------------------------------------------------------------------
echo "<a title='Quick Time erfassung' href='?action=quick_time&timestamp=".$_time->_timestamp."'>";
echo "<img id='quicktime' src='./".$_template->get_templatepfad() ."images/quicktime.jpg' border=0>";
echo "</a>";
echo "<br>";
echo "<br>";
//-----------------------------------------------------------------------------
// Logout - Button anzeigen
//-----------------------------------------------------------------------------
echo "<form action='?action=logout' method='post' target='_self'>";
echo "<input id='logout_button' src='./".$_template->get_templatepfad() ."images/logout.jpg' type='image' name='logout' value='Logout' >";
echo "</form>";
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
echo "<td class=td_background_top width=100 align=left colspan=2>Total - Saldi ende Monat</td>";
echo "</tr>";

if($_user->_modell==2){
	$str = "Monatssaldo";
}elseif($_user->_modell==1){
	$str = "Jahressaldo";
}else{
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
// Falls Settings - ferien nur bis heute Berechnet werden zukünftige anzeigen lassen
if($_settings->_array[27][1]){
	echo "<tr>";
	echo "<td class='td_background_tag' align=left>geplante Ferien</td>";
	echo "<td class=td_background_tag align=left>$_jahr->_summe_Fz Tage</td>";
	echo "</tr>";
}
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
//-------------------------------------------------------------------------
// Summen der Absenzen anzeigen (ab 0.87 erweiterbar pro Mitarbeiter)
//-------------------------------------------------------------------------
foreach($_monat->get_calc_absenz() as $werte){
	if($werte[3]<>0){
		echo "<tr>";
		echo "<td class=td_background_wochenende align=left>$werte[0]</td>";
		echo "<td class=td_background_wochenende align=left>";	
		echo "$werte[3] Tage ($werte[1])";		
		echo "</td>";
		echo "</tr>";			
	}
}
echo "</table>";
?>
</br>
<link rel="stylesheet" media="screen" href="./css/calendar_js.css" type="text/css" />
<script type="text/javascript" src="./js/calendar_js.js"></script>
<div id="calendar"></div> 