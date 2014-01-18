<?php
/*******************************************************************************
* Template - Klasse 
/*******************************************************************************
* Version 0.8
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_template{
	private $_templatepfad 	= "";
	private $_cookie		= "";
	private $_session       	= "";
	private $_template      	= "";
	private $_content       	= "";
	private $_startseite    	= "index.php";
	public  $_user01        	= "sites_user/user01.php";
	public  $_user02        	= "sites_user/user02.php";
	public  $_user03        	= "sites_user/user03.php";
	public  $_user04        	= "sites_user/user04.php";
	public  $_plugin        		= "modules/sites_plugin/plugin_null.php";
	public  $_mobile       		= "sites_mobile/mobile.php";
	public  $_modulpfad     	= "modules/";

	function __construct($_start){
		if($_COOKIE["designname"]){
			//ausgewähltes Design vom Cookie laden
			$this->_templatepfad = "./templates/".$_COOKIE["designname"]."/";
		}else{
			$this->_templatepfad = "./templates/smalltime/";
		}
		//index.php Template laden
		$this->_startseite = $_start;
		$this->_template = $this->_templatepfad.$this->_startseite;
	}

	function set_templatepfad($pfad){
		//neues gewähltes Design im Cookie speichern
		//setcookie("designname", $pfad, time()+2592000);
		setcookie("designname", $pfad, time()+2592000);
		//setcookie("designname", $pfad, time()+2592000);
		$this->_templatepfad = "./templates/".$pfad."/";
		//index.php Template laden
		$this->_template = $this->_templatepfad.$this->_startseite;
	}

	function get_templatepfad(){
		return $this->_templatepfad;
	}

	function get_template(){
		return $this->_template;
	}
	function get_content($_content){
		return $this->_templatepfad.$_content;
	}
	function get_user01(){
		return $this->_modulpfad.$this->_user01;
	}
	function get_user02(){
		return $this->_modulpfad.$this->_user02;
	}
	function get_user03(){
		return $this->_modulpfad.$this->_user03;
	}
	function get_user04(){
		return $this->_modulpfad.$this->_user04;
	}
	function get_plugin(){
		return $this->_plugin;
	}
	function get_mobile(){
		return $this->_modulpfad.$this->_mobile;
	}
	
	function set_user01($_name){
		$this->_user01 = $_name;
	}
	function set_user02($_name){
		$this->_user02 = $_name;
	}
	function set_user03($_name){
		$this->_user03 = $_name;
	}
	function set_user04($_name){
		$this->_user04 = $_name;
	}
	function set_plugin($_name){
		$this->_plugin = $_name;
	}
	function set_mobile($_name){
		$this->_mobile = $_name;
	}
	function checkhtml($string){		
		$string	= trim($string);
		$string = htmlspecialchars($string);
		return $string;
	}
}
?>