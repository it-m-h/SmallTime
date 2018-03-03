<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.020
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
//-----------------------------------------------------------------------------
// Anzeige der Summen aus der Statistik
//-----------------------------------------------------------------------------
$_data[0][0] = "SummeSollProMonat";
$_data[1][0] = "SummeWorkProMonat";
$_data[2][0] = "SummeAbsenzProMona";
$_data[3][0] = "SummeSaldoProMonat";
$_data[4][0] = "Auszahlung";
$_summe_calc_absenz = array();
for($i = 0; $i < 12;$i++)
{
	// ----------------------------------------------------------------------------
	// Anzahl der Tage im Monat
	// ----------------------------------------------------------------------------
	$_temp_time = new time();
	$_temp_time->set_timestamp(mktime(0,0,0,$i + 1,1,$_time->_jahr));
	$time_month = new time_month( $_settings->_array[12][1], $_temp_time->_letzterTag, $_user->_ordnerpfad, $_time->_jahr, $i + 1, $_user->_arbeitstage, $_user->_feiertage, $_user->_SollZeitProTag, $_user->_BeginnDerZeitrechnung, $_settings->_array[21][1],$_settings->_array[22][1], $_settings->_array[27][1], $_settings->_array[28][1]);
	
	$_temp_time = NULL;
	
	@$_data[0][1] += $time_month->_SummeSollProMonat;
	@$_data[1][1] += $time_month->_SummeWorkProMonat;
	@$_data[2][1] += $time_month->_SummeAbsenzProMonat;
	@$_data[3][1] += $time_month->_SummeSaldoProMonat;
	@$_data[4][1] += $_jahr->get_auszahlung(($i + 1), $_time->_jahr);

	//-------------------------------------------------------------------------
	// Summen der Absenzen berechnen (ab 0.87 erweiterbar pro Mitarbeiter)
	//-------------------------------------------------------------------------
	if(!$_summe_calc_absenz)
	{
		$_summe_calc_absenz = $time_month->get_calc_absenz();
	}
	else
	{
		$tp = 0;
		foreach($time_month->get_calc_absenz() as $werte)
		{
			$_summe_calc_absenz[$tp][3] = $_summe_calc_absenz[$tp][3] + $werte[3];
			$tp++;
		}
	}
	$_jahres_berechnung[$i] = $time_month;
}
for($i = 0; $i < 12;$i++){
	@$_SummeSollProMonat += $_jahres_berechnung[$i]->_SummeSollProMonat;
	@$_SummeWorkProMonat += $_jahres_berechnung[$i]->_SummeWorkProMonat;
	@$_SummeAbsenzProMonat += $_jahres_berechnung[$i]->_SummeAbsenzProMonat;
	@$_SummeSaldoProMonat += $_jahres_berechnung[$i]->_SummeSaldoProMonat;
	@$_SummeStempelzeiten += $_jahres_berechnung[$i]->_SummeStempelzeiten;
	//-----------------------------------------------------------------------alt
	@$_SummeFerien += $_jahres_berechnung[$i]->_SummeFerien;
	@$_SummeKrankheit += $_jahres_berechnung[$i]->_SummeKrankheit;
	@$_SummeUnfall += $_jahres_berechnung[$i]->_SummeUnfall;
	@$_SummeMilitaer += $_jahres_berechnung[$i]->_SummeMilitaer;
	@$_SummeIntern += $_jahres_berechnung[$i]->_SummeIntern;
	@$_SummeWeiterbildung += $_jahres_berechnung[$i]->_SummeWeiterbildung;
	@$_SummeExtern += $_jahres_berechnung[$i]->_SummeExtern;
	//--------------------------------------------------------------------alt
}
echo "<table width=100% border=0 cellpadding=3 cellspacing=1 >";
echo "<tr>";
echo "<td class='td_background_top' width=100 align=left colspan=2>Jahres - Saldo:</td>";
echo "</tr>";
echo "<tr>";
echo "<td class='alert";
echo $_SummeSaldoProMonat  >= 0 ?  " alert-success" : " alert-error";
echo "'  width=100 align=left>Zeitsaldo</td>";
echo "<td class=td_background_tag align=left>". round($_SummeSaldoProMonat,2) . " Std.</td>";
echo "</tr>";
echo "<tr>";
echo "<td class='alert";
echo $_SummeFerien >= 0 ? " alert-success" : " alert-error";
echo "' width=100 align=left>Ferientotal</td>";
echo "<td class=td_background_tag align=left>$_SummeFerien Tage</td>";
echo "</tr>";
echo "</table>";
echo "<br>";
// ----------------------------------------------------------------------------
// Viewer für die Jahresansicht
// ----------------------------------------------------------------------------
$monate = explode(";",$_settings->_array[11][1]);
echo "<table width='100%' hight='100%' border='0' cellpadding='3' cellspacing='1'>";
echo "<tr>";
echo "<td class='td_background_top' align='middle'>";
echo "Monat";
echo "</td>";
echo "<td class='td_background_top' align='middle'>";
echo "Soll";
echo "</td>";
echo "<td class='td_background_top' align='middle'>";
echo "Work";
echo "</td>";
echo "<td class='td_background_top' align='middle'>";
echo "Absenz";
echo "</td>";
echo "<td class='td_background_top' align='middle'>";
echo "Saldo";
echo "</td>";
echo "<td class='td_background_top' align='middle'>";
echo "Ausz.";
echo "</td>";
foreach($_absenz->_filetext as $spalten)
{
	explode(";",$spalten);
	echo "<td width='40' align='middle' class='td_background_top'>";
	echo "" .$spalten[0] . "";
	echo "</td>";
}
echo "</tr>";


for($i = 0; $i < 12;$i++)
{
	$_timestamp = mktime(0, 0, 0, $i + 1, 1, $_time->_jahr);
	echo "<tr>";
	echo "<td class=td_background_wochenende>";
	echo "<table width='100%' hight='100%' border='0' cellpadding='2' cellspacing='0'><tr><td width='18' valign='middle'>";
	echo "<img src='images/icons/calendar_view_month.png' border=0>";
	echo "</td><td align='left'>";
	echo "<a title='Monat ".$monate[$i]."' href='?action=show_time&admin_id=".$_SESSION['id']."&timestamp=".$_timestamp."'>".$monate[$i]."</a>&nbsp;";

	echo "</td></tr></table>";
	echo "</td>";

	echo "<td width='60' align='middle' class=td_background_tag>";
	echo $_jahres_berechnung[$i]->_SummeSollProMonat;
	echo "</td>";

	echo "<td width='60' align='middle' class=td_background_tag>";
	echo $_jahres_berechnung[$i]->_SummeWorkProMonat;
	echo "</td>";

	echo "<td width='60' align='middle' class=td_background_tag>";
	echo $_jahres_berechnung[$i]->_SummeAbsenzProMonat;
	echo "</td>";

	echo "<td width='60' align='middle' class=td_background_wochenende>";
	if($_jahres_berechnung[$i]->_SummeSaldoProMonat < 0) echo "<font class=minus>";
	echo $_jahres_berechnung[$i]->_SummeSaldoProMonat;
	if($_jahres_berechnung[$i]->_SummeSaldoProMonat < 0) echo "</font>";
	echo "</td>";

	echo "<td width='60' align='middle' class=td_background_tag><div id='mymodal'>";
	echo "<a title='Auszahlung' href='?action=edit_ausz&admin_id=".$_SESSION['id']."&monat=".($i + 1)."&jahr=".$_time->_jahr."&modal'>";
	echo $_jahr->get_auszahlung(($i + 1), $_time->_jahr);
	echo "</a>";
	echo "</div></td>";

	//-------------------------------------------------------------------------
	// Summen der Absenzen anzeigen (ab 0.87 erweiterbar pro Mitarbeiter)
	//-------------------------------------------------------------------------
	$tmp = $_jahres_berechnung[$i]->get_calc_absenz();
	foreach($tmp as $werte)
	{
		echo "<td width='40' align='middle' class=td_background_tag>";
		echo $werte[3] . "&nbsp;";
		echo "</td>";
	}
	echo "</tr>";
}
// Summen - Anzeige
echo "<tr>";
echo "<td class='td_background_top''  align='middle'>";
echo "Total :";
echo "</td>";
foreach($_data as $_spalten)
{
	echo "<td align='middle' class=td_background_top>";
	if($_spalten[1] < 0) echo "<font class=minus>";
	echo round($_spalten[1],2);
	if($_spalten[1] < 0) echo "</font>";
	echo "</td>";
}
//-------------------------------------------------------------------------
// Total - Summen der Absenzen anzeigen (ab 0.87 erweiterbar pro Mitarbeiter)
//-------------------------------------------------------------------------
foreach($_summe_calc_absenz as $werte)
{
	echo "<td align='middle' class=td_background_top>";
	if($werte[3] < 0) echo "<font class=minus>";
	echo round($werte[3],2);
	if($werte[3] < 0) echo "</font>";
	echo "</td>";
}
echo "</tr>";
echo "</table>";

if(strstr($_template->_modal,'true'))
{
	?>
	<script type="text/javascript">
		$('#mymodal a').click(function(e)
			{
				e.preventDefault();
				$("#modalBody").html("");
				$('#myModalLabel').html($(this).attr('title'));
				$("#modalBody").load(this.href + '');
				$("#mainModal").modal('show');
			});
	</script>
	<?php
} ?>