<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.011
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
$_pfad = "./Data/".$_user->_ordnerpfad."/Dokumente/";
check_htaccess_pdf($_user->_ordnerpfad);
echo "<div id='divpdf'>\n";
$folder1 = opendir($_pfad);
while($fA1 = readdir($folder1))
{
	if($fA1 == ".." or $fA1 == ".")
	{
	}
	else
	{
		if(!is_dir($fA1)) $afile[] = $fA1;
	}
}
closedir($folder1);
if($afile) rsort($afile);
$anz     = count($afile);
$aktuell = date("Y", time());
for($i = 0;$i < $anz;$i++)
{
	if($afile[$i] != "." && $afile[$i] != ".." && $afile[$i] != ".htaccess")
	{
		$jahr = trim(substr($afile[$i],0,4));
		if($aktuell == $jahr)
		{
			echo "";
		}
		else
		{
			$aktuell = $jahr;
			echo "</div><div id='divpdf'>";
		}
		//echo "<div id='pdf'><a id='pdfhref' href='./Data/".$_user->_ordnerpfad."/Dokumente/$afile[$i]' target='_new'><img src='images/ico/pdf.png' border=0 width=80><br>$afile[$i]</a>";
		// neuer Download nur über Sicherheit
		echo "<div id='pdf'><a href='download.php?datei=".$afile[$i]."&typ=pdf&pfad=".$_user->_ordnerpfad."' target='_new'><img id='zip' src='images/ico/pdf.png' border=0 width=86><br><font size=-4>$afile[$i]</font></a>";
		
		
		echo "</div>";
	}
}
echo "</div>\n";