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
<form action="?action=insert_absenz&timestamp=<?php echo $_time->_timestamp ?>&token=<?php echo $token ?>" method="post" target="_self" name="form">
	<table width="100%" border="0" cellpadding="5" cellspacing="2">
		<tr>
			<td class=td_background_wochenende width="300" align=left >Absenzen Grund f&uuml;r den : <?php echo date("d.m.y", $_time->_timestamp); ?></td>
			<td class=td_background_tag align=left>
				<?php
				echo "    <select name='_grund' size='1'>\n";
				foreach($_absenz->_filetext as $_tmp){
					$_tmp = explode(";", $_tmp);
					echo "       <option value='$_tmp[1]'>$_tmp[0]</option>\n";
				}
				echo "      </select>\n";
				?>  Anzahl (1 oder 0.5) : <input type="text" name="_anzahl" value="1" size="3">
			</td>
		</tr>
	        <tr>
                        <td class=td_background_heute align=left width="50%" align="center">
                        	<?php if (!strstr($_template->_jquery,'true')){ ?> <input type='submit'  name='absenden' value='CANCEL' ><?php } ?> 
                        </td>
                        <td class=td_background_heute align=left width="50%" align="center">
                            	<input type="submit" name="absenden" value="OK" >
                        </td>
                </tr>
	</table>
</form>