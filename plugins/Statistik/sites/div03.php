<?php
/*******************************************************************************
* Small Time - Plugin : Statistik der Mitarbeiter (Überzeit, Ferien usw.)
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
$_benutzer = file("./Data/users.txt");
unset($_benutzer[0]);
$i=1;
echo '<table cellpadding="2" cellspacing="1" border="0" width="100%">';
echo '
	<tr>
		<td width="20" height="20" class="td_background_top">ID</td>
		<td valign="middle" align="left" class="td_background_top">Name</td>
		<td valign="middle" align="left" class="td_background_top">Tot.</td>
	</tr>';
foreach($_benutzer as $string){	
	$string = explode(";", $string);	
	if(file_exists("./Data/".$string[0]."/Timetable/total.txt")){
		$totale = file("./Data/".$string[0]."/Timetable/total.txt");
		$time = round($totale[0],2);
		if($time <0){
			$time = "<font class=minus>".$time."</font>";
		}
	}else{
		$time = "xxx";
	}		
	$_userdaten_tmp = file("./Data/".$string[0]."/userdaten.txt");
	echo '
	<tr>
		<td width="20" height="20" class="td_background_info">'.$i.'</td>
		<td valign="middle" align="left" class="td_background_tag">'.$_userdaten_tmp[0].'</td>
		<td valign="middle" align="left" class="td_background_tag">'.$time.'</td>
	</tr>'; 
	$i++;
}
echo '</table>';