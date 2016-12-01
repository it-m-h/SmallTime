<?php  
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
// Settings des Templates mit Bootstrap
if (strstr($_template->_bootstrap,'true')){
?>
	<ul class="nav nav-tabs adminmenu">    
                <li<?php echo $_action=="anwesend" ?  ' class="active"':''; ?>><a id="Home" title="Home" href="?action=anwesend">Home</a></li>
                <li<?php echo $_action=="user_add" ?  ' class="active"':''; ?>><a id="Mitarbeiter" title="Mitarbeiter erstellen" href="?action=user_add">new MA</a></li>
                <li<?php echo $_action=="group" ?  ' class="active"':''; ?>><a id="Gruppen" title="Gruppen verwalten" href="?action=group">Gruppe</a></li>
                <li<?php echo $_action=="design" ?  ' class="active"':''; ?>><a id="Design" title="Design" href="?action=design">Design</a></li>
                <li<?php echo $_action=="settings" ?  ' class="active"':''; ?>><a id="Settings" title="Settings" href="?action=settings">Setting</a></li>
                <li<?php echo $_action=="feiertage" ?  ' class="active"':''; ?>><a id="Feiertag" title="Zus&auml;tzlicher Feiertag" href="?action=feiertage">Feiertage</a></li>
                <li<?php echo $_action=="import" ?  ' class="active"':''; ?>><a id="Import" title="Import von csv" href="?action=import">CSV Import</a></li>
                <li<?php echo $_action=="debug_info" ?  ' class="active"':''; ?>><a id="Status" title="Status / Meldungen" href="?action=debug_info">Status</a></li>
                <li<?php echo $_action=="idtime-generate" ?  ' class="active"':''; ?>><a id="Codes" title="QR-Codes" href="?action=idtime-generate">QR-Codes</a></li>
                <li<?php echo $_action=="pdfgenerate" ?  ' class="active"':''; ?>><a id="pdfgenerate" title="pdfgenerate" href="?action=pdfgenerate">PDF</a></li>
                <li<?php echo $_action=="logout" ?  ' class="active"':''; ?>><a id="Logout" title="Logout" href="?action=logout">Logout</a></li>
        </ul>
<?php 
//TODO : Template ohne Bootstrap -> löschen
}else{ ?>
	<div class="pagination">
	<ul>
		<li><a id="Home" title="Home" href="?action=anwesend">Home</a></li>
		<li><a id="Mitarbeiter" title="Mitarbeiter erstellen" href="?action=user_add">new MA</a></li>
		<li><a id="Gruppen" title="Gruppen verwalten" href="?action=group">Gruppe</a></li>
		<li><a id="Design" title="Design" href="?action=design">Design</a></li>
		<li><a id="Settings" title="Settings" href="?action=settings">Setting</a></li>
		<li><a id="Feiertag" title="Zus&auml;tzlicher Feiertag" href="?action=feiertage">Feiertage</a></li>
		<li><a id="Import" title="Import von csv" href="?action=import">CSV Import</a></li>
		<li><a id="Status" title="Status / Meldungen" href="?action=debug_info">Status</a></li>
		<li><a id="Codes" title="QR-Codes" href="?action=idtime-generate">QR-Codes</a></li>
		<li><a id="Logout" title="Logout" href="?action=logout">Logout</a></li>
	</ul>
	</div>
<?php } ?>