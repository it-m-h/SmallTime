<?php

/********************************************************************************
 * Small Time
/*******************************************************************************
* Version 0.9.205
 * Author:  IT-Master
 * www.it-master.ch / info@it-master.ch
 * Copyright (c), IT-Master, All rights reserved
 *******************************************************************************/
//$_jahr = new time_jahr($_user->_ordnerpfad, 0, $_user->_BeginnDerZeitrechnung, $_user->_Stunden_uebertrag, $_user->_Ferienguthaben_uebertrag, $_user->_Ferien_pro_Jahr, $_user->_Vorholzeit_pro_Jahr);
//-----------------------------------------------------------------------------
// Anzeige der Summen aus Statistik
//-----------------------------------------------------------------------------
echo "<table width=100% border=0 cellpadding=3 cellspacing=1 >";
echo "<tr>";
echo "<td class='td_background_top' width=100 align=left colspan=2>Aktuelle Total - Saldo</td>";
echo "</tr>";

echo "<tr>";
echo "<td class='alert";
echo $_jahr->_saldo_t >= 0 ? " alert-success" : " alert-error";
echo "'  width=100 align=left>Zeitsaldo</td>";
echo "<td class=td_background_tag align=left>$_jahr->_saldo_t Std.</td>";
echo "</tr>";

echo "<tr>";
echo "<td class='alert";
echo $_jahr->_saldo_F >= 0 ? " alert-success" : " alert-error";
echo "' width=100 align=left>Feriensaldo</td>";
echo "<td class=td_background_tag align=left>$_jahr->_saldo_F Tage
<span style='color: #a1a1a1;font-size: 11px;'> / Achtung: in der unteren Tabelle werden nur die effektiv bezogenen Ferien angezeigt und berechnet.</style></td>";
echo "</tr>";
echo "</table>";
echo "<br>";

echo "<table width=100% border=0 cellpadding=3 cellspacing=1>";
echo "<tr>";
echo "<td class=td_background_top width=100 align=left colspan=2>Grund - Einstellungen</td>";
echo "</tr>";

echo "<tr>";
echo "<td class=td_background_top width=100 align=left>Ferien:</td>";
echo "<td class=td_background_tag align=left>$_user->_Ferien_pro_Jahr Tage / Jahr</td>";
echo "</tr>";

echo "<tr>";
echo "<td class=td_background_top width=100 align=left>&Uuml;bertrag F:</td>";
echo "<td class=td_background_tag align=left>$_user->_Ferienguthaben_uebertrag Tage (Ferienguthaben bei Beginn der Zeitrechnung)</td>";
echo "</tr>";

echo "<tr>";
echo "<td class=td_background_top width=100 align=left>&Uuml;bertrag T:</td>";
echo "<td class=td_background_tag align=left>$_user->_Stunden_uebertrag Std. (Stundenguthaben bei Beginn der Zeitrechnung)</td>";
echo "</tr>";

echo "<tr>";
echo "<td class=td_background_top width=100 align=left>Vorholzeit:</td>";
echo "<td class=td_background_tag align=left>$_user->_Vorholzeit_pro_Jahr Std. / Jahr</td>";
echo "</tr>";

echo "</tr>";
echo "</table>";
echo "<br>";

$monate = explode(";", $_settings->_array[11][1]);
$y      = $_jahr->_startjahr;
$_now   = date("Y", time());
$_to    = $_jahr->_startjahr;
$_starttime = $_user->_BeginnDerZeitrechnung;
$_endtime = $_user->_EndeDerZeitrechnung;
$_kumulativ = (strstr(trim($_user->_modell), '0') or trim($_user->_modell) == '0');
//----------------------------------------------------------------------------------------------


$anzeige = array();
for ($year = $_now; $year >= $_to; $year--) {
	for ($month = 0; $month < 12; $month++) {
		$_isAfterEnd = time_user::is_month_after_end($year, $month + 1, $_endtime);
		$_monthSaldo = 0;
		$_monthFerien = 0;
		$_monthWork = 0;
		$_monthSoll = 0;
		if (!$_isAfterEnd && isset($_jahr->_data[$year][$month])) {
			$_monthSaldo = floatval($_jahr->_data[$year][$month][0] ?? 0);
			$_monthFerien = floatval($_jahr->_data[$year][$month][1] ?? 0);
			$_monthWork = floatval($_jahr->_data[$year][$month][2] ?? 0);
			$_monthSoll = floatval($_jahr->_data[$year][$month][3] ?? 0);
		}
		//Zeiten eintragen
		$anzeige[$year]['Saldo'][$month] 	= $_monthSaldo;	// Saldo im Monat
		$anzeige[$year]['Ferien'][$month] 	= $_monthFerien;	// Ferien im Monat
		$anzeige[$year]['Work'][$month] 	= $_monthWork;	// Gearbeitet
		$anzeige[$year]['Soll'][$month] 		= $_monthSoll;	// Sollstunden
		//Summen eintragen
		@$anzeige[$year]['Saldo'][12] 		+= $_monthSaldo;
		@$anzeige[$year]['Ferien'][12] 		+= $_monthFerien;
		@$anzeige[$year]['Work'][12] 		+= $_monthWork;
		@$anzeige[$year]['Soll'][12] 		+= $_monthSoll;
		//Monatsname und Link
		$_tempstamp = mktime(0, 0, 0, $month + 1, 1, $year);
		$monatslink = "
		<table width='100%' hight='100%' border='0' cellpadding='2' cellspacing='0'>
		<tr>
		<td width='18' valign='middle'>
		<img src='images/icons/calendar_view_month.png' border=0>
		</td><td valign='middle'>
		<a title='Monat " . $monate[$month] . "' href='?action=show_time&admin_id=" . $_SESSION['id'] . "&timestamp=" . $_tempstamp . "'>" . $monate[$month] . "</a>
		</td>
		</tr>
		</table>";
		$anzeige[$year]['Monat'][$month] = $monatslink;
		$anzeige[$year]['Auszahlung'][$month] = 0;
	}
}
//----------------------------------------------------------------------------------------------
// Auszahlungen eintragen
if (is_array($auszahlung->_arr_ausz)) {
	for ($u = 0; $u < count($auszahlung->_arr_ausz); $u++) {
		(isset($auszahlung->_arr_ausz[$u][1])) ? $_tmp_ausz_y = trim($auszahlung->_arr_ausz[$u][1]) : $_tmp_ausz_y = 0;
		(isset($auszahlung->_arr_ausz[$u][0])) ? $_tmp_ausz_m = trim($auszahlung->_arr_ausz[$u][0]) : $_tmp_ausz_m = 0;
		$_tmp_ausz_m--;
		(isset($auszahlung->_arr_ausz[$u][2])) ? $_tmp_ausz_a = trim($auszahlung->_arr_ausz[$u][2]) : $_tmp_ausz_a = 0;
		$_tmp_ausz_a = str_ireplace('\r', '', $_tmp_ausz_a);
		$_tmp_ausz_a = str_ireplace('\n', '', $_tmp_ausz_a);

		if (!time_user::is_month_after_end(intval($_tmp_ausz_y), intval($_tmp_ausz_m) + 1, $_endtime) && isset($anzeige[$_tmp_ausz_y])) {
			$anzeige[$_tmp_ausz_y]['Auszahlung'][$_tmp_ausz_m] = floatval($_tmp_ausz_a);
		}
	}
}
//----------------------------------------------------------------------------------------------
//Summen berechnen
for ($year = $_to; $year <= $_now; $year++) {
	for ($month = 0; $month < 12; $month++) {
		//Summen
		@$anzeige[$year]['Summ']['Saldo'] += $anzeige[$year]['Saldo'][$month];
		@$anzeige[$year]['Summ']['Ferien'] += $anzeige[$year]['Ferien'][$month];
		@$anzeige[$year]['Summ']['Work'] += $anzeige[$year]['Work'][$month];
		@$anzeige[$year]['Summ']['Soll'] += $anzeige[$year]['Soll'][$month];
		//Auszahlung
		@$anzeige[$year]['Summ']['Auszahlung'] += $anzeige[$year]['Auszahlung'][$month];
	}
	$startjahr = intval($_jahr->_startjahr);
	$_activeMonths = time_user::get_year_active_months($year, $_starttime, $_endtime);
	$_vorholzeit = 0;
	$_ferien = 0;
	if ($_activeMonths > 0) {
		$_vorholzeit = round(floatval($_jahr->_Vorholzeit_pro_Jahr) / 12 * floatval($_activeMonths), 2);
		$_ferien = round(floatval($_jahr->_Ferien_pro_Jahr) / 12 * floatval($_activeMonths), 2);
	}
	$anzeige[$year]['Summ']['vorholzeit'] = $_vorholzeit;
	$anzeige[$year]['Summ']['feriengutschrift'] = $_ferien;
	if ($year == $startjahr) {
		$anzeige[$year]['Summ']['vorholzeit_start'] = $_vorholzeit;
		$anzeige[$year]['Summ']['ferien_start'] = $_ferien;
	}

	$_saldo = $anzeige[$year]['Summ']['Saldo'] - $_vorholzeit - $anzeige[$year]['Summ']['Auszahlung'];
	if ($year == $startjahr) {
		$_saldo += floatval($_user->_Stunden_uebertrag);
	} elseif ($_kumulativ) {
		$_saldo += floatval($anzeige[($year - 1)]['Summ']['Saldo']);
	}
	$anzeige[$year]['Summ']['Saldo'] = $_saldo;

	if ($year == $startjahr) {
		$anzeige[$year]['Summ']['feriengutschrift'] = $_ferien + floatval($_user->_Ferienguthaben_uebertrag);
		$_ferienSaldo = $anzeige[$year]['Summ']['feriengutschrift'] - $anzeige[$year]['Summ']['Ferien'];
	} else {
		$_ferienSaldo = floatval($anzeige[($year - 1)]['Summ']['Ferien']) + $_ferien - $anzeige[$year]['Summ']['Ferien'];
	}
	$anzeige[$year]['Summ']['Ferien'] = $_ferienSaldo;
}
//----------------------------------------------------------------------------------------------


echo "<div id='show_year'>";
for ($year = $_now; $year >= $_to; $year--) {
	echo "<div id='year'>";
	echo "
	<table width=375 border=0 cellpadding=3 cellspacing=1>
	<tr>
	<td class=td_background_top align=left><b>Jahr: $year</b></td>
	<td class=td_background_top align=center>Saldo</td>
	<td class=td_background_top align=center>Ferien</td>
	<td class=td_background_top align=center>Ausz.</td>
	</tr>";
	for ($month = 0; $month < 12; $month++) {
		if ($anzeige[$year]['Ferien'][$month] <> 0) $anzeige[$year]['Ferien'][$month] = $anzeige[$year]['Ferien'][$month] . " Tg.";
		if ($anzeige[$year]['Auszahlung'][$month] <> 0) {
			$anzeige[$year]['Auszahlung'][$month] = $anzeige[$year]['Auszahlung'][$month] . " h";
		} else {
			$anzeige[$year]['Auszahlung'][$month] = "";
		}
		echo "
		<tr>
		<td class=td_background_tag align = left>" . $anzeige[$year]['Monat'][$month] . "</td>
		<td class=td_background_tag align=right>" . format($anzeige[$year]['Saldo'][$month]) . "</td>
		<td class=td_background_tag align=right>" . format($anzeige[$year]['Ferien'][$month]) . "</td>
		<td class=td_background_tag align=right>" . format($anzeige[$year]['Auszahlung'][$month]) . "</td>
		</tr>";
	}
	//Jahressummen
	$a = '';
	if ($anzeige[$year]['Summ']['Auszahlung'] <> 0) $a = format($anzeige[$year]['Summ']['Auszahlung']) . " h";
	echo "
	<tr>
	<td class=td_background_wochenende align = left>Jahres - Summe:</td>
	<td class=td_background_wochenende align=right>" . format($anzeige[$year]['Saldo'][12]) . "</td>
	<td class=td_background_wochenende align=right>" . format($anzeige[$year]['Ferien'][12]) . " Tage</td>
	<td class=td_background_wochenende align=right>" . $a . "</td>
	</tr>";
	//Auszahlung
	if ($a) {
		$a = '- ' . $a;
	}
	echo "
	<tr>
	<td class=td_background_tag align = left>Auszahlung:</td>
	<td class=td_background_tag align=right>" . $a . "</td>
	<td class=td_background_tag align=right></td>
	<td class=td_background_tag align=right></td>
	</tr>";
	// Vorholzeiten
	if ($anzeige[$year]['Summ']['vorholzeit']) $v = '- ' . $anzeige[$year]['Summ']['vorholzeit'];
	// nicht das Startjahr?

	if ($year != $_jahr->_startjahr) {
		echo "
		<tr>
		<td class=td_background_tag align = left>Vorholzeit / Ferien</td>
		<td class=td_background_tag align=right>" . @$v . "</td>
		<td class=td_background_tag align=right>" . $anzeige[$year]['Summ']['feriengutschrift'] . " Tage</td>
		<td class=td_background_tag align=right></td>
		</tr>";
		// beides nötig wegen verschiedener Server - BS
		if (strstr(trim($_user->_modell), '0')  or trim($_user->_modell) == '0') {
			$v = $anzeige[$year - 1]['Summ']['Saldo'];
		} else {
			// Vorjahr beim jährlich Berechnugnsmodell = 0
			$v = '';
		}
		$s = $anzeige[$year]['Saldo'][12] - $anzeige[$year]['Summ']['vorholzeit'] - $anzeige[$year]['Summ']['Auszahlung'];
		$f = $anzeige[$year]['Summ']['feriengutschrift'] - $anzeige[$year]['Ferien'][12];
		echo "
		<tr>
		<td class=td_background_wochenende align = left>Zwischen - Summe:</td>
		<td class=td_background_wochenende align=right>" . format($s) . "</td>
		<td class=td_background_wochenende align=right>" . format($f) . " Tage</td>
		<td class=td_background_wochenende align=right></td>
		</tr>";
		if ($anzeige[$year - 1]['Summ']['Ferien'] <> 0) $txt = " Tage";
		//Vorjahr
		echo "
		<tr>
		<td class=td_background_tag align = left>&Uuml;bertrag Vorjahr:</td>
		<td class=td_background_tag align=right>" . format($v) . "</td>
		<td class=td_background_tag align=right>" . format($anzeige[$year - 1]['Summ']['Ferien']) . $txt . "</td>
		<td class=td_background_tag align=right></td>
		</tr>";
	}
	// beim Startjahr
	else {
		$s = $anzeige[$year]['Saldo'][12] - $anzeige[$year]['Summ']['vorholzeit'] - $anzeige[$year]['Summ']['Auszahlung'];
		$f = $anzeige[$year]['Summ']['ferien_start'] - $anzeige[$year]['Ferien'][12];
		echo "
		<tr>
		<td class=td_background_tag align = left>Vorholzeit / Ferien</td>
		<td class=td_background_tag align=right>" . $anzeige[$year]['Summ']['vorholzeit_start'] . "</td>
		<td class=td_background_tag align=right>" . $anzeige[$year]['Summ']['ferien_start'] . " Tage</td>
		<td class=td_background_tag align=right></td>
		</tr>";
		echo "
		<tr>
		<td class=td_background_wochenende align = left>Zwischen - Summe:</td>
		<td class=td_background_wochenende align=right>" . format($s) . "</td>
		<td class=td_background_wochenende align=right>" . format($f) . " Tage</td>
		<td class=td_background_wochenende align=right></td>
		</tr>";
		echo "
		<tr>
		<td class=td_background_tag align = left>&Uuml;bertrag Settings:</td>
		<td class=td_background_tag align=right>" . $_user->_Stunden_uebertrag . "</td>
		<td class=td_background_tag align=right>" . $_user->_Ferienguthaben_uebertrag . " Tage</td>
		<td class=td_background_tag align=right></td>
		</tr>";
	}
	// Total - Summen
	//$anzeige[$year]['Summ']['Saldo'] = $anzeige[$year]['Summ']['Saldo'] + $v;
	echo "
	<tr>
	<td class=td_background_top align = left>Saldo ende Jahr:</td>
	<td class=td_background_top align=right>" . format($anzeige[$year]['Summ']['Saldo']) . "</td>
	<td class=td_background_top align=right>" . format(round($anzeige[$year]['Summ']['Ferien'], 2)) . " Tage</td>
	<td class=td_background_top align=right></td>
	</tr>";

	echo "</table>";
	echo "</div>";
}
echo "</div>";

function format($wert)
{
	if ($wert < 0) {
		return "<font class=minus>" . round(floatval($wert), 2) . "</font>";
	}
	return $wert;
}