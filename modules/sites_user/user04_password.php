<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.87
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/

//echo getinfotext( "Passwort ver&auml;ndern." ,"td_background_top");
?>


				
<div id="formular">
<form method="POST" action="?action=password">
<?php
$tbl = new HTML_Table('', 'table');
$tbl->addRow();
	$tbl->addCell('altes Passwort', 'td_background_wochenende" width="200');
	$tbl->addCell('<input type="password" name="old"/>', 'td_background_tag');
$tbl->addRow();
	$tbl->addCell('neues Passwort', 'td_background_wochenende" width="200');
	$tbl->addCell('<input type="password" name="new1"/>', 'td_background_tag');	
$tbl->addRow();
	$tbl->addCell('Passwort wiederholen', 'td_background_wochenende" width="200' );
	$tbl->addCell('<input type="password" name="new2"/>', 'td_background_tag');	
	
$tbl->addRow();
	$tbl->addCell('<input type="submit" value="senden" name="senden"/>', 'alert-error', 'data', array('colspan'=>2) );
echo $tbl->display();

?>

</form>
</div>
