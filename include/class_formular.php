<?php
/*******************************************************************************
* Small Time Formulargenerator
/*******************************************************************************
* Version 0.9
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
class Formular{
	// Die einzelnen Felder werden in der Variable $inputs gespeichert
	public $inputs = array();
	public $action;
	public $method;
	public $result;
	function __construct($action, $method){
		$this->action 		= $action;
		$this->method 	= $method;
	}
	// Formular erstellen
	function get(){	
		$result = '<form method='.$this->method.' action='.$this->action.'>';
		$result .= '<table>';
		foreach($this->inputs as $what) {
			$result .= '<tr>';
				$result .= '<td>';
				$result .= $what[0];
				$result .= '</td>';
				$result .= '<td>';
				$result .= $what[1];
				$result .= '</td>';
			$result .= '</tr>';
		}
		$result .= '</table>';
		$result .= '<form>';
		return $result;
	}
	
}
class inputfeld{
	public $beschreibung;
	public $type;
	public $name;
	public $value;
	public $size;
	
	function __construct($beschreibung, $type, $name, $value, $size){
		$this->beschreibung	= $beschreibung;
		$this->type			= $type;
		$this->name			= $name;
		$this->value			= $value;
		$this->size			= $size;
	}
	
	function get(){
		return  array($this->beschreibung, '<input type="'. $this->type . '" name="' . $this->name . '" value="' . $this->value . '"" size="' . $this->size . '">');
	}
}

class checkbox{
	public $beschreibung;
	public $type;
	public $name;
	public $value;
	public $size;
	
	function __construct($beschreibung, $type, $name, $value, $size){
		$this->beschreibung	= $beschreibung;
		$this->type			= $type;
		$this->name			= $name;
		$this->value			= $value;
		$this->size			= $size;
	}
	
	function get(){
		return  array($this->beschreibung, '<input type="'. $this->type . '" name="' . $this->name . '" value="' . $this->value . '"" size="' . $this->size . '">');
	}
}

class Formular_old{
	// Die einzelnen Felder werden in der Variable $inputs gespeichert
	public $inputs = array();
	public $action;
	public $method;
	public $result;
	
	function Formular_old($action, $method) {
		$this->action = $action;
		$this->method = $method;
	}
	
	function AddInput($beschreibung="", $type="", $editor, $name="", $value="", $size=null) {
		// TODO : ckeditor fehlt, ev. Probleme mit sonderzeichen, darum entfernt
		#if($editor == true){
		#	$editor = new CKEditor();
		#	$editor->basePath = 'ckeditor/';
		#	$editor->returnOutput = true;
		#	$this->inputs[] = array($beschreibung, $editor->editor($name, $value)); // Einfügen des ckeditors
		#} else {
			$this->inputs[] = array($beschreibung, "<input type=\"$type\" name=\"$name\" value=\"$value\" size=$size />");
		#}
	}
	function getInputs() {
		while(list($name, $value) = each($_POST)) {
			$this->result[$name] = $value;
		}
		while(list($name, $value) = each($_GET)) {
			$this->result[$name] = $value;
		}
	}
	function DeleteInput($index){
		$result = array();
		unset($this->inputs[$index]);
		foreach($this->inputs as $what)
			$result[] = $what;
		$this->inputs = $result;
	}
	public function setaction($act) {
			 $this->action = $act;
	}
	 public function getaction() {
			 return $this->action;
	}
	public function setmethod($method) {
			 $this->method = $method;
	}
	
	 public function getmethod() {
			 return $this->method;
	}
	// Formular ausgeben
	function ausgeben($toReturn=false)
	{	
		$result = '<form method="'.$this->method.'" action="'.$this->action.'">';
		$result .= "<table>";
		foreach($this->inputs as $what) {
			$result .= "<tr>";
			if(is_array($what)) {
				foreach($what as $thing) {
					$result .= "<td>";
					$result .= $thing;
					$result .= "</td>";
				}
			} else {
				$result .= "<td style=\"width:100%\">";
				$result .= $what;
				$result .= "</td>";
				}
			$result .= "</tr>";
		}
		$result .= "</table>";
		$result .= "<form>";
		if($toReturn)
			return $result;
		else 
			echo $result;
	}
}