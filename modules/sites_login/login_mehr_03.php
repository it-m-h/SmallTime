<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.872
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
if(strstr($_template->_bootstrap,'true')){
?>	
<img style="width: 100%" src="images/ico/groups.png">
<form style="padding: 5px;" name="login" action='index.php?action=login_mehr&timestamp=<?php echo $_time->_timestamp ?>&token=<?php echo $token ?>&group=<?php echo $_grpwahl+1; ?>' method='post' target='_self'>
        <div class="alert alert-block">
                <h4>Schnell-Stempeln!</h4>
        </div>
                        <label for="inputname">Name:</label>
                        <input id="inputname" type='text' name='_n' value='' size='20' onfocus="this.value=''" ><i style="margin-left: 5px;" class="icon-user"></i>

                        <label style="margin-top: 10px;" for="inputpasswd">Passwort:</label>
                        <input id="inputpasswd" type='password' name='_p' value='' size='20' onfocus="this.value=''" ><i style="margin-left: 5px;" class="icon-lock"></i>

                        <input style="margin-top: 20px;" class="btn" type='submit' name='login' value='Stempelzeit eintragen' >
                        <input type='hidden' name='tmp_log' value='<?php echo $_SESSION['tmp_login']; ?>' >

</form>
<?php
//-------------------------------------------------------------------------------------------
// MÃ¶gliche Erweiterung:
// Stempelzeiten nach login anzeigen lassen und automatisch ausblenden lassen
// MONATSARRY Ã¼berarbeiten, da Summen nicht berechnet werden
//-------------------------------------------------------------------------------------------
if($_POST['login']){
        //include ("./include/setting.php");
        //include ("./templates/".$_template."/statistik.php");
        //echo "Userdaten und Sessons lÃ¶schen nach Login.............<br>";
        $_SESSION['admin']=array();
        $_SESSION['admin']="";
        //session_destroy();
        $_Userpfad = "";
}
?>
<a style="float:left; margin: 2px; padding: 3px; background-color: #bebebe; width: 45%" href="admin.php">
        <img style="width: 100%%;" src="images/ico/admin.png" alt="" />
        <br>
        Admin - Login
</a>
<a style="float:left; margin: 2px; padding: 3px; background-color: #bebebe; width: 45%" href="index.php?group=-1"><img style="width: 100%" src="images/ico/user-icon.png" alt="" />
        <br>
        Single - Login
</a>
<span class="clearfix"></span>
<?php	
}else{ ?>
<Form name="login" action='index.php?action=login_mehr&timestamp=<?php echo $_time->_timestamp ?>&token=<?php echo $token ?>&group=<?php echo $_grpwahl+1; ?>' method='post' target='_self'>
	<table width=100% border=0 cellpadding=3 cellspacing=1>
		<tr>
			<td align=left COLSPAN=2 class=td_background_top width=60>Stempelzeit eintragen :</td>
		</tr>
		<tr>
			<td align=left class=td_background_tag width=100>Name :</td>
			<td align=left class=td_background_tag ><input type='text' name='_n' value='' size='20' onfocus="this.value=''" ></td>
		</tr>
		<tr>
			<td align=left class=td_background_tag width=100>Passwort :</td>
			<td align=left class=td_background_tag ><input type='password' name='_p' value='' size='20' onfocus="this.value=''" ></td>
		</tr>
		<tr>
			<td align=center COLSPAN=2 width=60>
				<input type='submit' name='login' value='Stempelzeit eintragen' >
				<input type='hidden' name='tmp_log' value='<?php echo $_SESSION['tmp_login']; ?>' >
			</td>
		</tr>
	</table>
</form>
<?php
//-------------------------------------------------------------------------------------------
// Mögliche Erweiterung:
// Stempelzeiten nach login anzeigen lassen und automatisch ausblenden lassen
// MONATSARRY &uuml;berarbeiten, da Summen nicht berechnet werden
//-------------------------------------------------------------------------------------------
if($_POST['login']){
	//include ("./include/setting.php");
	//include ("./templates/".$_template."/statistik.php");
	//echo "Userdaten und Sessons löschen nach Login.............<br>";
	$_SESSION['admin']=array();
	$_SESSION['admin']="";
	//session_destroy();
	$_Userpfad = "";
}
?>
<br><br><br>
<a href="?group=-1"><img height="80" src="images/ico/user-icon.png" alt="" /><br> Einzel - Login ...</a>
<hr>
<a href="admin.php">
	<img height="80'" src="images/ico/admin.png" alt="" />
	<br>
		Admin - Login ...
</a> <?php  } ?>