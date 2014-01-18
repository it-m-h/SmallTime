<?php
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
			<td align="right">Plugin : </td>
			<td width="100" align="right">
				<select name="plugin" onchange="document.plugin.submit()">
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