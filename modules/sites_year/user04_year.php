<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.872
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
/*
echo "<tr>";
echo "<td class=td_background_tag width=100>Vorholzeit</td>";
echo "<td class=td_background_tag >$user->_Vorholzeit_pro_Jahr h</td>";
echo "</tr>";
*/
echo "<tr>";
echo "<td class='alert";
echo $_jahr->_saldo_t >= 0 ? " alert-success" : " alert-error";
echo "'  width=100 align=left>Zeitsaldo</td>";
echo "<td class=td_background_tag align=left>$_jahr->_saldo_t Std.</td>";
echo "</tr>";

echo "<tr>";
echo "<td class='alert";
echo $_jahr->_saldo_F >=0 ? " alert-success" : " alert-error";
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

//$zeig = new time_show($_jahr->_data);
$monate = explode(";",$_settings->_array[11][1]);
$y = $_jahr->_startjahr;

//echo count($_jahr->_data);
//echo " / ".  date("Y", time());
$_now = date("Y", time());
$_to = $_jahr->_startjahr;
echo "<div id='show_year'>";
//$_start = 0;
for($x=$_now; $x>=$_to; $x--){
	//$x=2013;
	$_year =  $_jahr->_data[$x];
	//Auszahlungen berechnen
	$_sum_ausz =0;
	//echo "<hr>";
	//echo "Jahr: " .$x . "<hr>";
	//print_r($auszahlung->_arr_ausz);
	for($u=0; $u< count($auszahlung->_arr_ausz);$u++){
		$_tmp_ausz = trim($auszahlung->_arr_ausz[$u][1]);
		$_wahl = (string)$x;
		//echo "<br>vergleich -". $_tmp_ausz. "-=-" .$_wahl ."-";
		if(strstr($_tmp_ausz,$_wahl)){
			//echo "---------gleich";
			$_sum_ausz += $auszahlung->_arr_ausz[$u][2];	
		}else{
			//echo "----------------------------------nönönönön&ouml;n";
		}
	}
	//echo "<br>".$_sum_ausz;

	//echo "<hr>";
	
//echo($x);
echo "<div id='year'>";


//print_r($_jahr->_data);
//print_r($_jahr->_data[2013]);
//echo "<hr>";

/*	
}

//sort($_jahr->_data);

foreach($_jahr->_data as $_year){ */

	
	
	echo "
	<table width=325 border=0 cellpadding=3 cellspacing=1>
	<tr>
		<td class=td_background_top align=left><b>Jahr: $x</b></td>
		<td class=td_background_top align=center>Saldo</td>
		<td class=td_background_top align=center>Ferien</td>
	</tr>";
	echo "";
	$m=0;
	$tot1 = 0;
	$tot2 = 0;
	

	
	foreach($_year as $_month){
		//print_r($_month);
		//echo "Monat : " . $monate[$m] . " / ";
		//echo "Saldo: " . $_month[0] ." / ";
		//echo "Ferien: " . $_month[1] ." Tage ";
		if($_month[1]<> 0) {
			$_text = $_month[1]." Tage";
		}else{
			$_text = " ";
		}
		$_tempstamp = mktime(0, 0, 0, $m+1, 1, $x);
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

		$tmpfont1 = "";
		$tmpfont2 = "";
		if($_month[0]<0) {	
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
		if ($m >12){
			echo "Fehler in den Daten, Admin kontaktieren!";
			//$_jahr->
		}
	}
		$tmpfont1 = "";
		$tmpfont2 = "";
		if($tot1<0) {	
			$tmpfont1 = "<font class=minus>";
			$tmpfont2 = "</font>";
		}
		echo "
		<tr>
			<td class=td_background_wochenende align = left>Summe:</td>
			<td class=td_background_wochenende align=right>".$tmpfont1.$tot1.$tmpfont2."</td>
			<td class=td_background_wochenende align=right>".$tot2." Tage</td>
		</tr>";
		
		if($x == $_jahr->_startjahr){	
		$_jahr->_Ferien_pro_Jahr = $_jahr->_Ferien_pro_Jahr/12;
		$_jahr->_Ferien_pro_Jahr = $_jahr->_Ferien_pro_Jahr*(13-$_jahr->_startmonat);
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
	$tot2 = round($_jahr->_Ferien_pro_Jahr - $tot2,2);
	
	$tmpfont1 = "";
	$tmpfont2 = "";
	if($tot1<0) {	
		$tmpfont1 = "<font class=minus>";
		$tmpfont2 = "</font>";
	}		
	echo "
		<tr>
			<td class=td_background_wochenende align = left>Zwischen - Summe:</td>
			<td class=td_background_wochenende align=right>".$tmpfont1.$tot1.$tmpfont2."</td>
			<td class=td_background_wochenende align=right>".$tot2." Tage</td>
		</tr>";	
				

	if($x == $_jahr->_startjahr){
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
	if($tot1<0) {	
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
	//echo "<hr>";
	$_tmpabsenz = new time_absenz($_user->_ordnerpfad, $x);
	
	//print_r($_absenztemp->_array[0]);

	//$zeig = new time_show($_absenzen );
	$tmparr = $_tmpabsenz->_array;
	$F=0;
	$K=0;
	$U=0;
	$M=0;
	$I=0;
	$W=0;
	$E=0;
	if($tmparr){
	foreach($tmparr as $_werte){
		switch($_werte[1]){
			case "F":
				$F=$F+$_werte[2];
			break;
			case "K":
				$K=$K+$_werte[2];
			break;
			case "U":
				$U=$U+$_werte[2];
			break;
			case "M":
				$M=$M+$_werte[2];
			break;
			case "I":
				$I=$I+$_werte[2];
			break;
			case "W":
				$W=$W+$_werte[2];
			break;
			case "S":
				$S=$S+$_werte[2];
			break;
		}
	}
	}
	/*
	echo "<table width=300 border=0>";
	//if ($F) echo "Ferien = $F Tage<br>";
	if ($K) echo "<tr><td class=td_background_info align=left>Krankheit = $K Buchungen</td></tr>";
	if ($U) echo "<tr><td class=td_background_info align=left>Unfall = $U Buchungen</td></tr>";
	if ($M) echo "<tr><td class=td_background_info align=left>Militär = $M Buchungen</td></tr>";
	if ($I) echo "<tr><td class=td_background_info align=left>Intern = $I Buchungen</td></tr>";
	if ($W) echo "<tr><td class=td_background_info align=left>Weiterbildung = $W Buchungen</td></tr>";
	if ($S) echo "<tr><td class=td_background_info align=left>Sonstiges = $S Buchungen</td></tr>";
	echo "</table>";
	*/
	echo "</div>";
	$y++;
}
echo "</div>";
?>