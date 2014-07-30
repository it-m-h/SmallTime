<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.87
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
if($_settings->_array[13][1] OR $_SESSION['admin']){

	if(strstr($_template->_bootstrap,'true')){
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
				$alertclass = $anwesend ? "alert alert-success" : "alert alert-error";
				echo '<tr>';
				echo '<td class="'.$alertclass.'">';
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
				$alertclass = $anwesend ? "alert alert-success" : "alert alert-error";
				echo '<tr>';
				echo '<td class="'.$alertclass.'">';
				echo $_group->_array[4][$_grpwahl][$x];
				echo '</td>';
				echo '<td class="'.$alertclass.'">';
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