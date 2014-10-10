<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
$_pluginpfad = "./plugins/";
$_folderlist = opendir($_pluginpfad);
while($fA=readdir($_folderlist)){
	if($fA== ".." or $fA == "."){
	}else{
		if(!is_dir($fA)) $pfile[]=$fA;
	}
}
closedir($_folderlist);
if($pfile) rsort($pfile);
$anz=count($pfile);
?>
<form name='plugin' action='?action=plugins' method='post' target='_self'>
	<table width="100%">
		<tr>
			<td width="100%" align="left">
				<select class="pluginselect" name="plugin" onchange="document.plugin.submit()">
				<option value="zeiterfassung" <?php if($_SESSION['plugin'] == "zeiterfassung" ) echo " selected "?>>Zeiterfassung</option>
					<?php
					for($i=0;$i<$anz;$i++){
						if($pfile[$i] != "." && $pfile[$i] != ".." && $pfile[$i] != ".htaccess"){
							echo '<option value="'.$pfile[$i].'"';
							if($_SESSION['plugin'] == $pfile[$i]) echo " selected ";
							echo '>'.$pfile[$i].'</option>';
						}
					} 
					?>
				</select>
			</td>								
		</tr>
	</table>
</form>