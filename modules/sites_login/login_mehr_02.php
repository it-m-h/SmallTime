<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
if($_settings->_array[13][1]){
	get_gruppen();
}else{
	echo $_infotext02;
}
function get_gruppen(){
	global $_groups;
	global $_grpwahl;
	global $_action;
	echo "        <table width='100%' hight='100%' border='0' cellpadding='3' cellspacing='1'  ><tr>";
	$y=1;
	$breite = round((100 / count($_groups->_array)),1);
	foreach($_groups->_array as $_group){
		// Administratorengruppe nicht anzeigen, dann ist $y ==1
		if($y>1){
			//$_group = explode(";", $_group);
			if($_grpwahl==-1)$_grpwahl = 1;
			
			if($_grpwahl==$_group[0]-1){
				echo "        <td class='td_background_heute' align ='center' valign='middle' width='".$breite."%'>";
			}else{
				echo "        <td class='td_background_top' align ='center' valign='middle' width='".$breite."%'>";
			}
			echo "                <a href='?action=$_action&group=$y'>";
			echo "                <img src='./images/icons/group_go.png'> ";
			//echo $_group[0];
			echo $_group[1];
			//echo $_grpwahl;
			//echo $_group[2];
			echo "                </a>    ";
			echo "                </td>";
		}
		$y++;
	}
	echo "        </tr></table>";
}