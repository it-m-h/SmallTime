<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
//-------------------------------------------------------------------------------------------
//Gruppen anzeigen lassen
//-------------------------------------------------------------------------------------------
$i=0;
echo '<form action="?action=group" method="post">';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1">';
echo '<tr>';
echo '<td class="td_background_top" widht="20">';
echo 'Action:';
echo '</td>';
echo '<td class="td_background_top" align="center">';
echo 'Guppe';
echo '</td>';
echo '<td class="td_background_top">';
echo 'Abteilung';
echo '</td>';
echo '</td>';
echo '<td class="td_background_top">';
echo 'User-ID (durch Komma getrennt) ';
echo '</td>';
echo '</tr>';

foreach($_group->get_groups() as $_zeile){		
	$_eintrag = explode(";", $_zeile);			
	echo '<tr>';
	echo '<td class="td_background_tag" widht="20" align=left>';
	if($i>0){
		echo '<a title="delete" href="?action=group&del='.$i.'"><img src="images/icons/delete.png" border="0"> delete</a>';
	}else{
		echo " ";
	}
	echo '</td>';
	echo '<td class="td_background_tag" align="center">';
	$_grpnr = $i+1;
	echo $_grpnr;
	echo '<input type="hidden" size="2" name="e'.$i.'" value="'.$_grpnr.'">';
	echo '</td>';
	echo '<td class="td_background_tag" align=left>';
	echo '<input type="text" size="30" name="v'.$i.'" value="'.$_eintrag[1].'">';
	echo '</td>';
	echo '<td class="td_background_tag" align=left>';
	echo '<input type="text" size="30" name="u'.$i.'" value="'.$_eintrag[2].'">';
	echo '</td>';
	echo '</tr>';
	$i++;	
}
$i++;	

echo '<tr>';
echo '<td class="td_background_tag" widht=20 align=left>';
echo '<img src="images/icons/add.png" border=0>';
echo '</td>';
echo '<td class="td_background_tag" align=center>';
echo '<input type="hidden" size="2" name="e'.$i.'" value="'.$i.'">';
echo $i;
echo '</td>';
echo '<td class="td_background_tag" align=left>';
echo '<input type="text"  size="30" name="v'.$i.'" value=""> <img src="images/icons/information.png" title="Abteilungsname" border=0>';
echo '</td>';
echo '</td>';
echo '<td class="td_background_tag" align=left>';
echo '<input type="text" size="30" name="u'.$i.'" value=""> <img src="images/icons/information.png" title="User in der Gruppe" border=0>';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td class="td_background_top" align=center colspan=4>';
echo '<input type="hidden" name="anzahl" value="'.$i.'" titel="anzahl">';
echo '<input type="submit" name="senden" value="speichern">';
echo '</td>';
echo '</tr>';
echo "</table>";
echo '</form>';