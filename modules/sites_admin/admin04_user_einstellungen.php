<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
?>
<form action='?action=user_einstellungen_update&admin_id=<?php echo $_id; ?>' method='post' target='_self'>
	<table width=100% border=0 cellpadding=3 cellspacing=1>
		<tr>
			<td align=left COLSPAN=3 class=td_background_top width=60>Userdaten Editieren, neuer User erstellen</td>
		</tr>
		<!-- Nach dem erstellen eines Users sollte der Ordnerpfad nicht mehr verändert werden
		<tr>
			<td align=left class=td_background_tag width=300>Pfad (Ordnername ./Data/XXXXX)</td>
			<td align=left class=td_background_tag><input type='text' name='_a' value='<?php echo $_users->_array[$_id][0] ?>' size='70'></td>
			<td align=left class=td_background_tag width=16><img title='Pfad zu den Daten f&uuml;r diesen Benutzer. Ohne Sonderzeichen, leerschl&auml;ge oder Umlaute!' src='images/icons/information.png' border=0></td>
		</tr> !-->
		<input type='hidden' name='_a' value='<?php echo $_users->_array[$_id][0] ?>' size='70'>
		<tr>
			<td align=left class=td_background_tag>Loginname (empfehlung: Kurzzeichen)</td>
			<td align=left class=td_background_tag><input type='text' name='_b' value='<?php echo $_users->_array[$_id][1] ?>' size='70'></td>
			<td align=left class=td_background_tag width=16><img title='Loginnname, ohne Sonderzeichen, leerschl&auml;ge oder Umlaute!' src='images/icons/information.png' border=0></td>
		</tr>
		<tr>
			<td align=left class=td_background_tag>neues Passwort (nur neu vergeben m&ouml;glich!)</td>
			<td align=left class=td_background_tag><input type='text' name='_c' value='1234' size='70'></td>
			<td align=left class=td_background_tag width=16><img title='Neues Passwort.' src='images/icons/information.png' border=0></td>
		</tr>
		<tr>
			<td align=left class=td_background_tag>RFID - Chipnummer  (idtime.php?rfid=XXXXX)</td>
			<td align=left class=td_background_tag><input type='text' name='_d' value='<?php echo $_users->_array[$_id][3] ?>' size='70'></td>
			<td align=left class=td_background_tag width=16><img title='RFID - Nummer.' src='images/icons/information.png' border=0></td>
		</tr>
		<tr>
			<td COLSPAN=3 class=td_background_top width=200>
				<input type='submit' name='absenden' value='OK' > 
				<input type='submit'  name='absenden' value='CANCEL' > 
			</td>
		</tr>
	</table>
</form>
