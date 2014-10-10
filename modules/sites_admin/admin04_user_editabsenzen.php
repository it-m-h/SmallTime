<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
?>
<form action="?action=user_update_absenzen&admin_id=<?php echo $_id ?>" method="post" target="_self">
	<table width="100%" border="0" cellpadding="5" cellspacing="1 	">
		<tr>
			<td colspan=3 class=td_background_wochenende width="100%" align=left>Absenz - Auswahl einstellen (Bezeichnung, Kurzzeichen, &Uuml;bernommen wird in ?% von der Firma)
				<br>ACHTUNG: Es kann zu Fehlern f&uuml;ren wenn schon eine Absenz mit dem Kurzzeichen existiert und es ge&auml;ndert wird!
			</td>
		</tr>
		<tr>
			<td class=td_background_top align=left width="10%">
				Beschreibung
				</td><td class=td_background_top align=center width="10%">
				Kurzzeichen
				</td><td class=td_background_top align=left>
				? % wird von der Firma &uumlbernommen
			</td>
		</tr>
		<?php
		$i=0;
		foreach($_user->get_user_absenzen() as $string){
			$string = explode(";", $string);
			echo "
			<tr>
			<td class=td_background_tag align=left>
			<input type='text' name='ab0_$i' value='".$string[0]."' size='20'>
			</td><td class=td_background_tag align=center>
			<input class='smallinput' type='text' name='ab1_$i' value='".$string[1]."' size='1'>
			</td><td class=td_background_tag align=left>
			<input class='smallinput' type='text' name='ab2_$i' value='".$string[2]."' size='3'>
			</td>
			</tr>
			";
			$i++;
		}?>
		<tr>
			<td colspan=3 class=td_background_top align=center>
				<input type="submit" name="absenden" value="OK" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type='submit'  name='absenden' value='CANCEL' >
				<input type='hidden' name='anzahl' value='<?php echo $i-1; ?>' size='3'>
			</td>
		</tr>
	</table>
</form>