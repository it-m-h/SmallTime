<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
echo '<form action="?action=feiertage" method="post">';
echo '<table border="0" width="100%" cellpadding=3 cellspacing=1>';
echo '<tr>';
echo '<td class="td_background_top" widht="20">';
echo '';
echo '</td>';
echo '<td class="td_background_top" align="left">';
echo 'Names des Feiertags.';
echo '</td>';
echo '<td class="td_background_top">';
echo 'Datum (Tag.Monat)';
echo '</td>';
echo '</tr>';
$i = 0;
foreach($_feiertage->get_firmenfeiertage() as $_eintrag)
{
	echo '<tr>';
	echo '<td class="td_background_tag"widht=20>';
	echo '<a title="delete" href="?action=feiertage&del='.$i.'"><img src="images/icons/delete.png" border=0> delete</a>';
	echo '</td>';
	echo '<td class="td_background_tag">';
	echo '<input size="60" type="text" name="e'.$i.'" value="'.$_eintrag[0].'">';
	echo '</td>';
	echo '<td class="td_background_tag">';
	echo '<input type="text" name="v'.$i.'" value="'.$_eintrag[1].'">';
	echo '</td>';
	echo '</tr>';
	$i++;
}
echo '<tr>';
echo '<td class="td_background_tag" widht=20>';
echo '<img src="images/icons/add.png" border=0>';
echo '</td>';
echo '<td class="td_background_tag">';
echo '<input size="60" type="text" name="e'.$i.'" value=""> <img src="images/icons/information.png" title="Name des Feiertags" border=0>';
echo '</td>';
echo '<td class="td_background_tag">';
echo '<input type="text" name="v'.$i.'" value=""> <img src="images/icons/information.png" title="Eingabe: Tag.Monat" border=0>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<td class="td_background_top" align=center colspan=3>';
echo '<input type="hidden" name="anzahl" value="'.$i.'" titel="anzahl">';
echo '<input type="submit" name="senden" value="speichern">';
echo '</td>';
echo '</tr>';
echo '</table>';
echo '</form>';