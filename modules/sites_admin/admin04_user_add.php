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
<script type="text/javascript">
	function check()
	{
		var f = document.Formular;
		var fehler = "";


		if (f._a.value == "")
		{
			fehler += "- bitte Ordnername eingeben\n";
		}

		if (clean(f._a.value))
		{
			fehler += "- Der Ordnername ent"+unescape("%E4")+"llt noch Sonderzeichen\n";
		}

		if (hasWhiteSpace(f._a.value))
		{
			fehler += "- bitte keine Leerzeichen im Ordnername (Pfad) verwenden\n";
		}

		if (f._b.value == "")
		{
			fehler += "- bitte Loginname eingeben\n";
		}

		if (f._c.value == "")
		{
			fehler += "- bitte Passwort eingeben\n";
		}

		if (fehler != "")
		{
			var fehlertext = "Fehler:\n\n";
			fehlertext += fehler;
			alert(fehlertext + "\nBitte f"+unescape("%FC")+"llen Sie die Felder richtig aus. Danke.");
			return false;
		}else
		{
			return true;
		}

	}

	function hasWhiteSpace(s)
	{
		return /\s/g.test(s);
	}

	function clean(e)
	{
		var str=e;
		var good=" ,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,0,1,2,3,4,5,6,7,8,9,0,-,_".split(",");
		for(var i=0;i<good.length;i++) while(str.toLowerCase().indexOf(good[i])!=-1) str=str.toLowerCase().replace(good[i],"")
		if(str!='') return true;
	}
</script>
<form action='?action=user_add' method='post' target='_self' name="Formular" onsubmit="return check();">
	<table width=100% border=0 cellpadding=3 cellspacing=1>
		<tr>
			<td align=left class=td_background_tag width=300>
				Pfad (Ordnername ./Data/XXXXX)
			</td>
			<td align=left class=td_background_tag >
				<input type='text' name='_a' value='' size='70'>
			</td>
			<td align=left class=td_background_tag width=16>
				<img title='Pfad zu den Daten f&uuml;r diesen Benutzer. Ohne Sonderzeichen, leerschl&auml;ge oder Umlaute!' src='images/icons/information.png' border=0>
			</td>
		</tr>
		<tr>
			<td align=left class=td_background_tag>
				Loginname (empfehlung: Kurzzeichen)
			</td>
			<td align=left class=td_background_tag>
				<input type='text' name='_b' value='' size='70'>
			</td>
			<td align=left class=td_background_tag width=16>
				<img title='Loginnname, ohne Sonderzeichen, leerschl&auml;ge oder Umlaute!' src='images/icons/information.png' border=0>
			</td>
		</tr>
		<tr>
			<td align=left class=td_background_tag>
				neues Passwort (nur neu vergeben m&ouml;glich!)
			</td>
			<td align=left class=td_background_tag >
				<input type='text' name='_c' value='1234' size='70'>
			</td>
			<td align=left class=td_background_tag width=16>
				<img title='Neues Passwort.' src='images/icons/information.png' border=0>
			</td>
		</tr>
		<tr>
			<td align=left class=td_background_tag>
				RFID - Chip Nummer (idtime.php?rfid=XXXXX)
			</td>
			<td align=left class=td_background_tag >
				<input type='text' name='_d' value='' size='70'>
			</td>
			<td align=left class=td_background_tag width=16>
				<img title='RFID - Nummer, muss einmalig sein!' src='images/icons/information.png' border=0>
			</td>
		</tr>
		<tr>
			<td class=td_background_top>
			</td>
			<td COLSPAN=2 class=td_background_top width=200>
				<input type='submit' name='absenden' value='OK' >
				<input type='submit'  name='absenden' value='CANCEL' >
			</td>
		</tr>
	</table>
</form>
