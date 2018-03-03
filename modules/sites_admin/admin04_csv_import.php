<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.020
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
//-------------------------------------------------------------------------------------------
//CSV funktioniert nur im folgenden format
//-------------------------------------------------------------------------------------------
//echo "CSV - Upload von IPHON - APP < br > ";
echo "<center>";
echo "";
echo "
<table width=50% border=0>
<tr>
<td><b>
<ol>
<li>File upload</li>
<li>Importtabelle wird berechnet</li>
<li>Mitarbeiter ausw&auml;hlen</li>
</ol>
</b></td>
</tr>
</table>
Format der CSV - Datei: Trennzeichen entweder ; oder Tabulatoren<br>
<table width=50% border=1>
<tr>
<td>Datum</td>
<td>Zeit 1</td>
<td>Zeit 1</td>
</tr>
<tr>
<td>01.03.2012</td>
<td>07:00</td>
<td>11:15</td>
</tr>
</table>";
echo "<br>";
echo "<hr color=red>";
//-------------------------------------------------------------------------------------------
//File importieren und speichern unter ./import / import.csv falls ein neues ausgewählt wurde
//-------------------------------------------------------------------------------------------
$_file   = @$_FILES['uploadedfile']['tmp_name'];
$_submit = @$_POST['submit'];
if($_file && $_submit)
{
	move_uploaded_file($_FILES['uploadedfile']['tmp_name'], "import/import.csv");
	echo "File : './import/import.csv' wurde auf den Server geladen.<br><br>";
	echo "<hr color=red>";
}
//-------------------------------------------------------------------------------------------
//File in ein array laden, falls es existiert
//-------------------------------------------------------------------------------------------
$_file = "./import/import.csv";
if(file_exists($_file))
{
	$_csvdat = file($_file);
}
//-------------------------------------------------------------------------------------------
//durchsuchen und wenn kein Datum mit Punkt in der ersten Spalte - dann unset der Zeile
//-------------------------------------------------------------------------------------------
$x = 0;
if(file_exists($_file))
{
	foreach($_csvdat as $_zeile)
	{
		$_zeile = explode("\t",$_zeile);
		if(!strrpos($_zeile[0],"."))
		{
			unset($_csvdat[$x]);
		}
		$x++;
	}
}
//-------------------------------------------------------------------------------------------
//Daten anzeigen lassen wenn $_show = 1
//-------------------------------------------------------------------------------------------
if(file_exists($_file))
{
	$_show = 0;
	if($_show)
	{
		$x = 0;
		foreach($_csvdat as $_zeile)
		{
			$_zeile = explode("\t",$_zeile);
			echo "Zeile : " . $x. " / ";
			foreach($_zeile as $_spalte)
			{
				echo $_spalte;
				echo " | ";
			}
			echo "<br>";
			$x++;
		}
		echo "<hr color=red>";
	}
}
//-------------------------------------------------------------------------------------------
//Daten anzeigen lassen und berechnen der timestamp
//-------------------------------------------------------------------------------------------
if(file_exists($_file))
{
	$_timelist = array();
	$_show = 1;
	if($_show)
	{
		echo "<table bgcolor=white border=0 width=100% cellpadding=3 cellspacing=1>";
		$x = 0;
		foreach($_csvdat as $_zeile)
		{
			echo '<tr>';
			if(strpos($_zeile,";"))
			{
				$_zeile = explode(";",$_zeile);
			}
			else
			{
				$_zeile = explode("\t",$_zeile);
			}
			echo '<td class="td_background_info" widht=20>';
			echo "ID:" . $x. "";
			echo "</td>";
			for($z = 0; $z < 3;$z++)
			{
				echo '<td class="td_background_tag" widht=20>';
				echo $_zeile[$z];
				echo "</td>";
			}

			echo '<td class="td_background_info" widht=20>';
			echo "Convert:";
			echo "</td>";
			//-------------------------------------------------------------------------------------------
			//Datum konvertieren:
			$tmp         = explode(".",$_zeile[0]);
			$_w_jahr_t   = convertint($tmp[2]);
			$_w_monat_t  = convertint($tmp[1]);
			$_w_tag_t    = convertint($tmp[0]);
			//-------------------------------------------------------------------------------------------
			//Zeit 1 konvertieren
			$tmp         = explode(":",$_zeile[1]);
			$_w_stunde_1 = convertint($tmp[0]);
			$_w_min_1    = convertint($tmp[1]);
			$time1       = mktime($_w_stunde_1, $_w_min_1, 0, $_w_monat_t, $_w_tag_t, $_w_jahr_t);
			$_timelist[] = $time1;
			//-------------------------------------------------------------------------------------------
			//Zeit 1 konvertieren
			$tmp         = explode(":",$_zeile[2]);
			$_w_stunde_2 = convertint($tmp[0]);
			$_w_min_2    = convertint($tmp[1]);
			$time2       = mktime($_w_stunde_2, $_w_min_2, 0, $_w_monat_t, $_w_tag_t, $_w_jahr_t);
			$_timelist[] = $time2;
			//-------------------------------------------------------------------------------------------
			//timestamp 1
			echo "<td class='td_background_tag' widht=20> timestamp : ";
			echo $time1;
			echo '</td>';
			//-------------------------------------------------------------------------------------------
			//timestamp 2
			echo "<td class='td_background_tag' widht=20> timestamp : ";
			echo $time2;
			echo '</td>';
			$x++;
			echo '</tr>';
		}
		echo "</table>";
		echo "<hr color=red>";
	}
}
//Convert String to int
function convertint($_string)
{
	for($z = 0; $z <= 255; $z++)
	{
		if(!$z >= 48 && !$z <= 57)
		{
			$_string = str_replace(chr($z), '', $_string);
		}
	}
	return (int)$_string;
}
?>
<form enctype="multipart/form-data" action="?action=import" method="POST">
	<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
	CSV - File: <input name="uploadedfile" type="file" /> <input type="submit" name="submit" value="Upload File" />
</form>
<?php
echo "<hr color=red>";
//wenn User gewählt, importieren
if(@$_POST['importieren'])
{
	echo "Mitarbeiter ausgew&auml;hlt, ich importiere....... <hr>";
	echo "Mitarbeiter : ".$_POST['name']. "<br>";
	$x    = $_POST['name'];
	$user = $_users->_array[$x];
	echo "Mitarbeiter : $user[1] / Pfad : $user[0] / Passwort : $user[2]<br>";
	foreach($_timelist as $time)
	{
		$_time->save_timestamp($time,$user[0]);
	}
	echo "Import erfolgreich<br>";
	echo "L&ouml;sche Datei<br>";
	$_file = "./import/import.csv";
	if(file_exists($_file))
	{
		unlink($_file);
		echo "Datei delete<br>";
	}
	else
	{
		echo "No Datei!<br>";
	}

}
else
{
	echo "Bei welchem Benutzer sollten die Datem importiert werden:<br>";
	echo '<form action="?action=import" method="POST">';
	echo '<select name="name" size="1">';
	$x = 1;
	for($x = 1; $x < count($_users->_array); $x++)
	{
		echo '<option value="'.$x.'">'.$_users->_array[$x][1]. '</option>';
	}
	echo '</select>';
	echo '';
	echo '<input type="submit" name="importieren" value="importieren">';
	echo '</form>';
}
echo "</center>";
?>
