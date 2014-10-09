<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
if(@$_POST['upload']=="Hochladen"){	
	$uploaddir = './images/';
	$uploadfile = $uploaddir . 'smalltime.jpg';
	if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)){
		$bildtemp = $_template->get_templatepfad() . "./images/smalltime.jpg";
		if(file_exists ($bildtemp)){
			copy($uploadfile, $bildtemp);
		}
	}	 
}
?>
<form  enctype="multipart/form-data" method="POST" action="?action=settings&menue=logoundfarben">
	<table width="100%" border="0">
		<tr>
			<td class="td_background_top" width="150">Logo:</td>
		</tr>
		<tr>
			<td ><img src="./images/smalltime.jpg" alt="" width="100%"/></td>
		</tr>
		<tr>
			<td >
				<br />(Gr&ouml;sse: 1200x122 Pixel, JPG - Datei)<br />
				<input type="hidden" name="MAX_FILE_SIZE" value="9999999" />
				<input type="file" name="userfile"><br />
				<input type="submit" name='upload' value="Hochladen">	
			</td>
		</tr>
	</table>	
</form>