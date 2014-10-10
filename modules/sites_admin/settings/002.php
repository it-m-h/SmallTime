<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.893
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
if(@$_POST['01'] ){
	$dom = new DOMDocument('1.0', 'utf-8');
	$root = $dom->createElement('multilogin');
	$dom->appendChild($root);
	
	$root->appendChild($guest = $dom->createElement("guest"));
	if(@$_POST['01']){
		$guest->appendChild($dom->createElement("ShowUsername", "true"));
		$_S1 = " checked";
	}else{
		$guest->appendChild($dom->createElement("ShowUsername", "false"));
		$_S1 = "";
	}
	if(@$_POST['02']){
		$guest->appendChild($dom->createElement("ShowUserOnline", "true"));
		$_S2 = " checked";
	}else{
		$guest->appendChild($dom->createElement("ShowUserOnline", "false"));
		$_S2 = "";
	}
	if(@$_POST['03']){
		$guest->appendChild($dom->createElement("ShowUserLastTime", "true"));
		$_S3 = " checked";
	}else{
		$guest->appendChild($dom->createElement("ShowUserLastTime", "fasle"));
		$_S3 = "";
	}		
	if(@$_POST['04']){
		$guest->appendChild($dom->createElement("ShowUserAllTime", "true"));
		$_S4 = " checked";
	}else{
		$guest->appendChild($dom->createElement("ShowUserAllTime", "fasle"));
		$_S4 = "";
	}	
	if(@$_POST['05']){
		$guest->appendChild($dom->createElement("ShowUserPic", "true"));
		$_S5 = " checked";
	}else{
		$guest->appendChild($dom->createElement("ShowUserPic", "fasle"));
		$_S5 = "";
	}	
	
	$root->appendChild($user = $dom->createElement("user"));
	if(@$_POST['11']){
		$user->appendChild($dom->createElement("ShowUsername", "true"));
		$_S11 = " checked";
	}else{
		$user->appendChild($dom->createElement("ShowUsername", "false"));
		$_S11 = "";
	}
	if(@$_POST['12']){
		$user->appendChild($dom->createElement("ShowUserOnline", "true"));
		$_S12 = " checked";
	}else{
		$user->appendChild($dom->createElement("ShowUserOnline", "false"));
		$_S12 = "";
	}
	if(@$_POST['13']){
		$user->appendChild($dom->createElement("ShowUserLastTime", "true"));
		$_S13 = " checked";
	}else{
		$user->appendChild($dom->createElement("ShowUserLastTime", "fasle"));
		$_S13 = "";
	}		
	if(@$_POST['14']){
		$user->appendChild($dom->createElement("ShowUserAllTime", "true"));
		$_S14 = " checked";
	}else{
		$user->appendChild($dom->createElement("ShowUserAllTime", "fasle"));
		$_S14 = "";
	}	
	if(@$_POST['15']){
		$user->appendChild($dom->createElement("ShowUserPic", "true"));
		$_S15 = " checked";
	}else{
		$user->appendChild($dom->createElement("ShowUserPic", "fasle"));
		$_S15 = "";
	}	
	
	$root->appendChild($admin = $dom->createElement("admin"));
	if(@$_POST['21']){
		$admin->appendChild($dom->createElement("ShowUsername", "true"));
		$_S21 = " checked";
	}else{
		$admin->appendChild($dom->createElement("ShowUsername", "false"));
		$_S21 = "";
	}
	if(@$_POST['22']){
		$admin->appendChild($dom->createElement("ShowUserOnline", "true"));
		$_S22 = " checked";
	}else{
		$admin->appendChild($dom->createElement("ShowUserOnline", "false"));
		$_S22 = "";
	}
	if(@$_POST['23']){
		$admin->appendChild($dom->createElement("ShowUserLastTime", "true"));
		$_S23 = " checked";
	}else{
		$admin->appendChild($dom->createElement("ShowUserLastTime", "fasle"));
		$_S23 = "";
	}		
	if(@$_POST['24']){
		$admin->appendChild($dom->createElement("ShowUserAllTime", "true"));
		$_S24 = " checked";
	}else{
		$admin->appendChild($dom->createElement("ShowUserAllTime", "fasle"));
		$_S24 = "";
	}	
	if(@$_POST['25']){
		$admin->appendChild($dom->createElement("ShowUserPic", "true"));
		$_S25 = " checked";
	}else{
		$admin->appendChild($dom->createElement("ShowUserPic", "fasle"));
		$_S25 = "";
	}	
		
	$dom->save('./include/Settings/multilogin.xml');
}
if(file_exists ("./include/Settings/multilogin.xml")){
	$multilogin= simplexml_load_file("./include/Settings/multilogin.xml");
}else{
	create_mulitsettings();
	$multilogin= simplexml_load_file("./include/Settings/multilogin.xml");
}


if($multilogin->guest->ShowUsername=="true"){
	$_S1 = " checked"; 
}
if($multilogin->guest->ShowUserOnline=="true"){
	$_S2 = " checked";
}
if($multilogin->guest->ShowUserLastTime=="true"){
	$_S3 = " checked";
}
if($multilogin->guest->ShowUserAllTime=="true"){
	$_S4 = " checked"; 
}
if($multilogin->guest->ShowUserPic=="true"){
	$_S5 = " checked";
}

if($multilogin->user->ShowUsername=="true"){
	$_S11 = " checked"; 
}
if($multilogin->user->ShowUserOnline=="true"){
	$_S12 = " checked";
}
if($multilogin->user->ShowUserLastTime=="true"){
	$_S13 = " checked";
}
if($multilogin->user->ShowUserAllTime=="true"){
	$_S14 = " checked"; 
}
if($multilogin->user->ShowUserPic=="true"){
	$_S15 = " checked";
}

if($multilogin->admin->ShowUsername=="true"){
	$_S21 = " checked"; 
}
if($multilogin->admin->ShowUserOnline=="true"){
	$_S22 = " checked";
}
if($multilogin->admin->ShowUserLastTime=="true"){
	$_S23 = " checked";
}
if($multilogin->admin->ShowUserAllTime=="true"){
	$_S24 = " checked"; 
}
if($multilogin->admin->ShowUserPic=="true"){
	$_S25 = " checked";
}
?>
<form method="POST" action="?action=settings&menue=multilogin">
	<table width="100%" border="0">
		<thead>
			<td class="td_background_top">Gast (Mehrbenutzersystem - Anzeige):</td>
			<td class="td_background_top" align="center">Anzeige:</td>
		</thead>
		<tr>
			<td class="td_background_tag">Anzeige des Usernamens</td>
			<td class="td_background_tag" align="center"><input type="checkbox" name="01" value="1"<?php echo $_S1 ;?>></td>
		</tr>
		<tr>
			<td class="td_background_tag">Anzeige ob Anwesend oder Abwesend (gr&uuml;n oder rot)</td>
			<td class="td_background_tag" align="center"><input type="checkbox" name="02" value="1"<?php echo $_S2 ;?>></td>
		</tr>
		<tr>
			<td class="td_background_tag">Letzte Stempelzeit von Heute anzeigen</td>
			<td class="td_background_tag" align="center"><input type="checkbox" name="03" value="1"<?php echo $_S3 ;?>></td>
		</tr>
		<tr>
			<td class="td_background_tag">Alle Stempelzeiten von Heute anzeigen</td>
			<td class="td_background_tag" align="center"><input type="checkbox" name="04" value="1"<?php echo $_S4 ;?>></td>
		</tr>
		<tr>
			<td class="td_background_tag">Bid des Users anzeigen</td>
			<td class="td_background_tag" align="center"><input type="checkbox" name="05" value="1"<?php echo $_S5 ;?>></td>
		</tr>
	</table>	
	<table width="100%" border="0">
		<thead>
			<td class="alert alert-success">Angemeldeter User:</td>
			<td class="alert alert-success" align="center">Anzeige:</td>
		</thead>
		<tr>
			<td class="td_background_tag">Anzeige des Usernamens</td>
			<td class="td_background_tag" align="center"><input type="checkbox" name="11" value="1"<?php echo $_S11 ;?>></td>
		</tr>
		<tr>
			<td class="td_background_tag">Anzeige ob Anwesend oder Abwesend (gr&uuml;n oder rot)</td>
			<td class="td_background_tag" align="center"><input type="checkbox" name="12" value="1"<?php echo $_S12 ;?>></td>
		</tr>
		<tr>
			<td class="td_background_tag">Letzte Stempelzeit von Heute anzeigen</td>
			<td class="td_background_tag" align="center"><input type="checkbox" name="13" value="1"<?php echo $_S13 ;?>></td>
		</tr>
		<tr>
			<td class="td_background_tag">Alle Stempelzeiten von Heute anzeigen</td>
			<td class="td_background_tag" align="center"><input type="checkbox" name="14" value="1"<?php echo $_S14 ;?>></td>
		</tr>
		<tr>
			<td class="td_background_tag">Bid des Users anzeigen</td>
			<td class="td_background_tag" align="center"><input type="checkbox" name="15" value="1"<?php echo $_S15 ;?>></td>
		</tr>
	</table>	
	<table width="100%" border="0">
		<thead>
			<td class="alert alert-error">Admin - Interface:</td>
			<td class="alert alert-error" align="center">Anzeige:</td>
		</thead>
		<tr>
			<td class="td_background_tag">Anzeige des Usernamens</td>
			<td class="td_background_tag" align="center"><input type="checkbox" name="21" value="1"<?php echo $_S21 ;?>></td>
		</tr>
		<tr>
			<td class="td_background_tag">Anzeige ob Anwesend oder Abwesend (gr&uuml;n oder rot)</td>
			<td class="td_background_tag" align="center"><input type="checkbox" name="22" value="1"<?php echo $_S22 ;?>></td>
		</tr>
		<tr>
			<td class="td_background_tag">Letzte Stempelzeit von Heute anzeigen</td>
			<td class="td_background_tag" align="center"><input type="checkbox" name="23" value="1"<?php echo $_S23 ;?>></td>
		</tr>
		<tr>
			<td class="td_background_tag">Alle Stempelzeiten von Heute anzeigen</td>
			<td class="td_background_tag" align="center"><input type="checkbox" name="24" value="1"<?php echo $_S24 ;?>></td>
		</tr>
		<tr>
			<td class="td_background_tag">Bid des Users anzeigen</td>
			<td class="td_background_tag" align="center"><input type="checkbox" name="25" value="1"<?php echo $_S25 ;?>></td>
		</tr>
	</table>	
	<br />
	<input type="submit" name="absenden" value="absenden"/>
</form>