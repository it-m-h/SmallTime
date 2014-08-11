<?php
/*******************************************************************************
* Small Time allgemeine Funktionen
/*******************************************************************************
* Version 0.872
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
// ----------------------------------------------------------------------------
// InfoText bei Admin - Content / String und CSS - Class
// ----------------------------------------------------------------------------
function getinfotext($str,$css){
	$_infotext = "<table width=100% border=0 cellpadding=5 cellspacing=1><tr><td colspan=9 class='".$css."' width=100 align=left>";
	$_infotext .= $str;
	$_infotext .= "</td></tr></table>";	
	return $_infotext;
}

// ----------------------------------------------------------------------------
// Berechtigung, für Userzugriff auf PDF
// ----------------------------------------------------------------------------
function check_htaccess_pdf($datenpfad){
	$_file = "./Data/".$datenpfad."/Dokumente/.htaccess";
	if (!file_exists($_file)){
		$_zeilenvorschub = "\r\n";
        $fp = fopen($_file,"a+");
        fputs($fp, "Order deny,allow");
        fputs($fp, $_zeilenvorschub);
		fputs($fp, "Allow from all");
        fputs($fp, $_zeilenvorschub);
		fputs($fp, "Allow from <127.0.0.1>");
        fputs($fp, $_zeilenvorschub);
        fclose($fp);
		$_datum = date("d.m.Y",time());
		$_uhrzeit = date("H:i",time());
		$_datetime =  $_datum." - ".$_uhrzeit;
		$_debug 	= new time_filehandle("./debug/","time.txt",";");
		$_debug->insert_line("Time;" . $_datetime . ";Fehler in time_funktion_pdf;193;" .$datenpfad.";htaccess nicht vorhanden, wurde erstellt.");
	}
}

// ----------------------------------------------------------------------------
// Meldungen in die Datei schreiben
// ----------------------------------------------------------------------------

function check_htaccess($_file,$_rwo,$_text){
	//$_file = "./Data/".$datenpfad."/Dokumente/.htaccess";
	if (!file_exists($_file)){
		$_zeilenvorschub = "\r\n";
        $fp = fopen($_file,"a+");
		if($_rwo){
	        fputs($fp, "Order deny,allow");
        	fputs($fp, $_zeilenvorschub);
			fputs($fp, "Allow from all");
        	fputs($fp, $_zeilenvorschub);
			fputs($fp, "Allow from <127.0.0.1>");
        	fputs($fp, $_zeilenvorschub);		
		}else{
		    fputs($fp, "Deny from all");	
		}
        fclose($fp);
		$_datum = date("d.m.Y",time());
		$_uhrzeit = date("H:i",time());
		$_datetime =  $_datum." - ".$_uhrzeit;
		$_debug 	= new time_filehandle("./debug/","time.txt",";");
		$_debug->insert_line("Time;".$_datetime.";Fehler in;".$_file.";".$_text);
	}
}

// ----------------------------------------------------------------------------
// Stunden oder Dezimal anzeigen
// ----------------------------------------------------------------------------

function dec2time($number, $format = "%h:%i") {
  	$h = floor($number);
    $i = ($number - $h) * 60;
	$i = number_format($i, 0, '.', '');
    $format = preg_replace("/%h/", $h, $format);
    $format = preg_replace("/%i/", $i, $format);
    return $format;
}

// ----------------------------------------------------------------------------
// Funktionen für Debug
// ----------------------------------------------------------------------------

function txt($txt){
        echo "<p style='color:red'>$txt</p>";
}

function showController($c){
	$cVars = get_class_vars(get_class($c));
	foreach($cVars as $cname => $cvalue) {
		echo "<font color=red size=+3>NAME: '<b>" . $cname . "</b>'</font>";
		showClassVar( $c->$cname);
		echo "<hr>";	
	}
}
function showClassVar($class) {
  echo "<table>";
  echo "<tr><td colspan=3>";
  echo '<strong>KLASSE: "'.get_class($class).'"</strong></td><tr>';
  $aVars = get_class_vars(get_class($class));
  foreach($aVars as $name => $value) {
    echo "<tr><td>";
        echo "[$name]";
        echo "</td><td> : </td><td>";

        if(is_array ($class->$name)){
                echo "<p style='color:blue'>";
                print_r($class->$name);
                echo "</p></td></tr>";
        }else{
                echo "<p style='color:blue'>" . $class->$name . "</p></td></tr>";
        }

  }
  echo '<tr><td colspan=3><strong>'.get_class($class).' - Methoden:</strong></td><tr>';
  $aMethods = get_class_methods(get_class($class));
  sort($aMethods);
  foreach($aMethods as $name => $value) {
        echo "<tr><td>";
        echo "[$name]";
        echo "</td><td> : </td><td>";
        echo "<p style='color:blue'>" . $value ."</p></td></tr>";
  }
  echo '</p>';
  echo "</table>";
}


function monatskalender($tmp){
    global $_color ;
    $_ausgabe = "";
    $monat=date('n');
    $monat = $monat + $tmp;
    $jahr=date('Y');
    if ($tmp ==0 ){$disermonat=1;}else {$disermonat=0;}
    if ($monat >= 13) {$monat = $monat-12; $jahr = $jahr+1;}
    if ($monat <= 0) {$monat = $monat+12; $jahr = $jahr-1;}

    $erster=date('w', mktime(0,0,0,$monat,1,$jahr));
    $insgesamt=date('t', mktime(0,0,0,$monat,1,$jahr));
    $heute=date('d');
    $monate=array('Januar','Februar','M&auml;rz','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember');
    if($erster==0){$erster=7;}
    $_ausgabe = $_ausgabe.'<table width=100% border=0 cellpadding=3 cellspacing=1 class=td_background_wochenende>';
    $_ausgabe = $_ausgabe. '<tr><td colspan=7 align=center class=td_background_feiertag >'. $monate[$monat-1].' '.$jahr.'</td></tr>';
    $_ausgabe = $_ausgabe. '<tr>';
    $_ausgabe = $_ausgabe. '<td class=td_background_wochenende>Mo</td>';
    $_ausgabe = $_ausgabe. '<td class=td_background_wochenende>Di</td>';
    $_ausgabe = $_ausgabe. '<td class=td_background_wochenende>Mi</td>';
    $_ausgabe = $_ausgabe. '<td class=td_background_wochenende>Do</td>';
    $_ausgabe = $_ausgabe. '<td class=td_background_wochenende>Fr</td>';
    $_ausgabe = $_ausgabe. '<td class=td_background_wochenende>Sa</td>';
    $_ausgabe = $_ausgabe. '<td class=td_background_wochenende>So</td></tr>';
    $_ausgabe = $_ausgabe. "<tr>\n";
    $i=1;
    while($i<$erster){$_ausgabe = $_ausgabe. '<td>&nbsp;</td>'; $i++;}
    $i=1;
    while($i<=$insgesamt)
    {
            $rest=($i+$erster-1)%7;
            if ($rest==6 || $rest==0){
                    $_ausgabe = $_ausgabe. '<td class=td_background_wochenende align=center>';
            } elseif ($i==$heute && $disermonat){
                    $_ausgabe = $_ausgabe. '<td class=td_background_heute align=center>';
            } else  {
                    $_ausgabe = $_ausgabe. '<td class=td_background_tag align=center>';
            }
            $_ausgabe = $_ausgabe. $i;
            $_ausgabe = $_ausgabe. "</td>\n";
            if($rest==0){$_ausgabe = $_ausgabe. '</tr><tr>';}
            $i++;
    }
    $_ausgabe = $_ausgabe. '</tr>';
    $_ausgabe = $_ausgabe. '</table>';
        return $_ausgabe;
}
?>