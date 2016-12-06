<?php
/********************************************************************************
* Small Time - Datei - download
/*******************************************************************************
* Version 0.9.009
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
//Session starten
//download.php?datei=2016&type=zip

//    PDF :  /Data/administrator/Dokumente/2016.12.pdf
//    ZIP :  /Data/_zip/administrator/Dokumente/2016.12.pdf

session_start();
if(@$_SESSION['admin']==NULL OR @$_SESSION['admin']==""){
	//echo "kein Download möglich, sie sind nicht eingeloggt!";
	echo "Sie besitzen keine Berechtigung.";
}else{
	$datei = @$_GET['datei'];
	$typ = @$_GET['typ'];
	if($typ =="zip"){
		$pfad = './Data/_zip/'.$_SESSION['admin'].'/';
		$datei = $datei;
		$type = 'application/zip';
		getfile($pfad, $datei, $type);
	}elseif($typ =="pdf"){
		$pfad = './Data/'.$_SESSION['admin'].'/Dokumente/';
		$datei = $datei;
		$type = 'application/pdf';
		getfile($pfad, $datei, $type);
	}else{
		echo "File - Typ wird nicht unterstützt.";
	}
}
function getfile($pfad, $datei, $type){
	$download= $pfad. $datei;
	if(file_exists ($download)){
		header("Content-Type: $type");
		if($type =='application/zip'){
			header("Content-Disposition: attachment; filename=\"$datei\"");
		}else{
			header("Content-Disposition: inline; filename=". $datei); 
		}
		readfile($download);
		echo "Download von der Datei erfolgreich.";
	}else{
		echo "Download von der Datei nicht möglich.";
	}
}
?>