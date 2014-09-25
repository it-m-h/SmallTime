<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
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
echo "<td class=td_background_tag align=left>$_jahr->_saldo_F Tage</td>";
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

$monate = explode(";",$_settings->_array[11][1]);
$y      = $_jahr->_startjahr;
$_now   = date("Y", time());
$_to    = $_jahr->_startjahr;
echo "<div id='show_year'>";
for($x = $_now; $x >= $_to; $x--)
{
	$_year     = $_jahr->_data[$x];
	//Auszahlungen berechnen
	$_sum_ausz = 0;
	for($u = 0; $u < count($auszahlung->_arr_ausz);$u++)
	{
		$_tmp_ausz = trim($auszahlung->_arr_ausz[$u][1]);
		$_wahl     = (string)$x;
		if(strstr($_tmp_ausz,$_wahl))
		{
			$_sum_ausz += $auszahlung->_arr_ausz[$u][2];
		}
	}
	echo "<div id='year'>";
	echo "
	<table width=325 border=0 cellpadding=3 cellspacing=1>
	<tr>
	<td class=td_background_top align=left><b>Jahr: $x</b></td>
	<td class=td_background_top align=center>Saldo</td>
	<td class=td_background_top align=center>Ferien</td>
	</tr>";
	echo "";
	$m    = 0;
	$tot1 = 0;
	$tot2 = 0;
	foreach($_year as $_month)
	{
		if($_month[1] <> 0)
		{
			$_text = $_month[1]." Tage";
		}
		else
		{
			$_text = " ";
		}
		$_tempstamp = mktime(0, 0, 0, $m + 1, 1, $x);
		$monatslink = "
		<table width='100%' hight='100%' border='0' cellpadding='2' cellspacing='0'>
		<tr>
		<td width='18' valign='middle'>
		<img src='images/icons/calendar_view_month.png' border=0>
		</td><td valign='middle'>
		<a title='Monat ".$monate[$m]."' href='?action=show_time&admin_id=".$_SESSION['id']."&timestamp=".$_tempstamp."'>".$monate[$m]."</a>
		</td>
		</tr>
		</table>";
		$tmpfont1   = "";
		$tmpfont2   = "";
		if($_month[0] < 0)
		{
			$tmpfont1 = "<font class=minus>";
			$tmpfont2 = "</font>";
		}
		echo "
		<tr>
		<td class=td_background_tag align = left>".$monatslink."</td>
		<td class=td_background_tag align=right>".$tmpfont1.$_month[0].$tmpfont2."</td>
		<td class=td_background_tag align=right>".$_text."</td>
		</tr>"	;
		$tot1 = round($tot1 + $_month[0],2);
		$tot2 = $tot2 + $_month[1];
		$m++;
		if($m > 12)
		{
			echo "Fehler in den Daten, Admin kontaktieren!";
		}
	}
	$tmpfont1 = "";
	$tmpfont2 = "";
	if($tot1 < 0)
	{
		$tmpfont1 = "<font class=minus>";
		$tmpfont2 = "</font>";
	}
	echo "
	<tr>
	<td class=td_background_wochenende align = left>Summe:</td>
	<td class=td_background_wochenende align=right>".$tmpfont1.$tot1.$tmpfont2."</td>
	<td class=td_background_wochenende align=right>".$tot2." Tage</td>
	</tr>";

	if($x == $_jahr->_startjahr)
	{
		$_jahr->_Ferien_pro_Jahr = $_jahr->_Ferien_pro_Jahr / 12;
		$_jahr->_Ferien_pro_Jahr = $_jahr->_Ferien_pro_Jahr * (13 - $_jahr->_startmonat);
		$_jahr->_Ferien_pro_Jahr = round($_jahr->_Ferien_pro_Jahr,2);
	}
	echo "
	<tr>
	<td class=td_background_tag align = left>Auszahlung:</td>
	<td class=td_background_tag align=right>- ".$_sum_ausz."</td>
	<td class=td_background_tag align=right></td>
	</tr>"	;
	echo "
	<tr>
	<td class=td_background_tag align = left>Vorholzeit / Ferien</td>
	<td class=td_background_tag align=right>- ".$_jahr->_Vorholzeit_pro_Jahr."</td>
	<td class=td_background_tag align=right>$_jahr->_Ferien_pro_Jahr Tage</td>
	</tr>"	;
	$tot1 -= $_jahr->_Vorholzeit_pro_Jahr;
	$tot1 -= $_sum_ausz;
	$tot2     = round($_jahr->_Ferien_pro_Jahr - $tot2,2);

	$tmpfont1 = "";
	$tmpfont2 = "";
	if($tot1 < 0)
	{
		$tmpfont1 = "<font class=minus>";
		$tmpfont2 = "</font>";
	}
	echo "
	<tr>
	<td class=td_background_wochenende align = left>Zwischen - Summe:</td>
	<td class=td_background_wochenende align=right>".$tmpfont1.$tot1.$tmpfont2."</td>
	<td class=td_background_wochenende align=right>".$tot2." Tage</td>
	</tr>";
	if($x == $_jahr->_startjahr)
	{
		echo "
		<tr>
		<td class=td_background_tag align = left>&Uuml;bertrag:</td>
		<td class=td_background_tag align=right>".$_user->_Stunden_uebertrag."</td>
		<td class=td_background_tag align=right>".$_user->_Ferienguthaben_uebertrag." Tage</td>
		</tr>";
		$tot1 += $_user->_Stunden_uebertrag;
		$tot2 += $_user->_Ferienguthaben_uebertrag;
	}
	$tmpfont1 = "";
	$tmpfont2 = "";
	if($tot1 < 0)
	{
		$tmpfont1 = "<font class=minus>";
		$tmpfont2 = "</font>";
	}
	echo "
	<tr>
	<td class=td_background_top align = left>Saldo:</td>
	<td class=td_background_top align=right>".$tmpfont1.$tot1.$tmpfont2."</td>
	<td class=td_background_top align=right>".$tot2." Tage</td>
	</tr>";

	echo "</table>";
	$_tmpabsenz = new time_absenz($_user->_ordnerpfad, $x);
	$F		= 0;
	$K		= 0;
	$U		= 0;
	$M		= 0;
	$I		= 0;
	$W		= 0;
	$E		= 0;
	if($_tmpabsenz->_array)
	{	//TODO : Total - Summen der Absenzen anzeigen (ab 0.87 erweiterbar pro Mitarbeiter)
		foreach($_tmpabsenz->_array as $_werte)		
		{
			switch($_werte[1])
			{
				case "F":
				$F = $F + $_werte[2];
				break;
				case "K":
				$K = $K + $_werte[2];
				break;
				case "U":
				$U = $U + $_werte[2];
				break;
				case "M":
				$M = $M + $_werte[2];
				break;
				case "I":
				$I = $I + $_werte[2];
				break;
				case "W":
				$W = $W + $_werte[2];
				break;
				case "S":
				$S = $S + $_werte[2];
				break;
			}
		}
	}
	$_tmpabsenz = NULL;
	echo "</div>";
	$y++;
}
echo "</div>";
?>