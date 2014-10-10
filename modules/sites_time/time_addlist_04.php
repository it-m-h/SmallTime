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
<form name="insert" action="?action=insert_time_list&timestamp=<?php echo $_time->_timestamp ?>&token=<?php echo $token ?>" method="post" target="_self">
	<table width="100%" border="0" cellpadding="5" cellspacing="2">
		<tr>
			<td class=td_background_top width="200" align=left>Tag:</td>
			<td class=td_background_tag align=left>
				<input type="text" name="_w_tag" value="<?php echo $_time->_tag; ?>" size="4">
			</td>
		</tr>
 		<tr>
			<td class=td_background_top width="200" align=left>Monat:</td>
			<td class=td_background_tag align=left>
				<input type="text" name="_w_monat" value="<?php echo $_time->_monat; ?>" size="4">
			</td>
		</tr>
		<tr>
			<td class=td_background_top width="200" align=left>Jahr</td>
			<td class=td_background_tag align=left>
				<input type="text" name="_w_jahr" value="<?php echo $_time->_jahr; ?>" size="4">
			</td>
		</tr>
		<tr>
			<td class=td_background_heute width="200" align=left>Mehrere Zeitangaben : z.B</td>
			<td class=td_background_heute align=left>7.51-12.05-13-16.20</td>
		</tr>
		<tr >
			<td class=td_background_top align=left>Eingabe:</td>
			<td class=td_background_tag align=left>
				<input type="text" name="_zeitliste" value="" size="74">
			</td>
		</tr>		
		<tr>
			<td class=td_background_heute align=left width="50%" align="center">
				<?php if (!strstr($_template->_jquery,'true')){ ?> <input type='submit'  name='absenden' value='CANCEL' > <?php } ?>
			</td>
			<td class=td_background_heute align=left width="50%" align="center">
				<input type="submit" name="absenden" value="OK" >
			</td>
		</tr>		
	</table>
</form>