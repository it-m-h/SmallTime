<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.85
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
echo "<table width=100% border=0 cellpadding=5 cellspacing=1>";
//echo "<tr>";
//echo "<td class=td_background_top width=100 align=left>&nbsp;</td>";
//echo "<td colspan=5 class=td_background_top align=left>&nbsp;</td>";
//echo "</tr>";
$_file = "./Data/users.txt";
$_benutzer = file($_file);
unset($_benutzer[0]);
//unset($_benutzer[1]);
$i=1;
foreach($_benutzer as $string){
	$string = explode(";", $string);
	$_userdaten_tmp = file("./Data/".$string[0]."/userdaten.txt");
	echo "<tr>";
	if($_user->_loginname == $string[1]){
		echo "<td class='td_background_info' width=100 align=left>";
		//echo $_user->_loginname.$string[1];
	}else{
		echo "<td class='td_background_top' width=100 align=left>";
		//echo $_user->_loginname.$string[1];
	}
	echo "<a title='Userdaten anzeigen von diesem Monat' href='?action=user_personalkarte&admin_id=".$i."'><img src='images/icons/user.png' border=0> ";
	echo "Nr. ". $i . " / " ;
	//echo $string[1];
	//echo "<br>";
	echo $_userdaten_tmp[0];
	echo "</a>";
	//echo '</td><td class="td_background_top" align="center">';
	//$openseite = "'admin04_personalkarte'";
	//echo '<img border="0" title="Info" src="images/icons/information.png" onclick="opendetails('.$openseite.')"></img>';
	
	//-----------------------------------------------------------------------------
	// menue
	//-----------------------------------------------------------------------------
	//-----------------------------------------------------------------------------
	// Löschen
	//-----------------------------------------------------------------------------
	if($i > 1){
		$tmpstr= "<span class='btn'><a title='L&ouml;schen eines Users' href='?action=delete_user&delete_user_id=".$i."'><img src='images/icons/delete.png' border=0></a></span>";
	}else{
		$tmpstr= "";
	}	
	//echo $_action;
	echo "</td></tr><tr><td>";
	echo "
	<div class='btn-group'>
		<span class='btn";
		if($_action == 'show_time' && $i ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='Monatsansicht' href='?action=show_time&admin_id=".$i."'><img src='images/icons/calendar_view_month.png' border='0'></a>
               </span>
               <span class='btn";
		if($_action == 'show_year2' && $i ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='Jahres&uuml;bersicht Details' href='?action=show_year2&admin_id=".$i."''><img src='images/icons/calendar.png' border='0'></a>
               </span>
               <span class='btn";
		if($_action == 'show_year' && $i ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='Jahres&uuml;bersicht' href='?action=show_year&admin_id=".$i."'><img src='images/icons/table_multiple.png' border=0></a>
               </span>
               <span class='btn";
		if($_action == 'show_pdf' && $i ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='Vorhandene PDF' href='?action=show_pdf&admin_id=".$i."'><img src='images/icons/page_white_acrobat.png' border=0></a>
               </span>
               <span class='btn";
		if($_action == 'zip_user' && $i ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='ZIP-Datei erstellen' href='?action=zip_user&admin_id=".$i."'><img src='images/icons/page_white_zip.png' border=0></a>
               </span>
               <span class='btn";
		if($_action == 'user_einstellungen' && $i ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='Grundeinstellungen' href='?action=user_einstellungen&admin_id=".$i."'><img src='images/icons/folder_user.png' border=0></a>
               </span>
               <span class='btn";
		if($_action == 'user_edit' && $i ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='Userdaten einstellen' href='?action=user_edit&admin_id=".$i."'><img src='images/icons/user_edit.png' border=0></a>
               </span>
               <span class='btn";
		if($_action == 'user_edit_absenzen' && $i ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='Absenzen einstellen' href='?action=user_edit_absenzen&admin_id=".$i."'><img src='images/icons/date_edit.png' border=0></a>
               </span>            
               	".$tmpstr."
	</div>
	</td></tr>";
	$i++;
}
echo "</table>";

$show_user=true;
if($show_user){

	echo "<hr>";
	//-----------------------------------------------------------------------------
	// Anzeige der Summen aus Statistik
	//-----------------------------------------------------------------------------

	echo "<table class='table' border=0 cellpadding=3 cellspacing=1>";

	echo "<tr>";
	echo "<td class='td_background_top' align=left colspan=2>Mitarbeiterdaten</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td class=td_background_tag align=left>Name</td>";
	echo "<td class=td_background_tag align=left>$_user->_name</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td class=td_background_tag align=left>Start - Datum</td>";
	echo "<td class=td_background_tag align=left>".date("d.m.Y",$_user->_BeginnDerZeitrechnung)."</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td class=td_background_tag align=left>Anstellung</td>";
	echo "<td class=td_background_tag align=left>$_user->_SollZeitProzent %</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td class=td_background_tag align=left>Sollstd. / Wo</td>";
	echo "<td class=td_background_tag align=left>$_user->_SollZeitProWoche h</td>";
	echo "</tr>";


	echo "<tr>";
	echo "<td class='td_background_top' align=left colspan=2>Aktuelle Total - Saldi</td>";
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
}


$_show_userdata=false;
if($_show_userdata){
	//-----------------------------------------------------------------------------
	// Anzeige von Userdaten
	//-----------------------------------------------------------------------------
	echo "<hr>";
	echo "<table width=100% border=0 cellpadding=3 cellspacing=1>";

	$_temp = explode(";", $_users[$_userid]);
	$_SESSION['admin']= $_temp[0];

	echo "<tr>";
	echo "<td class=td_background_info width=100 align=left>$_userid</td>";
	echo "<td class=td_background_wochenende align=left>".$_SESSION['admin']." &nbsp;</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td class=td_background_tag width=100 align=left>Name</td>";
	echo "<td class=td_background_tag align=left>$_userdaten[0]</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td class=td_background_tag width=100 align=left>Start - Datum</td>";
	echo "<td class=td_background_tag align=left>".date("d.m.Y",$_userdaten[1])."</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td class=td_background_tag width=100 align=left>Anstellung</td>";
	echo "<td class=td_background_tag align=left>".$_userdaten[2]." %</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td class=td_background_tag width=100 align=left>Sollstd. / Wo</td>";
	echo "<td class=td_background_tag align=left>".($_userdaten[3]*$_userdaten[2]/100)." h</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class=td_background_tag width=100 align=left>Monat&nbsp;</td>";
	echo "<td class=td_background_tag align=left>".$_monate[date("n", $_timestamp)] ." ".date("Y", $_timestamp)."</td>";
	echo "</tr>";

	if(!$_SummeSollProMonat==0){
		echo "<tr>";
		echo "<td class=td_background_tag width=100 align=left>Soll / Monat</td>";
		echo "<td class=td_background_tag align=left>$_SummeSollProMonat Std.</td>";
		echo "</tr>";
	}
	if(!$_SummeSaldoProMonat==0){
		echo "<tr>";
		echo "<td class=td_background_tag width=100 align=left>Saldo / Monat</td>";
		echo "<td class=td_background_tag align=left>$_SummeSaldoProMonat Std.</td>";
		echo "</tr>";
	}
	if(!$_Feriensaldo==0){
		echo "<tr>";
		echo "<td class=td_background_tag width=100 align=left>Feriensaldo</td>";
		echo "<td class=td_background_tag align=left>$_Feriensaldo Tage</td>";
		echo "</tr>";
	}
	$i=0;
	foreach($_abwesenheit as $_tmp){
		if(!$_SummeAbsenzenProMonat[$i][2] == 0){
			echo "<tr>";
			echo "<td class=td_background_tag width=100 align=left>$_tmp[0]</td>";
			echo "<td class=td_background_tag align=left>";
			echo $_SummeAbsenzenProMonat[$i][2];
			echo " Tage   ($_tmp[1])</td>";
			echo "</tr>";
		}
		$i++;
	}
	echo "</table>";
}
//echo $_copyright;
?>