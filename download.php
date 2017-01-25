<?php
/********************************************************************************
* Small Time - Datei - download
/*******************************************************************************
* Version 0.9.012
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
//Session starten
//download.php?datei=2016&type=zip

//    PDF :  /Data/administrator/Dokumente/2016.12.pdf
//    ZIP :  /Data/_zip/administrator/Dokumente/2016.12.pdf

session_start();
include_once ('./include/class_filehandle.php');

if(@$_SESSION['admin']==NULL OR @$_SESSION['admin']==""){
	//echo "kein Download möglich, sie sind nicht eingeloggt!";
	echo "Sie besitzen keine Berechtigung.";
}else{
	$datei = @$_GET['datei'];
	$typ = @$_GET['typ'];
	if($typ =="zip"){
		$pfad = './Data/_zip/'.$_SESSION['admin'].'/';
		//Admins dürfen alle ZIP downloaden und pfad im GET benutzen
		// $_SESSION['showpdf']  ist 1 wenn ein Administrator eingeloggt ist
		if($_SESSION['showpdf']==1 && isset($_GET['pfad'])){
			$pfad = './Data/_zip/'.$_GET['pfad'].'/';
			//echo "ZIP mit Pfad gesetzt.";
		}
		$type = 'application/zip';
		getfile($pfad, $datei, $type);
	}elseif($typ =="pdf"){
		$pfad = './Data/'.$_SESSION['admin'].'/Dokumente/';
		//Admins dürfen alle PDF downloaden
		// $_SESSION['showpdf']  ist 1 wenn ein Administrator eingeloggt ist
		if($_SESSION['showpdf']==1 && isset($_GET['pfad'])){
			$pfad = './Data/'.$_GET['pfad'].'/Dokumente/';
			//echo "PDF mit Pfad gesetzt.";
		}
		$type = 'application/pdf';
		getfile($pfad, $datei, $type);
	}else{
		echo "File - Typ wird nicht unterstützt.";
	}
}
function getfile($pfad, $datei, $type){
	$download= $pfad. $datei;
	if(file_exists ($download)){
		header('Content-Description: File Transfer');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		if($type =='application/zip'){
			//header("Content-type: ".$type);
			header('Content-type: application/octet-stream');
			header("Content-Disposition: attachment; filename=" .  basename($datei));
		}else{
			header("Content-type: " .$type);
			header("Content-Disposition: inline; filename=". $datei); 
		}
		header("Pragma: no-cache");
		header("Expires: 0");
		header('Content-Length: ' . filesize($download)); 
		while (ob_get_level()) {
		    ob_end_clean();
		}
		readfile($download);
		//echo "Download von der Datei erfolgreich.";
	}else{
		echo "Download von der Datei nicht möglich.";
	}
}
?>