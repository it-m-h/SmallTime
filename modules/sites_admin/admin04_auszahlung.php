<?php
/********************************************************************************
* Small Time - Auszahlung hinzufügen
/*******************************************************************************
* Version 0.84
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
/*
echo "\$_SESSION['datenpfad']".$_SESSION['datenpfad']."<br>";
echo "\$_SESSION['username']".$_SESSION['username']."<br>";

echo "Monat : " .$_GET['monat'] . "<br>";
echo "Jahr: " .$_GET['jahr'] . "<br>";
echo "<hr>";*/



?>
<Form action="?action=update_ausz&admin_id=<?php echo $_id; ?>&monat=<?php echo $auszahlung->_ausz_monat; ?>&jahr=<?php echo $auszahlung->_ausz_jahr; ?>" method="post" target="_self">
	<table width="100%" border="0" cellpadding="5" cellspacing="1 	">
		<tr>
			<td class=td_background_tag align="center">
				Anzahl : <input size="10" type="text" name="anzahl" value="<?php echo $auszahlung->_ausz_anz;?>">
			</td>
		</tr>
		<tr>
			<td class="td_background_heute" align="center">
				<input type="submit" name="absenden" value="speichern" >
			</td>
		</tr>
	</table>
</Form>