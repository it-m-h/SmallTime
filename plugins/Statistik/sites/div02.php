<?php
/*******************************************************************************
* Small Time - Plugin : Statistik der Mitarbeiter (Überzeit, Ferien usw.)
/*******************************************************************************
* Version 0.899
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
?>
<!--Anfang DIV für die InfoBoxMonat -->
<div id="InfoBoxMonat" style="z-index: 1; visibility: hidden; left: 0px; top: 0px;">
	<div id="BoxInnenMonat">
		<span id="BoxInhalteMonat">
		</span>
	</div>
</div>
<!--Ende DIV für die InfoBox 	-->
<style type="text/css">
	#InfoBoxMonat
	{
		visibility: hidden;
		position: absolute;
		top: 0px;
		left: 0px;
		z-index: 1;
		width: 650px;
		background-color: #d0dbf4;
		border: 1px solid #555555;
		-moz-box-shadow: 1px 1px 1px 0px #606060;
		-webkit-box-shadow: 1px 1px 1px 0px #606060;
		box-shadow: 1px 1px 1px 0px #606060;
	}
	#BoxInnenMonat
	{
		padding: 10px;
	}

	#BoxInhalteMonat
	{
		text-decoration: none;
		color: #333333;
		font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		font-weight: normal;
		font-size: 12px;
		line-height: 130%;
	}
</style>
<script type="text/javascript">
	function DetailsMouseover(e,Inhalte)
	{
		console.log( "Details onmouseover" );
		offsetX = -280;
		offsetY = 30;
		if (offsetX)
		{
			offsetx=offsetX;
		} else
		{
			offsetx=0;
		}
		if (offsetY)
		{
			offsety=offsetY;
		} else
		{
			offsety=0;
		}
		var PositionX = 0;
		var PositionY = 0;
		if (!e) var e = window.event;
		if (e.pageX || e.pageY)
		{
			PositionX = e.pageX;
			PositionY = e.pageY;
		}
		else if (e.clientX || e.clientY)
		{
			PositionX = e.clientX + document.body.scrollLeft;
			PositionY = e.clientY + document.body.scrollTop;
		}
		document.getElementById("BoxInhalteMonat").innerHTML = Inhalte;
		document.getElementById('InfoBoxMonat').style.left = (PositionX+offsetx)+"px";
		document.getElementById('InfoBoxMonat').style.top = (PositionY+offsety)+"px";
		document.getElementById('InfoBoxMonat').style.visibility = "visible";
	}

	function DetailsMouseout()
	{
		console.log( "Details onmouseout" );
		document.getElementById('InfoBoxMonat').style.visibility = "hidden";
	}
</script>
<?php
$uz = 0;
for($z = 1; $z < count($_users->_array ) ; $z++)
{
	$_file        		= "./Data/" .$_users->_array[$z][0] . "/Timetable/" .$wahljahr;
	
	$_file_absenz 	= "./Data/".$_users->_array[$z][0]."/Timetable/A" . $wahljahr;
	$_file_abstxt 		= "./Data/".$_users->_array[$z][0]."/absenz.txt";
	$abstxt       		= array();
	if(file_exists($_file_abstxt))
	{
		$tmparr = file($_file_abstxt);
		foreach($tmparr as $zeile)
		{
			$spalte = explode(";", $zeile);
			$abstxt[] = $spalte[1];
		}
	}
	//-------------------------------------------------------------------------------------------
	// Anzahl der Absenzen für die Anzahl der Spaltenberechnung
	//-------------------------------------------------------------------------------------------
	$AnzahlAbsenzen = count($tmparr);
	$_data[$uz][0][0] = $wahljahr;
	$_data[$uz][0][1] = "Soll";
	$_data[$uz][0][2] = "Work";
	$_data[$uz][0][3] = "Saldo";
	for($c = 4;($c - 4) < count($abstxt);$c++)
	{
		$_data[$uz][0][$c] = $abstxt[($c - 4)];
	}
	for($a = 1; $a <= 13; $a++)
	{
		for($b = 0; $b <= 10; $b++)
		{
			$_data[$uz][$a][$b] = "";
		}
	}
	//Absenzen laden
	if(file_exists($_file_absenz))
	{
		$tmparr = file($_file_absenz);
		$arrabs = NULL;
		$u = 0;
		
		foreach($tmparr as $zeile)
		{
			$werte = explode(";", $zeile);
			$arrabs[$u][0] = $werte[0];
			$arrabs[$u][1] = $werte[1];
			$arrabs[$u][2] = $werte[2];
			$monat = date("n", $werte[0]);
			$arrabs[$u][3] = $monat;
			for($c = 0;$c < count($abstxt);$c++)
			{
				if($werte[1] == $abstxt[$c]) $_data[$uz][$monat][($c + 4)] += $werte[2];
			}
			$u++;
		}
	}
	//Saldo laden
	if(file_exists($_file))
	{
		$tmparr = file($_file);
		for($i = 1; $i <= 12; $i++)
		{
			$werte = explode(";", $tmparr[($i - 1)]);
			$_data[$uz][$i][1] = $werte[2];	// Soll
			$_data[$uz][$i][2] = $werte[3];	// Work
			$_data[$uz][$i][3] = $werte[0];	// Saldo
			$_data[$uz][$i][4] = $werte[1];	// Ferien
		}
	}
	$anz = count($_data[$uz][13]);
	for($a = 1; $a <= 12; $a++)
	{
		for($b = 0; $b <= $anz; $b++)
		{
			$_data[$uz][13][$b] += $_data[$uz][$a][$b];
		}
	}
	$uz++;
}

$html = "";
$html .= "<table width=100% hight=100% border=0 cellpadding=3 cellspacing=1>";
$html .= "<tr>";
$html .= "<td class=td_background_info width=30px>";
$html .= $wahljahr;
$html .= "</td>";
//-------------------------------------------------------------------------------------------
// rote Spaltenüberschriften / Jahr / Soll / Work / Saldo / Absenzen
//-------------------------------------------------------------------------------------------
for($i = 1; $i < ($AnzahlAbsenzen + 4); $i++)
{
	$html .= "<td width=40 align=middle class=td_background_info>";
	$html .= "" .$_data[0][0][$i] . "";
	$html .= "</td>";
}
$html .= "</tr>";

for($a = 0; $a < count($_data); $a++)
{
	$text = "";
	$text = view_jahr($a);
	$html .= "<tr>";
	$html .= "<td class=td_background_top width=30px>";
	$html .= '<a href="#" onmouseover="DetailsMouseover(event, \'' . $text . '\');" onmouseout="DetailsMouseout();">';
	$html .= "<img border='0' src='images/icons/page_go.png'></img>";
	$html .= '</a>';
	$html .= "</td>";
	//-------------------------------------------------------------------------------------------
	// Inhalte / der Tabelle
	//-------------------------------------------------------------------------------------------

	for($i = 1; $i <= ($AnzahlAbsenzen + 3); $i++)
	{
		$html .= "<td width=40 align=middle class=td_background_tag>";
		if($_data[$a][13][$i] <> 0)
		{
			$wert = trim($_data[$a][13][$i]);		
			if($wert < 0){
				$wert = "<font class=minus>".$wert."</font>";
			}
			$html .= $wert;
		}
		else
		{
			$html .= "";
			if($i==1 || $i==2){
				$html .= '<img title="Jahres&uuml;bersicht des Mitarbeiters &ouml;ffnen, damit die Werte berechnet werden. (neue Spalten ab Ver. 0.896) Diese Info erscheint auch, wenn noch keine Stempelzeiten vorhanden sind." src="images/icons/information.png" border="0">';
			}
		}
		$html .= "</td>";
	}
	$html .= "</tr>";
}
$html .= "</table>";
echo $html;

function view_jahr($a)
{
	global $_data;
	global $wahljahr;
	global $AnzahlAbsenzen;
	$anz = count($_data[$a][13]);
	$html= "";
	$html .= "<table width=100% hight=100% border=0 cellpadding=3 cellspacing=1>";
	$html .= "<tr>";
	$html .= "<td class=td_background_info width=30px>";
	$html .= $wahljahr;
	$html .= "</td>";
	//-------------------------------------------------------------------------------------------
	// rote Spaltenüberschriften / Jahr / Soll / Work / Saldo / Absenzen
	//-------------------------------------------------------------------------------------------
	for($i = 1; $i < ($AnzahlAbsenzen + 4); $i++)
	{
		$html .= "<td width=40 align=middle class=td_background_info>";
		$html .= "" .$_data[$a][0][$i] . "";
		$html .= "</td>";
	}
	$html .= "</tr>";
	for($m = 1; $m <= 12;$m++)
	{

		$html .= "<tr>";
		$html .= "<td class=td_background_wochenende>";
		$html .= "<table width=100% hight=100% border=0 cellpadding=2 cellspacing=0><tr><td width=18 valign=middle>";
		$html .= "<img src=images/icons/calendar_view_month.png border=0>";
		$html .= "</td><td align=left>";
		$html .= $m;
		$html .= "</td></tr></table>";
		$html .= "</td>";
		//-------------------------------------------------------------------------------------------
		// Inhalte / der Tabelle
		//-------------------------------------------------------------------------------------------
		for($mi = 1; $mi <= ($AnzahlAbsenzen + 3); $mi++)
		{
			$html .= "<td width=40 align=middle class=td_background_tag> ";
			if($_data[$a][$m][$mi] <> NULL)
			{
				$wert = trim($_data[$a][$m][$mi]);
				if($wert < 0)
				{
					$wert = "<font class=minus>".$wert."</font>";
				}
				$html .= $wert;
			}
			else
			{
				$html .= "";
			}
			$html .= "</td>";
		}
		$html .= "</tr>";
	}
	$html .= "<tr>";
	//-------------------------------------------------------------------------------------------
	// Summen in der Jahresansicht des MA unten
	//-------------------------------------------------------------------------------------------
	$html .= "<td class=td_background_info>Summe:</td>";
	for($mi = 1; $mi <= ($AnzahlAbsenzen + 3); $mi++)
	{
		$html .= "<td width=40 align=middle class=td_background_info> ";
		if($_data[$a][13][$mi] <> 0)
		{
			$wert = trim($_data[$a][13][$mi]);
			if($wert < 0)
			{
				$wert = "<font class=minus>".$wert."</font>";
			}
			$html .= $wert;
		}
		else
		{
			$html .= "";
		}
		$html .= "</td>";
	}
	$html .= "</tr>";
	$html .= "</table>";
	return $html;
}
?>

