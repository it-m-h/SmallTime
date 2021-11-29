<?php
/*******************************************************************************
* Gruppen - Klasse
/*******************************************************************************
* Version 0.9.123
* Author:  IT-Master
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master, All rights reserved
*******************************************************************************/
class time_group
{
	private $_filename = "./Data/group.txt";
	public 	$_array = NULL;
	function __construct($_grpwahl)
	{
		if($_grpwahl >= 0)
		{
			$_groups = new time_filehandle("./Data/","group.txt",";" );
			$_users  = new time_filehandle("./Data/","users.txt",";" );
			for($x = 1; $x < count($_groups->_array); $x++)
			{
				$tmpgrp = explode(",",$_groups->_array[$x][2]);
				$y      = 0;
				foreach($tmpgrp as $ma)
				{
					// Gruppenbezeichnung
					$this->_array[0][$x][] = $_groups->_array[$x][1];
					// Mitarbeiter ID
					$this->_array[1][$x][] = $ma;
					// Mitarbeiter Ordnerpfad
					$this->_array[2][$x][] = $_users->_array[$ma][0];
					// Mitarbeiter Kurzzeichen
					$this->_array[3][$x][] = $_users->_array[$ma][1];
					// Mitarbeiter Name
					$this->_array[4][$x][] = $this->get_userdata($_users->_array[$ma][0]);
					// Mitarbeiter Stempelzeiten
					$this->_array[5][$x][] = $this->get_timestamps($_users->_array[$ma][0]);
					$u = 0;
					// Anzahl Stempelzeiten
					$this->_array[6][$x][$y] = count($this->_array[5][$x][$y]);
					// Passwort
					$this->_array[7][$x][$y] = $_users->_array[$ma][2];
					$y++;
				}
			}
		}
	}
	function __destruct()
	{
	}
	function get_userdata($_ordnerpfad)
	{
		$tmp = "";
		$file= "./Data/". $_ordnerpfad. "/userdaten.txt";
		if(file_exists($file))	$tmp = file($file);
		return $tmp[0];
	}
	function get_usergroup($userid)
	{
		$_groups = file($this->_filename);
		$x       = 0;
		foreach($_groups as $_group)
		{
			$_group = explode(";", $_group);
			$_group[2] = explode(",", $_group[2]);
			foreach($_group[2] as $users)
			{
				if($users == $userid and $x > 0)return $x;
			}
			$x++;
		}
	}
	function get_timestamps($_ordnerpfad)
	{
		$_w_jahr = date("Y", time());
		$_w_monat= date("n", time());
		$_file   = "./Data/".$_ordnerpfad."/Timetable/".$_w_jahr.".".$_w_monat;
		if(file_exists($_file))
		{
			$_timeTable = file($_file);
			sort($_timeTable);
		}
		else
		{
			$_timeTable = NULL;
		}
		// Anzeige der heutigen Stempelzeiten (nur heute $temptime)
		$_temptime = array();
		$_str = "";
		if(is_array($_timeTable) && count($_timeTable))
		{
			foreach($_timeTable as $_time)
			{
				//Datenüberprüfung und Bereinigung
				$_time = trim($_time);
				$_time = str_replace("\r", "", $_time);
				$_time = str_replace("\n", "", $_time);
				if($_time)
				{
					//Stempelzeit berechnen
					$_w_jahr     = date("Y", time());
					$_w_monat    = date("n", time());
					$_w_tag      = date("j", time());
					$_w_stunde   = date("H", time());
					$_w_minute   = date("i", time());
					$_w_sekunde  = date("s", time());

					$_w_jahr_t   = date("Y", $_time);
					$_w_monat_t  = date("n", $_time);
					$_w_tag_t    = date("j", $_time);
					$_w_stunde_t = date("H", $_time);
					$_w_minute_t = date("i", $_time);
					$_w_sekunde_t= date("s", $_time);

					if($_w_jahr == $_w_jahr_t && $_w_monat == $_w_monat_t && $_w_tag == $_w_tag_t)
					{
						$_temptime[] = $_w_stunde_t . ":" . $_w_minute_t;
					}
				}
				else
				{
					$_datum   = date("d.m.Y",time());
					$_uhrzeit = date("H:i",time());
					$_datetime= $_datum." - ".$_uhrzeit;
					$_debug   = new time_filehandle("./debug/","time.txt",";");
					$_debug->insert_line("Time;" . $_datetime . ";Fehler in class_group;141;" .$_file.";Leerzeile entdeckt");
				}
			}
		}
		return $_temptime;
	}
	function get_groups()
	{
		//--------------------------------------
		//Absenzen: Gruppen in ein Array laden
		//--------------------------------------
		if(file_exists($this->_filename))
		{
			return file($this->_filename);
		}
		else
		{
			return false;
		}
	}
	function del_group($id)
	{
		$_temp = file($this->_filename);
		unset($_temp[$id]);
		foreach($_temp as $_zeile)
		{
			$_new[] = $_zeile;
		}
		$_anzahl = count($_new);
		for($i = 0; $i < $_anzahl;$i++)
		{
			$_zeile = explode(";",$_new[$i]);
			$_zeile[0] = $i + 1;
			$_new[$i] = implode(";",$_zeile);
		}
		$neu = implode( "", $_new);
		$open= fopen($this->_filename,"w+");
		fwrite ($open, $neu);
		fclose($open);
	}
	function save_group()
	{
		$_zeilenvorschub = "\r\n";
		$_anzahl         = $_POST['anzahl'];
		$fp              = fopen($this->_filename,"w+");
		for($x = 0; $x <= $_anzahl; $x++)
		{
			$_temp_e = @$_POST['e'.$x];
			$_temp_v = @$_POST['v'.$x];
			$_temp_u = @$_POST['u'.$x];
			if($_temp_v <> "" && $_temp_e <> "")
			{
				fputs($fp, $_temp_e.";".$_temp_v.";".$_temp_u.$_zeilenvorschub);
			}
		}
		fclose($fp);
	}
}