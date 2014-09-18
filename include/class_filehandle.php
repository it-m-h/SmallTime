<?php
/*******************************************************************************
* Filehandle (fopen)
/*******************************************************************************
* Version 0.892
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_filehandle{
	public $_filename 	= ""; 
	public $_filepfad 	= "";
	public $_array	= NULL;
	
	function __construct($_filepfad, $_filename, $_trennzeichen){
		$this->_filename = $_filename;
		$this->_filepfad = $_filepfad;
		$this->mkfile();
		if(file_exists($_filepfad.$_filename)){
			$this->_array = file($_filepfad.$_filename);
			if(!$this->_array ){
				$this->_array[] = "keine Daten vorhanden!";
			}
			$i=0;
			foreach($this->_array as $zeile){
				if(strpos($zeile, $_trennzeichen)){
					$this->_array[$i] = explode($_trennzeichen, $this->_array[$i]);
					$z=0;
					foreach($this->_array[$i] as $spalte){
						$this->_array[$i][$z] = trim($spalte);
						$z++;
					}
				}
				$i++;
			}
		}else{
			//echo "File existiert nicht";
		}	
	}
	function mkfile(){
		if(!file_exists ($this->_filepfad)){
			mkdir ($this->_filepfad);
		}
		if(!file_exists ($this->_filepfad.$this->_filename)){
			$neu = "";
			$open = fopen($this->_filepfad.$this->_filename,"w+");
			fwrite ($open, $neu);
			fclose($open);
		}
	}
	function get_array(){
		return $this->_array;	
	}
	
	function user_exist($name){
		$inhalt = file($this->_filepfad.$this->_filename);
		$i=0;
		foreach($inhalt as $temp){
			$temp = explode(";",$temp);
			if (strstr($temp[1], $name)) return true;
			//echo "vergleiche : " . $temp[1]." mit ".$name. "<br>";
			$i++;		
		}
		return false;
	}
	
	function get_anzahl(){
		return count(file($this->_filepfad.$this->_filename))-1;
	}
		
	function insert_line($text){
		$_zeilenvorschub = "\r\n";
		$_file = $this->_filepfad.$this->_filename;
		$fp = fopen($_file,"a+");
		fputs($fp, $text);
		fputs($fp, $_zeilenvorschub);
		fclose($fp);	
	}
	function insert_line_top($text){
		$_max = 49;
		$_zeilenvorschub = "\r\n";
		$_file = $this->_filepfad.$this->_filename;			
		$tmp = file($_file);
		for($x=0; $x< count($tmp); $x++){ 
			$tmp = str_replace($_zeilenvorschub, "", $tmp); 
		}
		$tmp[] = $text;
		rsort($tmp);
		for($x=0; $x< count($tmp); $x++){ 
			if($x > $_max){
				unset($tmp[$x]);
			}
		}
		$neu =implode($_zeilenvorschub,$tmp);	
		$open = fopen($_file,"w+");
		fwrite ($open, $neu);
		fclose($open);		
	}
	
	function insert_user($text){
		$_zeilenvorschub = "\r\n";
		$_file = $this->_filepfad.$this->_filename;	
		$fp = fopen($_file,"a+");
		fputs($fp, $_zeilenvorschub);
		fputs($fp, $text);
		fclose($fp);	
/*		$_zeilenvorschub = "\r\n";
		$_file = $this->_filepfad.$this->_filename;		
		$tmp = file($_file);
		for($x=0; $x< count($tmp); $x++){ $tmp = str_replace($_zeilenvorschub, "", $tmp); }
		$tmp[] = $text;
		$neu =implode($_zeilenvorschub,$tmp);	
		$open = fopen($_file,"w+");
		fwrite ($open, $neu);
		fclose($open);*/
	}

	function add_user($_a){
		$_zeilenvorschub = "\r\n";
		if(!file_exists ("./Data/".$_a) || !is_dir("./Data/".$_a)){
			//echo " / Ordner noch nicht vorhanden";
			//echo "./Data/". $_a;
			mkdir ("./Data/". $_a);
			mkdir ("./Data/". $_a. "/Dokumente");
			mkdir ("./Data/". $_a. "/Rapport");
			mkdir ("./Data/". $_a. "/Timetable");
			copy("./Data/vorlage/absenz.txt","./Data/". $_a. "/absenz.txt");
			copy("./Data/vorlage/userdaten.txt","./Data/". $_a. "/userdaten.txt");
		}
		//Start-Datum auf den jetztigen Monat setzten
		//$_w_monat, $_w_jahr
		$_t_jahr = date("Y", time());
		$_t_monat = date("n", time());
		$_file = "./Data/".$_a."/userdaten.txt";
		$_tmp = mktime(0, 0, 0, $_t_monat, 1, $_t_jahr);
		$inhalt = file($_file);
		$inhalt[1] = $_tmp.$_zeilenvorschub;
		$fp = fopen($_file,"w+");
		foreach($inhalt as &$value){
			fputs($fp, $value);
		}
		fclose($fp);
	}
	function delete_user($id, $pfad){
		//echo "gelöscht wird : " . $id . " - " .  $pfad . "<br>";
		$_tmpusers= file("./Data/users.txt");
		unset($_tmpusers[$id]);
		$neu = implode( "", $_tmpusers);
		$open = fopen("./Data/users.txt","w+");
		fwrite ($open, $neu);
		fclose($open);
		rename ("./Data/". $pfad, "./Data/_del_".date("Y.n.d")."_". $pfad);  
		$_txt = "";	
		$_txt = $_txt.  "<br><br>User wurde etfernt und die Dateien verschoben nach ./Data/_del_".date("Y.n.d")."_". $pfad. "!";
		$_txt = $_txt.   "<br> Sichen Sie bitte das Verzeichniss und l&ouml;schen Sie es.";
		$_txt = $_txt.   "<br>Falls einmal ein gleicher Benutzer erstellt und dieser wieder gel&ouml;scht wird k&ouml;nnte es zu einer Fehlermeldung kommen.";
		return $_txt;
	}
	
	
}
?>