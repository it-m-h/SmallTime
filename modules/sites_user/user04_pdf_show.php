<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.009
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
/*
NEU: Download und Anzeige von PDF nur noch über download.php

$_file = "./Data/".$_user->_ordnerpfad."/Dokumente/".date("Y.m", $_time->_timestamp).".pdf";
echo $_file;
echo "<br>";
echo "<iframe src='".$pdf."' height='700' width='100%'></iframe>";
*/

include ('./modules/sites_user/user04_pdf.php');

?>