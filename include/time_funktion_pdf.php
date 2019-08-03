<?php
/*******************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
// ----------------------------------------------------------------------------
// PDF erstellen mit mehreren Stempelzeiten und Kommentaren auf gleicher Seite
// ----------------------------------------------------------------------------
function erstelle_neu($_drucktime)
{
	global $_user;
	global $_time;
	global $_settings;
	global $_absenz;
	global $_month;
	//Daten für die Anzeige
	$_sum = array();

	if($_drucktime){
		$tmp_jahr = date("Y", time());
		$tmp_monat= date("n", time()) - 1;
		if($tmp_monat == 0){
			$tmp_monat = 12;
			$tmp_jahr  = $tmp_jahr - 1;
		}
		// Falls der Mitarbeiter Drucken darf ist das nur der letzte Monat
		$_monat = new time_month( $_settings->_array[12][1], $_time->_letzterTag, $_user->_ordnerpfad, $tmp_jahr, $tmp_monat, $_user->_arbeitstage, $_user->_feiertage, $_user->_SollZeitProTag, $_user->_BeginnDerZeitrechnung,$_settings->_array[21][1],$_settings->_array[22][1], $_settings->_array[27][1], $_settings->_array[28][1]);
		$_jahr  = new time_jahr($_user->_ordnerpfad, 0, $_user->_BeginnDerZeitrechnung, $_user->_Stunden_uebertrag, $_user->_Ferienguthaben_uebertrag, $_user->_Ferien_pro_Jahr, $_user->_Vorholzeit_pro_Jahr, $_user->_modell, $_drucktime);
	}
	else
	{
		$_monat = new time_month( $_settings->_array[12][1], $_time->_letzterTag, $_user->_ordnerpfad, $_time->_jahr, $_time->_monat, $_user->_arbeitstage, $_user->_feiertage, $_user->_SollZeitProTag, $_user->_BeginnDerZeitrechnung,$_settings->_array[21][1],$_settings->_array[22][1], $_settings->_array[27][1], $_settings->_array[28][1]);
		$_jahr  = new time_jahr($_user->_ordnerpfad, 0, $_user->_BeginnDerZeitrechnung, $_user->_Stunden_uebertrag, $_user->_Ferienguthaben_uebertrag, $_user->_Ferien_pro_Jahr, $_user->_Vorholzeit_pro_Jahr, $_user->_modell,$_time->_timestamp);
	}


	// PDF erstellen
	$pdf                = new PDF();
	// Schrift auf Fett stellen (B)
	$pdf->SetFont('Arial','B',10);
	$pdf->SetDrawColor(150,150,150);
	$pdf->AddPage();
	$pdf->SetFillColor(220, 220, 220);

	// -------------------------------------------------------------------------
	// Mitarbeitername und Monat
	// -------------------------------------------------------------------------
	$pdf->Cell(11,6,'',0,'','L');
	$pdf->Cell(30,6,'Name : ',0,0,'L', '1');
	$pdf->Cell(59,6,$_user->_name,0,0,'L', '1');
	$pdf->Cell(30,6,'Monat : ',0,0,'L', '1');
	$_monatname         = iconv("UTF-8","ISO-8859-1",$_time->_monatname);
	$pdf->Cell(59,6,$_monatname." ".$_time->_jahr,0,0,'L', '1');
	$pdf->Ln();
	$pdf->Ln(1);
	// -------------------------------------------------------------------------
	// Monatsveränderungen - Berechnungen und anzeigen
	// -------------------------------------------------------------------------
	// Hintergrund weiss setzten und Schriftart Arial, normal, 10px
	$_ferien            = 0;
	$_absenzenvorhanden = 0;
	$_ftxt              = "";
	$_ftxtA             = "";
	$_ftxtE             = "";
	foreach($_monat->get_calc_absenz() as $werte){
		if($werte[1] == "F"){
			$_ferien = trim($werte[3]);
			if($_ferien == 1){
				$_ftxt = " Tag";
			}
			else
			{
				$_ftxt = " Tage";
			}
		}
		if($werte[3] > 0 & $_absenzenvorhanden == 0){
			$_absenzenvorhanden = 1;
		}
	}
	// $_ferienstart = $_jahr->_saldo_F + $_ferien;
	$_ferienstart = 0;
	$_ferienstart = round($_jahr->_saldo_F,2);
	$_ferienstart = $_ferienstart + round($_ferien,2);
	$_ferienstart = round($_ferienstart,2);

	if($_ferienstart == 1){
		$_ftxtA = " Tag";
	}
	elseif($_ferienstart > 1){
		$_ftxtA = " Tage";
	}
	if($_jahr->_saldo_F == 1){
		$_ftxtE = " Tag";
	}
	else
	{
		$_ftxtE = " Tage";
	}
	$_saldo_start = 0;
	$_sum['zeit']['monat_ende'] = $_jahr->_saldo_t;
	$_sum['zeit']['vorholzeit'] = round($_jahr->_Vorholzeit_pro_Jahr / 12,2);
	$ausz = new auszahlung($_time->_monat, $_time->_jahr);
	$wert = trim($ausz->get_auszahlung($_time->_monat, $_time->_jahr));
	$_sum['zeit']['auszahlung'] = $wert;
	$_sum['zeit']['summe'] = $_monat->_SummeSaldoProMonat;
	$_sum['ferien']['monat_ende'] = $_jahr->_saldo_F;
	$_sum['ferien']['bezug'] = $_ferien;
	if($_time->_jahr == time_user::get_user_startyear() && $_time->_monat == time_user::get_user_startmonth()){
		$_sum['zeit']['monat_start'] = 0;
		$_sum['zeit']['uebertrag'] = $_user->_Stunden_uebertrag;
		$_sum['ferien']['uebertrag'] = $_user->_Ferienguthaben_uebertrag;
	}
	else
	{
		$_sum['zeit']['monat_start'] =$_sum['zeit']['monat_ende'];
		$_sum['zeit']['monat_start'] += $_sum['zeit']['vorholzeit'] ;
		$_sum['zeit']['monat_start'] += $_sum['zeit']['auszahlung'] ;
		$_sum['zeit']['monat_start'] -= $_sum['zeit']['summe'];
		$_sum['zeit']['uebertrag'] = 0;
		$_sum['ferien']['uebertrag'] = 0;
	}
	$_sum['ferien']['monat_start'] = $_sum['ferien']['monat_ende'] - $_sum['ferien']['uebertrag'] + $_sum['ferien']['bezug'] ;

	$pdf->SetFont('Arial','',10);
	//$pdf->Line(21, 44, 199, 44);
	//$pdf->Line(21, 64, 199, 64);

	$pdf->SetFillColor(240, 240, 240);
	$pdf->Cell(11,6,'',0,'','L');
	$pdf->Cell(30,6,'Monatsanfang :',0,0,'L', '1');
	$pdf->Cell(30,6,'Zeit Saldo : ',0,0,'L', '1');
	$pdf->Cell(30,6,$_sum['zeit']['monat_start'].' Std.',0,0,'L', '1');
	$pdf->Cell(30,6,'Feriensaldo : ',0,0,'L', '1');
	$pdf->Cell(30,6,$_sum['ferien']['monat_start'] .$_ftxtA,0,0,'L', '1');
	$pdf->Cell(28,6,'',0,0,'L', '1');
	$pdf->Ln();

	if($_sum['zeit']['uebertrag'] <> 0 or $_sum['ferien']['uebertrag'] <> 0){
		$pdf->Cell(11,6,'',0,'','L');
		$txt = iconv("UTF-8","ISO-8859-1",'Übertrag');
		$pdf->Cell(30,6,$txt .' : ',0,0,'L');
		$pdf->Cell(30,6,'',0,0,'L');
		$pdf->Cell(30,6,$_sum['zeit']['uebertrag']  ." Std.",0,0,'L');
		$pdf->Cell(30,6,'',0,0,'L');
		$pdf->Cell(30,6,$_sum['ferien']['uebertrag'] . ' Tage',0,0,'L');
		$pdf->Cell(28,6,'',0,0,'L');
		$pdf->Ln();
	}

	$pdf->Cell(11,6,'',0,'','L');
	$txt = iconv("UTF-8","ISO-8859-1",'Veränderung');
	$pdf->Cell(30,6,$txt .' : ',0,0,'L');
	$pdf->Cell(30,6,'',0,0,'L');
	$pdf->Cell(30,6,$_sum['zeit']['summe'] ." Std.",0,0,'L');
	$pdf->Cell(30,6,'',0,0,'L');
	$pdf->Cell(30,6,'- '.$_sum['ferien']['bezug'].$_ftxt,0,0,'L');
	$pdf->Cell(28,6,'',0,0,'L');
	$pdf->Ln();

	if($_sum['zeit']['auszahlung'] <> 0 ){
		$pdf->Cell(11,6,'',0,'','L');
		$pdf->Cell(30,6,'Auszahlung : ',0,0,'L');
		$pdf->Cell(30,6,'',0,0,'L');
		$pdf->Cell(30,6,$_sum['zeit']['auszahlung'] ." Std.",0,0,'L');
		$pdf->Cell(30,6,'',0,0,'L');
		$pdf->Cell(30,6,'',0,0,'L');
		$pdf->Cell(28,6,'',0,0,'L');
		$pdf->Ln();
	}

	if($_sum['zeit']['vorholzeit'] <> 0 ){
		$pdf->Cell(11,6,'',0,'','L');
		$pdf->Cell(30,6,'Vorholzeit : ',0,0,'L');
		$pdf->Cell(30,6,'',0,0,'L');
		$pdf->Cell(30,6,$_sum['zeit']['vorholzeit']  ." Std.",0,0,'L');
		$pdf->Cell(30,6,'',0,0,'L');
		$pdf->Cell(30,6,'',0,0,'L');
		$pdf->Cell(28,6,'',0,0,'L');
		$pdf->Ln();
	}

	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(11,6,'',0,'','L');
	$pdf->Cell(30,6,'Monatsende :',0,0,'L', '1');
	$pdf->Cell(30,6,'',0,0,'L', '1');
	$pdf->Cell(30,6,$_sum['zeit']['monat_ende']." Std.",0,0,'L', '1');
	$pdf->Cell(30,6,'',0,0,'L', '1');
	$pdf->Cell(30,6,$_sum['ferien']['monat_ende']. $_ftxtE,0,0,'L', '1');
	$pdf->Cell(28,6,'',0,0,'L', '1');
	$pdf->Ln();
	$pdf->Ln(2);
	$pdf->SetFont('Arial','',10);

	// -------------------------------------------------------------------------
	// Monatstabelle
	// -------------------------------------------------------------------------
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillColor(200, 200, 200);
	$pdf->Cell(11,5,'',0,'','C');
	$pdf->Cell(18,5,"Datum",1,'','C', '1');
	//$pdf->Cell(7,5,"T",1,'','C', '1');
	$pdf->Cell(72,5,"Stempelzeiten",1,'','L', '1');
	$pdf->Cell(13,5,"Summe",1,'','C', '1');
	$pdf->Cell(13,5,"Saldo",1,'','C', '1');
	$pdf->Cell(14,5,"Abw.",1,'','L', '1');
	$pdf->Cell(48,5,"Bemerkung",1,'','L', '1');
	$pdf->Ln();
	$pdf->SetFont('Arial','',10);

	$i = 0;
	foreach($_monat->_MonatsArray as $zeile){
		if($i != 0){
			if($zeile[4] > 0 and $zeile[5] < 0){
				$pdf->SetFillColor(255, 255, 255);
			}
			else
			{
				$pdf->SetFillColor(220, 220, 220);
			}
			$pdf->Cell(11,5,'',0,'','C');
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(11,5,$zeile[1],1,'','C','1');
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(7,5,$zeile[3],1,'','C','1');

			$tmp   = "";
			$trenn = "";
			for($x = 0; $x < count($zeile[12]) and count($zeile[12]) > 0; $x++){
				// Trennzeichen bei Stempelzeiten als $trenn
				if($x == 0){
					$trenn = "";
				}
				elseif($x % 2 and $x <> 0){
					$trenn = " - ";
				}
				else
				{
					$trenn = " / ";
				}
				$tmp = $tmp . $trenn;
				$tmp = $tmp . $zeile[12][$x];
			}
			// Stempelzeiten
			$pdf->Cell(72,5,$tmp,1,'','L','1'); 	
			// Summe
			if($zeile[13] == 0) $zeile[13] = "";
			$pdf->Cell(13,5,$zeile[13],1,'','C','1');	
			// Saldo
			if($zeile[20] == 0 && $zeile[13] == 0)$zeile[20] = "";	
			$pdf->Cell(13,5,$zeile[20],1,'','C','1');
			// Abw.
			if ($zeile[14] == "0") $zeile[14] = "";
			$pdf->Cell(14,5,$zeile[14],1,'','L','1');
			$_txt = iconv("UTF-8", "ISO-8859-1", $zeile[34]);
			// Bemerkung
			$zeile[6] = str_replace("0", "", iconv("UTF-8", "ISO-8859-1", $zeile[6]));
			$zeile[16] = str_replace("0", "", iconv("UTF-8", "ISO-8859-1", $zeile[16]));
			if (($zeile[16] != "") && (strlen($zeile[34]) > 1))
				$pdf->MultiCell(48,5,$zeile[6].$zeile[16].";\r\n".$_txt,1,'','L','1');
			else
				$pdf->MultiCell(48,5,$zeile[6].$zeile[16].$_txt,1,'','L','1');
		}
		$i++;
	}

	// --------------------------------------------------------------------------
	// Summen in der Tabelle
	// --------------------------------------------------------------------------
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillColor(200, 200, 200);
	$pdf->Cell(11,5,'',0,'','C');
	$pdf->Cell(18,5,"",1,'','C', '1');
	$pdf->Cell(72,5,"Sollstunden :" .$_monat->_SummeSollProMonat . " Std.",1,'','L', '1');
	$pdf->Cell(13,5,$_monat->_SummeWorkProMonat,1,'','C', '1');
	$pdf->Cell(13,5,$_monat->_SummeSaldoProMonat,1,'','C', '1');
	$pdf->Cell(14,5,$_monat->_SummeAbsenzProMonat,1,'','C', '1');
	$pdf->Cell(48,5,"",1,'','C', '1');
	$pdf->SetFillColor(255, 255, 255);
	$pdf->Ln();
	$pdf->Ln(2);


	// -------------------------------------------------------------------------
	// Absenzen anzeigen wenn Einträge vorhanden sind
	// -------------------------------------------------------------------------
	if($_absenzenvorhanden){
		$pdf->SetFillColor(240, 240, 240);
		$pdf->Cell(11,6,'',0,'','L');
		$pdf->Cell(30,6,'Absenzen:',0,0,'L', '1');
		$pdf->Cell(60,6,"",0,0,'L', '1', '1');
		$pdf->Ln();
		$pdf->SetFont('Arial','',10);
		// -------------------------------------------------------------------------
		// Summen der Absenzen anzeigen (ab 0.87 erweiterbar pro Mitarbeiter)
		// -------------------------------------------------------------------------
		foreach($_monat->get_calc_absenz() as $werte){
			if($werte[3] <> 0){
				$pdf->Cell(11,6,'',0,'','L');
				$pdf->Cell(30,6,$werte[0]." : ",0,0,'L', '1');
				$pdf->Cell(60,6,$werte[3]. " Tage (" . $werte[1].")",0,0,'L', '1');
				$pdf->Ln();
			}
		}
		$pdf->Ln(2);
	}


	// --------------------------------------------------------------------------
	// Druckdatum und User anzeigen
	// --------------------------------------------------------------------------
	$pdf->SetFont('Arial','',7);
	$pdf->SetFillColor(255, 255, 255);

	$datum = 'Print : ';
	$datum .= date("j", time()). '.';
	$datum .= date("n", time()). '.';
	$datum .= date("Y", time());
	$datum .= ' / ';
	$datum .= date("H", time()) . ':';
	$datum .= date("i", time());
	$datum .= ' / von : ';
	$datum .= $_user->_name;

	$pdf->Cell(11,5,'',0,'','C');
	$pdf->Cell(178,5,$datum,0,'','L');
	$pdf->Ln();
	//$pdf->_checkoutput();
	$pdf->Output("./Data/".$_user->_ordnerpfad."/Dokumente/".date("Y.m", $_time->_timestamp).".pdf");
}



class PDF extends FPDF
{
	// --------------------------------------------------------------------------
	// Kopfzeile mit dem Logo
	// --------------------------------------------------------------------------
	function Header()
	{
		//Logo
		$this->Image('./images/smalltime.jpg',20,4,179);
		//Arial fett 15
		$this->SetFont('Arial','',10);
		//nach rechts gehen
		$this->Cell(80);
		//Titel
		//$this->Cell(30,10,'SMALL - TIME - Zeiterfassung',0,0,'C');
		//Zeilenumbruch
		$this->Ln(14);
	}
	// --------------------------------------------------------------------------
	// Fusszeile mit dem Copyright
	// --------------------------------------------------------------------------
	function Footer()
	{
		//Position 1,5 cm von unten
		$this->SetY( - 15);
		//Arial kursiv 8
		$this->SetFont('Arial','',6);
		//Seitenzahl
		//$this->Cell(0,10,'Seite '.$this->PageNo().' / {nb}',0,0,'C');
		$this->Cell(0,10,'SMALL - TIME - Zeiterfassung, IT-Master GmbH / www.small.li',0,0,'C');
	}
	//Simple table
	function BasicTable($header,$data)
	{
		//Header
		foreach($header as $col)                $this->Cell(40,7,$col,1);
		$this->Ln();
		//Data
		foreach($data as $row){
			foreach($row as $col)                    $this->Cell(40,6,$col,1);
			$this->Ln();
		}
	}
	//Better table
	function ImprovedTable($header,$data)
	{
		//Column widths
		$w = array(10,10,20,10,10,10);
		//Header
		for($i = 0;$i < count($header);$i++)                $this->Cell($w[$i],7,$header[$i],1,0,'C');
		$this->Ln();
		//Data
		foreach($data as $row){
			$this->Cell($w[0],6,$row[0],'LR');
			$this->Cell($w[1],6,$row[1],'LR');
			$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
			$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
			$this->Cell($w[4],6,number_format($row[4]),'LR',0,'R');
			$this->Cell($w[5],6,number_format($row[5]),'LR',0,'R');
			$this->Ln();
		}
		//Closure line
		$this->Cell(array_sum($w),0,'','T');
	}
	//Colored table
	function FancyTable($header,$data)
	{
		//Colors, line width and bold font
		$this->SetFillColor(255,0,0);
		$this->SetTextColor(255);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		//Header
		$w = array(40,35,40,45);
		for($i = 0;$i < count($header);$i++)                $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
		$this->Ln();
		//Color and font restoration
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		//Data
		$fill = 0;
		foreach($data as $row){
			$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
			$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
			$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
			$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
			$this->Ln();
			$fill =!$fill;
		}
		$this->Cell(array_sum($w),0,'','T');
	}
}