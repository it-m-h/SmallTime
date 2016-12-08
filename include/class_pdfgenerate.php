<?php
/*******************************************************************************
*PDF für alle Mitarbeiter generieren und anzeigen
/*******************************************************************************
* Version 0.9.011
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
class pdfgenerate{
	public $_monat= NULL;
	public $_jahr	= NULL;
	public $_html	= NULL;
	public $_users	= NULL;
	
	function __construct($monat, $jahr, $users){
		$this->_monat 	= $monat;
		$this->_jahr 	= $jahr;
		$this->_users 	= $users;
		$this->_html	= "constructor - TEXT von pdfgenerate";	
		if(isset($_GET['function'])){
			//wenn GET - function  = createpdf -> pdf vom gewählen Monat erstellen und json übermitteln -> alle MA generieren wurde noch nicht realisiert
			if($_GET['function']=='createpdf'){
				$this->create_pdf($this->_monat ,$this->_jahr);
			}else{
				//wenn GET - function  = getpdf -> pdf vom gewählen Monat anzeigen (json übermitteln) 
				$this->get_pdf($this->_monat , $this->_jahr);	
			}	
		}else{
			//wenn nix angegeben wurde , getpdf
			$this->get_pdf($this->_monat , $this->_jahr);
		}	
	}
	function create_pdf(){
		//$_POST = json_decode(file_get_contents('php://input'),true);
		$this->_html = "create pdf: ". $this->_monat . "." . $this->_jahr ;
	}	 
	function get_pdf(){
		$arr = array();
		$i=0;
		unset($this->_users->_array[0]);
		foreach($this->_users->_array as $user){
			//Dokumenten - Pfad und Loginname
			$arr[$i]['pfad'] 	= './Data/' . $user['0'] . '/';
			$arr[$i]['pfad'] 	=  $user['0'];
			$arr[$i]['name'] 	= $user['1'];
			// Anzeigename des Mitarbeiters
			$userdata = file( './Data/' . $user['0'] . '/userdaten.txt');
			$z=0;
			foreach($userdata as $temp){
				$userdata[$z] = trim($temp);
				$z++;
			}
			$arr[$i]['username'] 	= $userdata[0];
			$arr[$i]['userstart'] 	= $userdata[1];
			$arr[$i]['userstartmonat'] 	= date("m", $userdata[1]);
			$arr[$i]['userstartjahr'] 	= date("Y",$userdata[1]);
			$arr[$i]['pdflink'] = './Data/' . $user['0'] . '/Dokumente/' . $this->_jahr . '.' . $this->_monat . '.pdf';
			$arr[$i]['pdfdatei'] =  $this->_jahr . '.' . $this->_monat . '.pdf';
			$arr[$i]['pdflinkcreate'] =' ';
			$arr[$i]['pdfexist'] = '0'; 
			// check ob file existiert
			if(file_exists($arr[$i]['pdflink'] )){
				$arr[$i]['pdfexist'] = '1'; 
				// Link zum PDF
				$arr[$i]['pdflink'] ='
				<a id="pdfhref" href="download.php?datei=' .$arr[$i]['pdfdatei'] . '&typ=pdf&pfad='.$user['0'].'" target="_new">
				<img src="images/icons/page_white_acrobat.png" border="0" > - 
				./Data/' . $user['0'] . '/Dokumente/' . $this->_jahr . '.' . $this->_monat . '.pdf
				</a>';
			}else{
				if( $this->_jahr < $arr[$i]['userstartjahr']  ){
					$arr[$i]['pdflink'] = "vor Anstellungsbeginn";
				}elseif($this->_jahr == $arr[$i]['userstartjahr'] && $this->_monat < $arr[$i]['userstartmonat']){		
					$arr[$i]['pdflink'] = "vor Anstellungsbeginn";		
				}else{
					$arr[$i]['pdfexist'] = '1';
					$temptime = mktime(0, 0, 0, $this->_monat, 1, $this->_jahr);
					// Link um ein PDF zu erstellen
					$arr[$i]['pdflinkcreate'] = '<p onclick="create('.($i+1).','.$temptime.')" class=" pdfcreate btn btn-danger">PDF erstellen</p>';	
				}			
			}
			$i++;
		}
		$this->_html = json_encode($arr);
	}
	function output(){
		return $this->_html;
	}

}