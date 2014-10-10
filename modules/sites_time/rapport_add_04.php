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
<form action="?action=insert_rapport&timestamp=<?php echo $_time->_timestamp ?>&token=<?php echo $token ?>" method="post" target="_self">
	<table width="100%" border="0" cellpadding="5" cellspacing="2">
		<tr>
			<td class=td_background_tag align=left>
				<textarea cols="120" rows="5" name="rapport" ><?php echo $_rapport->get_rapport($_user->_ordnerpfad, $_time->_timestamp); ?></textarea>
			</td>
		</tr>
		<tr>
			<td class=td_background_top align=left width="50%" align="center">
				<input type="submit" name="absenden" value="UPDATE" >
			<?php if (!strstr($_template->_jquery,'true')){ ?> <input type="submit" name="absenden" value="CANCEL" ><?php } ?> 
			</td>
		</tr>
		<tr>
			<td class=td_background_heute align=left width="50%" align="center">
				<input type='submit'  name='absenden' value='DELETE' >
			</td>
		</tr>
	</table>
</form>