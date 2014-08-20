<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.877
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
$_ShowUsername 			= TRUE;		// Anzeige des Usernamens
$_ShowUserOnline 			= TRUE;		// Anzeige ob Anwesend oder Abwesend (grün oder rot)
$_ShowUserLastTime 		= FALSE;	// letzte Stempelzeit von heute anzeigen
$_ShowUserAllTime 		= FALSE;	// alle Stempelzeiten von heute anzeigen
$_ShowUserPic 			= FALSE;	// Bid des users anzeigen
// Falls Eingeloogt, dann User - Einstellungen laden 
if(isset($_SESSION['admin'])){ setUser(); }
// admin-Pannel? (admin.php), dann Admin - Einstellungen laden
if($_logcheck->_admins){ setAdmin(); }
function setUser(){
	global $_ShowUsername;
	global $_ShowUserOnline;
	global $_ShowUserLastTime;
	global $_ShowUserAllTime;
	global $_ShowUserPic;	
	$_ShowUsername 		= TRUE;
	$_ShowUserOnline 		= TRUE;
	$_ShowUserLastTime 	= TRUE;
	$_ShowUserAllTime 	= FALSE;
	$_ShowUserPic 		= FALSE;
}
function setAdmin(){
	global $_ShowUsername;
	global $_ShowUserOnline;
	global $_ShowUserLastTime;
	global $_ShowUserAllTime;
	global $_ShowUserPic;	
	$_ShowUsername 		= TRUE;
	$_ShowUserOnline 		= TRUE;
	$_ShowUserLastTime 	= TRUE;
	$_ShowUserAllTime 	= TRUE;
	$_ShowUserPic 		= TRUE;
}

//Settings - Einstellungen : Anwesenheitsliste anzeigen
if($_settings->_array[13][1] OR $_SESSION['admin']){
	//template unsterstützt Bootstrap
	if(strstr($_template->_bootstrap,'true')){
		//-------------------------------------------------------------------------------------------------------------
		// Anzeige der Anwesenheitsliste
		//-------------------------------------------------------------------------------------------------------------
		if(!$_grpwahl) $_grpwahl = 1;
		if($_grpwahl == -1)$_grpwahl = 1;
		$_group = new time_group($_grpwahl);
		if($id) $_grpwahl = $_group->get_usergroup($id);
		$anzMA = count($_group->_array[1][$_grpwahl]);
		echo '<table border="0" cellspacing="1" cellpadding="3" width=100%>';
		for($x=0; $x<$anzMA ;$x++){
			//Anwesend oder nicht
			$count_time = count($_group->_array[5][$_grpwahl][$x]);
			$anwesend = $count_time%2;					
			if($_ShowUserOnline or $_ShowUserAllTime){
				$alertclass = $anwesend ? "alert alert-success" : "alert alert-error";
			}else{
				$alertclass = $anwesend ? "alert alert-error" : "alert alert-error";
			}
			echo '<tr>';
			//Miratbeiter - Bild anzeigen
			if($_ShowUserPic){
				if(file_exists("./Data/".$_group->_array[2][$_grpwahl][$x]."/img/bild.jpg")){
					echo '<td class="'.$alertclass.'" width="50">';
					echo '<img src="./Data/' . $_group->_array[2][$_grpwahl][$x] . '/img/bild.jpg" alt="" width="50"/>';
					echo '</td>';
				}else{
					echo '<td class="'.$alertclass.'" width="50">';
					echo '<img src="./images/ico/user-icon.png" alt="" width="50"/>';
					echo '</td>';
				}
			}	
			//Mitarbeitername
			if($_ShowUsername){
				echo '<td class="'.$alertclass.'">';
				echo $_group->_array[4][$_grpwahl][$x];
				echo '</td>';
			}			
			if($_ShowUserLastTime && !$_ShowUserAllTime){
				echo '<td class="'.$alertclass.'">';
				if($anwesend){
					echo "Anwesend seit : " . $_group->_array[5][$_grpwahl][$x][count($_group->_array[5][$_grpwahl][$x])-1];
				}else{
					echo "Abwesend";
				}
				echo '</td>';
			}elseif($_ShowUserAllTime){
				echo '<td class="'.$alertclass.'">';
				if($anwesend){
					echo "Anwesend : ";
					
					$str = implode(" - ", $_group->_array[5][$_grpwahl][$x]);
					echo $str;
					
				}else{
					echo "Abwesend";
				}
				echo '</td>';
			}				
			echo '</tr>';
		}
		echo '</table>';			
	}else{
		if(!isset($_SESSION['admin'])){
			//-------------------------------------------------------------------------------------------------------------
			//Falls keins Session existiert, nur Userliste anzeigen ohne Onlinestatus
			//-------------------------------------------------------------------------------------------------------------
			if(!$_grpwahl) $_grpwahl = 1;
			if($_grpwahl == -1)$_grpwahl = 1;
			$_group = new time_group($_grpwahl);
			if($id) $_grpwahl = $_group->get_usergroup($id);	
			$anzMA = count($_group->_array[1][$_grpwahl]);
			echo '<table border="0" cellspacing="1" cellpadding="3" width=100%>';
			for($x=0; $x<$anzMA ;$x++){
				$count_time = count($_group->_array[5][$_grpwahl][$x]);
				$anwesend = $count_time%2;
				if($anwesend) $pic = "green";else $pic = "red";
				echo '<tr>';
				echo '<td class="td_background_tag" height="32px">';
				echo $_group->_array[4][$_grpwahl][$x];
				echo '</td>';
				echo '</tr>';
			}
			echo '</table>';	
		}else{
			//-------------------------------------------------------------------------------------------------------------
			//Userliste mit Anwesend seit - neu bei Login anzeigen
			//-------------------------------------------------------------------------------------------------------------
			if(!$_grpwahl) $_grpwahl = 1;
			if($_grpwahl == -1)$_grpwahl = 1;
			$_group = new time_group($_grpwahl);
			if($id) $_grpwahl = $_group->get_usergroup($id);	
			$anzMA = count($_group->_array[1][$_grpwahl]);
			echo '<table border="0" cellspacing="1" cellpadding="3" width=100%>';
			for($x=0; $x<$anzMA ;$x++){
				$count_time = count($_group->_array[5][$_grpwahl][$x]);
				$anwesend = $count_time%2;
				if($anwesend) $pic = "green";else $pic = "red";
				echo '<tr>';
				echo '<td class="td_background_tag" width="40">';
				echo '<img width="32" height="32" border="0" alt="" src="images/'.$pic.'.png">';
				echo '</td>';
				echo '<td class="td_background_tag">';
				echo $_group->_array[4][$_grpwahl][$x];
				echo '</td>';
				echo '<td class="td_background_tag">';
				if($anwesend){
					echo "Anwesend seit : " . $_group->_array[5][$_grpwahl][$x][count($_group->_array[5][$_grpwahl][$x])-1];
				}else{
					echo "Abwesend";
				}	
				echo '</td>';
				echo '</tr>';
			}
			echo '</table>';	
		}
	}

}else{
	echo $_infotext04;
}
?>