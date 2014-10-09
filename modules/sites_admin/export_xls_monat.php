<?php
/********************************************************************************
* Small Time - XLS - Monatsübersicht
/*******************************************************************************
* Version 0.896
* Author: IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
$stempelzeiten = 6;
$xls ='';
$xls .='
<table>
	<tr>
		<td bgcolor="b9b9b9">User : </td>
		<td align="right" colspan="'.($stempelzeiten+4).'" bgcolor="b9b9b9">'. $_user->_name .'</td>
		<td></td>
	</tr>	
	<tr>
		<td bgcolor="b9b9b9">Monat : </td>
		<td align="right" colspan="'.($stempelzeiten+4).'" bgcolor="b9b9b9">'. $_time->_monatname. " " .  $_time->_jahr  .'</td>
		<td></td>
	</tr>		
	<tr>
		<td colspan="'.($stempelzeiten+6).'"></td>
	</tr>		
	<tr>
		<td bgcolor="d1d1d1">Datum</td>
		<td bgcolor="d1d1d1">Tag</td>
		<td bgcolor="d1d1d1" colspan="'.($stempelzeiten).'">Stempel - Zeiten</td>
		<td bgcolor="d1d1d1" >Gearbeitet</td>
		<td bgcolor="d1d1d1">Saldo</td>
		<td bgcolor="d1d1d1">Absenz</td>
		<td bgcolor="d1d1d1">Rapport</td>
	</tr>';
	
	for($z=1; $z< count($_monat->_MonatsArray); $z++){
		//Farbe festlegen - bei Wochenende oder Feiertag grau
		if($_monat->_MonatsArray[$z][30]=="class=td_background_feiertag"){
			$farbe = "e2e2e2";
		}elseif($_monat->_MonatsArray[$z][30]=="class=td_background_wochenende"){
			$farbe = "e2e2e2";
		}else{
			$farbe = "";
		}
		
		//-------------------------------------------------------------------------
		$xls .= " 
	<tr>\n";
		//-------------------------------------------------------------------------
		// Datum  anzeigen
		//-------------------------------------------------------------------------
		$xls .= "		<td bgcolor=".$farbe.">". $_monat->_MonatsArray[$z][1]."</td>\n";
		//-------------------------------------------------------------------------
		// Tag anzeigen
		//-------------------------------------------------------------------------
		$xls .= "		<td bgcolor=".$farbe.">". $_monat->_MonatsArray[$z][3]."</td>\n";
		//-------------------------------------------------------------------------
		// Stempelzeiten anzeigen / max. 6 Stempelzeiten
		//-------------------------------------------------------------------------
		for ($x=0;$x<$stempelzeiten;$x++){
			$xls .= "		<td bgcolor=".$farbe.">". $_monat->_MonatsArray[$z][12][$x] ."</td>\n";
		}
		//-------------------------------------------------------------------------
		// gearbeitete Stunden  anzeigen
		//-------------------------------------------------------------------------
		$xls .= "		<td bgcolor=".$farbe.">";
		if($_monat->_MonatsArray[$z][13]>0){
			$xls .= number_format($_monat->_MonatsArray[$z][13], 2, '.', '');
		}
		$xls .= "</td>\n";
		//-------------------------------------------------------------------------
		// Saldo anzeigen
		//-------------------------------------------------------------------------
		$xls .= " 		<td bgcolor=".$farbe.">";
		if(number_format($_monat->_MonatsArray[$z][20], 2, '.', '')<>0){
			$xls .= number_format($_monat->_MonatsArray[$z][20], 2, '.', '');
		}
		$xls .= "</td>\n";
		//-------------------------------------------------------------------------
		// Absenzen anzeigen
		//-------------------------------------------------------------------------
		$xls .= " 		<td bgcolor=".$farbe.">";
		$xls .= $_monat->_MonatsArray[$z][15] . " " . $_monat->_MonatsArray[$z][14];
		$xls .= "</td>\n";
		//-------------------------------------------------------------------------
		// Rapport - Text anzeigen
		//-------------------------------------------------------------------------
		$xls .= " 		<td>";
		$xls .= $_monat->_MonatsArray[$z][6]." ".$_monat->_MonatsArray[$z][34] . " " . $_monat->_MonatsArray[$z][32];;
		$xls .= "</td>\n";
		$xls .= " 
		</tr>\n";	
	}

	//-------------------------------------------------------------------------
	// Fusszeile mit den Summen
	//-------------------------------------------------------------------------
	$_sum = "";
	if($_monat->_SummeWorkProMonat>0) { 
		$_sum = $_monat->_SummeWorkProMonat; 
	}
	$xls .= '
	<tr>
		<td bgcolor="d1d1d1" COLSPAN=2></td>
		<td bgcolor="d1d1d1" COLSPAN='.$stempelzeiten.'>Sollstunden :'.  $_monat->_SummeSollProMonat .' Std.</td>
		<td bgcolor="d1d1d1">'. $_sum .'</td>
		<td bgcolor="d1d1d1">	'.number_format($_monat->_SummeSaldoProMonat, 2, '.', '') .'</td>
		<td bgcolor="d1d1d1">	'.$_monat->_SummeAbsenzProMonat.'</td>
		<td>	</td>
	</tr>
</table>'; 

echo $xls;