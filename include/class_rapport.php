<?php
/*******************************************************************************
* Rapport
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_rapport{
	function __construct(){	
	}
	function __destruct(){
	}
	function insert_rapport($_ordnerpfad, $_timestamp){
		$rapport = $_POST['rapport'];
		$_file = "./Data/".$_ordnerpfad."/Rapport/".date("Y.m.d", $_timestamp);
		$_folder = "./Data/".$_ordnerpfad."/Rapport/";
		if (!file_exists($_folder)){
			mkdir($_folder, 0770);
		}		
		$fp = fopen($_file,"w+");
		fputs($fp, $rapport);
		fclose($fp);
	}
	function delete_rapport($_ordnerpfad, $_timestamp){
		$_file = "./Data/".$_ordnerpfad."/Rapport/".date("Y.m.d", $_timestamp);
		if(file_exists($_file)){
			unlink($_file);
		}
	}
	function get_rapport($_ordnerpfad, $_timestamp){
		$_file = "./Data/".$_ordnerpfad."/Rapport/".date("Y.m.d", $_timestamp);
		if(!file_exists($_file)){
			$_txt = "Rapport f&uuml;r den " . date("d.m.Y",$_timestamp). "\n-";
			$_txt = "";
		}else{
			$_txt = file_get_contents($_file);
		}
		return $_txt;
	}
}