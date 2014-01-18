<?php
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
?>