<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.83
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
?>
<Form action='?action=user_add' method='post' target='_self'>
	<table width=100% border=0 cellpadding=3 cellspacing=1>
		<tr>
			<td align=left class=td_background_tag width=300>Pfad (Ordnername ./Data/XXXXX)</td>
			<td align=left class=td_background_tag ><input type='text' name='_a' value='' size='70'></td>
			<td align=left class=td_background_tag width=16><img title='Pfad zu den Daten für diesen Benutzer. Ohne Sonderzeichen, leerschläge oder Umlaute!' src='images/icons/information.png' border=0></td>
		</tr>
		<tr>
			<td align=left class=td_background_tag>Loginname (empfehlung: Kurzzeichen)</td>
			<td align=left class=td_background_tag><input type='text' name='_b' value='' size='70'></td>
			<td align=left class=td_background_tag width=16><img title='Loginnname, ohne Sonderzeichen, leerschläge oder Umlaute!' src='images/icons/information.png' border=0></td>
		</tr>
		<tr>
			<td align=left class=td_background_tag>neues Passwort (nur neu vergeben m&ouml;glich!)</td>
			<td align=left class=td_background_tag ><input type='text' name='_c' value='1234' size='70'></td>
			<td align=left class=td_background_tag width=16><img title='Neues Passwort.' src='images/icons/information.png' border=0></td>
		</tr>
		<tr>
			<td align=left class=td_background_tag>RFID - Chip Nummer (idtime.php?rfid=XXXXX)</td>
			<td align=left class=td_background_tag ><input type='text' name='_d' value='' size='70'></td>
			<td align=left class=td_background_tag width=16><img title='RFID - Nummer, muss einmalig sein!' src='images/icons/information.png' border=0></td>
		</tr>
		<tr>
			<td class=td_background_top></td>
			<td COLSPAN=2 class=td_background_top width=200>
				<input type='submit' name='absenden' value='OK' > 
				<input type='submit'  name='absenden' value='CANCEL' > 
			</td>
		</tr>
	</table>
</form>
