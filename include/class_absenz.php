<?php
/*******************************************************************************
* Absenzen - Klasse
/*******************************************************************************
* Version 0.9.126
* Author:  IT-Master
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master, All rights reserved
*******************************************************************************/
class time_absenz
{
* Version 0.9.205
	public $_filetext = array();
	public $_calc = array();
	private $ordnerpfad = NULL;
	private function parse_absenz_line($line)
	{
		$line = rtrim((string)$line, "\r\n");
		$parts = explode(";", $line);
		$parts = array_pad($parts, 3, '');

		return array($parts[0], $parts[1], $parts[2]);
	}
	private function is_empty_absenz_line($line)
	{
		return trim($line[0]) == '' && trim($line[1]) == '' && trim($line[2]) == '';
	}
	private function is_after_user_end($ordnerpfad, $_timestamp)
	{
		$_endtime = time_user::get_user_endtime_by_path($ordnerpfad);
		return time_user::is_after_endtime($_timestamp, $_endtime);
	}
	function __construct($ordnerpfad, $jahr)
	{
		$this->ordnerpfad = $ordnerpfad;
		$_file = "./Data/" . $this->ordnerpfad . "/Timetable/A" . $jahr;
		$_absenzfile = "./Data/" . $this->ordnerpfad . "/absenz.txt";
		if (file_exists($_absenzfile)) {
			$this->_filetext = file($_absenzfile);
		}
		if (file_exists($_file)) {
			$this->_array = file($_file);
			$i = 0;
			foreach ($this->_array as $string) {
				$string = $this->parse_absenz_line($string);

				foreach ($this->_filetext as $_zeile) {
					$_zeile = $this->parse_absenz_line($_zeile);
					if ($this->is_empty_absenz_line($_zeile)) {
						continue;
					}
					if (trim($string[1]) != '' && trim($string[1]) == trim($_zeile[1])) {
						$string[3] = trim($_zeile[0]);
						if (trim($string[2]) == '' || floatval($string[2]) == 0)
							$string[2] = 1;
						$string[4] = trim($_zeile[2]);
					}
				}
				$this->_array[$i] = $string;
				$i++;
			}
		}
		$this->calc();
	}
	function calc()
	{
		if (!$this->_calc) {
			$o = 0;
			foreach ($this->_filetext as $_zeile) {
				$_zeile = str_replace("ä", "ae", $_zeile);
				$_zeile = str_replace("ö", "oe", $_zeile);
				$_zeile = str_replace("ü", "ue", $_zeile);
				$_zeile = $this->parse_absenz_line($_zeile);
				if ($this->is_empty_absenz_line($_zeile)) {
					continue;
				}
				$this->_calc[$o] = array($_zeile[0], $_zeile[1], $_zeile[2], 0);
				$o++;
			}
		}
	}
	function get_absenztext()
	{
		return $this->_filetext;
	}
	function insert_absenz($ordnerpfad, $_w_jahr)
	{
		$_zeilenvorschub = "\r\n";
		$_file = "./Data/" . $ordnerpfad . "/Timetable/A" . $_w_jahr;
		if (!file_exists($_file)) {
			$_meldung = "Keine Daten vorhanden, folgende Datei wurde versucht zu &ouml;ffnen " . $_file;
		} else {
			$_abwesenheit = file($_file);
		}
		$_timestamp = $_GET['timestamp'];
		if ($this->is_after_user_end($ordnerpfad, $_timestamp)) {
			return false;
		}
		$_grund = $_POST['_grund'];
		$_anzahl = $_POST['_anzahl'];
		$fp = fopen($_file, "a+");
		fputs($fp, $_timestamp . ";" . $_grund . ";" . $_anzahl . $_zeilenvorschub);
		fclose($fp);
	}
	function delete_absenz($ordnerpfad, $_w_jahr)
	{
		$_timestamp = $_GET['timestamp'];
		if ($this->is_after_user_end($ordnerpfad, $_timestamp)) {
			return false;
		}
		$_file = "./Data/" . $ordnerpfad . "/Timetable/A" . $_w_jahr;
		$_absenzliste = file($_file);
		$i = 0;
		$neu = "";
		foreach ($_absenzliste as $string) {
			$string = explode(";", $string);
			if ($string[0] == $_timestamp) {
				unset($_absenzliste[$i]);
			}
			$i++;
		}
		if (is_array($_absenzliste)) {
			$neu = implode("", $_absenzliste);
		}
		$open = fopen($_file, "w+");
		fwrite($open, $neu);
		fclose($open);
	}
	public function __destruct()
	{
	}
}