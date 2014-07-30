<?php
/*******************************************************************************
* Small Time
/*******************************************************************************
* Version 0.87
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
// ----------------------------------------------------------------------------
// PDF erstellen mit merhreren Stempelzeiten und Kommentaren auf gleicher Seite
// ----------------------------------------------------------------------------
function erstelle_neu($_drucktime){
	global $_user;
	global $_time;
	global $_settings;
	global $_absenz;
	global $_month;
	
	if ($_drucktime){
		$tmp_jahr = date("Y", time());
		$tmp_monat = date("n", time())-1;
		if($tmp_monat ==0){ 
			$tmp_monat  = 12;
			$tmp_jahr = $tmp_jahr-1;
		}
		//echo "". $_drucktime . "<hr>";
		//echo "<hr>Monat :" . $tmp_monat . "/". $tmp_jahr . "<hr>"; 
		// Falls der Mitarbeiter Drucken darf ist das nur der letzte Monat
		$_monat 	= new time_month( $_settings->_array[12][1] , $_time->_letzterTag, $_user->_ordnerpfad, $tmp_jahr, $tmp_monat, $_user->_arbeitstage, $_user->_feiertage, $_user->_SollZeitProTag, $_user->_BeginnDerZeitrechnung,$_settings->_array[21][1],$_settings->_array[22][1], $_settings->_array[27][1]);
		$_jahr = new time_jahr($_user->_ordnerpfad, 0, $_user->_BeginnDerZeitrechnung, $_user->_Stunden_uebertrag, $_user->_Ferienguthaben_uebertrag, $_user->_Ferien_pro_Jahr, $_user->_Vorholzeit_pro_Jahr, $_user->_modell, $_drucktime);
		//echo "Nur der letzte Monat ".$tmp_monat;
	}else{
		//echo "". $_time->_timestamp . "<hr>";
		$_monat 	= new time_month( $_settings->_array[12][1] , $_time->_letzterTag, $_user->_ordnerpfad, $_time->_jahr, $_time->_monat, $_user->_arbeitstage, $_user->_feiertage, $_user->_SollZeitProTag, $_user->_BeginnDerZeitrechnung,$_settings->_array[21][1],$_settings->_array[22][1], $_settings->_array[27][1]);
		$_jahr = new time_jahr($_user->_ordnerpfad, 0, $_user->_BeginnDerZeitrechnung, $_user->_Stunden_uebertrag, $_user->_Ferienguthaben_uebertrag, $_user->_Ferien_pro_Jahr, $_user->_Vorholzeit_pro_Jahr, $_user->_modell,$_time->_timestamp);
		//echo "jeder Monat";
	}
	
	//global $_jahr;
		
	
	$pdf=new PDF();
	$pdf->SetFont('Arial','B',10);
	$pdf->AddPage();
	$pdf->SetDrawColor(150,150,150);

	$pdf->Cell(10,6,'',0,'','L');
	$pdf->Cell(40,6,'Name : ',0,0,'L');
	$pdf->Cell(60,6,$_user->_name,0,0,'L');
	$pdf->Cell(40,6,'Monat : ',0,0,'L');
	$pdf->Cell(60,6,$_time->_monatname." ".$_time->_jahr,0,0,'L');
	$pdf->Ln();

	$pdf->Cell(10,6,'',0,'','L');
	$pdf->Cell(40,6,'Saldo Total: ',0,0,'L');
	$pdf->Cell(60,6,$_jahr->_saldo_t." Std.",0,0,'L');
	$pdf->Cell(40,6,'Ferienguthaben : ',0,0,'L');
	$pdf->Cell(60,6,$_jahr->_saldo_F." Tage",0,0,'L');
	$pdf->Ln();
	$pdf->Ln();

	$pdf->Cell(10,6,'',0,'','L');
	$pdf->Cell(40,6,'Saldo : ',0,0,'L');
	$pdf->Cell(60,6,$_monat->_SummeSaldoProMonat." Std.",0,0,'L');
	$pdf->Cell(40,6,'Sollstunden : ',0,0,'L');
	$pdf->Cell(60,6,$_monat->_SummeSollProMonat." Std.",0,0,'L');
	$pdf->Ln();

	$pdf->Cell(10,6,'',0,'','L');
	$pdf->Cell(40,6,'Gearbeitet : ',0,0,'L');
	$pdf->Cell(60,6,$_monat->_SummeWorkProMonat." Std.",0,0,'L');
	$pdf->Ln();

	$pdf->Line(20, 60, 200, 60);
	//-------------------------------------------------------------------------
	// Summen der Absenzen anzeigen (ab 0.87 erweiterbar pro Mitarbeiter)
	//-------------------------------------------------------------------------	
	foreach ($_monat->get_calc_absenz() as $werte){
		if($werte[3]<>0){	
			$pdf->Cell(10,6,'',0,'','L');
			$pdf->Cell(40,6,$werte[0]." : ",0,0,'L');
			$pdf->Cell(60,6,$werte[3]. " Tage (" . $werte[1].")",0,0,'L');
			$pdf->Ln();	
		}
	}
	$pdf->Ln();

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
	
	//$pdf->ImprovedTable($header,$_drucktable);
	$i=0;
	foreach($_monat->_MonatsArray as $zeile){
		if($i!=0){
			if($zeile[4]>0 and $zeile[5]<0){
				$pdf->SetFillColor(255, 255, 255);
			}else{
				$pdf->SetFillColor(220, 220, 220);
			}
			$pdf->Cell(11,5,'',0,'','C');
			$pdf->Cell(11,5,$zeile[1],1,'','C','1');
			$pdf->Cell(7,5,$zeile[3],1,'','C','1');
			$tmp="";
			$trenn="";
			for($x=0; $x< count($zeile[12]) and count($zeile[12])>0; $x++){
				// Trennzeichen bei Stempelzeiten als $trenn
				if($x==0){$trenn = "";}elseif($x%2 and $x<>0){$trenn = " - ";}else{$trenn = " / ";}
				$tmp = $tmp . $trenn;
				$tmp = $tmp . $zeile[12][$x];	
			}
			$pdf->Cell(72,5,$tmp,1,'','L','1'); //stempelzeiten in einer Schleife....
			if($zeile[13]==0)$zeile[13]="";
			$pdf->Cell(13,5,$zeile[13],1,'','C','1');
			if($zeile[20]==0 && $zeile[13]==0)$zeile[20]="";
			$pdf->Cell(13,5,$zeile[20],1,'','C','1');
			$pdf->Cell(14,5,$zeile[14],1,'','L','1');
			$pdf->MultiCell(48,5,$zeile[6].$zeile[16].$zeile[34],1,'','L','1');
			//$pdf->Ln();
		}
		$i++;
	}
	
	/*
	//$pdf->ImprovedTable($header,$_drucktable);
	foreach($_drucktable as $zeile){
	if($zeile[14]){
	$pdf->SetFillColor(255, 255, 255);
	}else{
	$pdf->SetFillColor(220, 220, 220);
	}
	$pdf->Cell(11,5,'',0,'','C');
	$pdf->Cell(11,5,$zeile[0],1,'','C','1');
	$pdf->Cell(7,5,$zeile[1],1,'','C','1');
	$pdf->Cell(72,5,$zeile[2],1,'','L','1');
	$pdf->Cell(13,5,$zeile[3],1,'','C','1');
	$pdf->Cell(13,5,$zeile[4],1,'','C','1');
	$pdf->Cell(14,5,$zeile[5],1,'','L','1');
	$pdf->MultiCell(48,5,$zeile[24].$zeile[16],1,'','L','1');
	//$pdf->Ln();
	}
	*/
	//echo "Schreibe :".date("Y.m", $_time->_timestamp).".pdf";
	$pdf->Output("./Data/".$_user->_ordnerpfad."/Dokumente/".date("Y.m", $_time->_timestamp).".pdf");
	
	
}
// ----------------------------------------------------------------------------
// PDF erstellen mit kurzen Stempelzeiten und Kommentaren auf der gleicher Seite - alte Version
// ----------------------------------------------------------------------------
function erstelle_pdf_small($_drucktable){
	global $_Userpfad;
	$_file = "./Data/".$_Userpfad."Dokumente/";
	global $_timestamp;
	$_drucktable;
	unset($_drucktable[0]);
	global $_monate;
	global $_userdaten;
	global $_SummeSaldoProMonat;
	global $_SummeFerienTotal;
	global $_SummeAbsenzenProMonat;
	global $_abwesenheit;
	global $_SummeSollProMonat;

	//-----------------------------------------------------------------------------
	//Jahres - Summen: / vor den Settings - Anzeigen zu berechnen
	//-----------------------------------------------------------------------------
	//echo "<hr>";
	//seit wann sollten Daten vorhanden sein?
	$_startjahr = date("Y",$_userdaten[1]);

	//wenn Startmonat nicht Januar, dann Ferien für das erste Jahr berechnen.
	$ferienguthabenerstesJahr =0;
	if(trim(date("n",$_userdaten[1]))<>"1"){
		$ferienguthabenerstesJahr = $_userdaten[5] / 12 * (13-date("n",$_userdaten[1]));
		//$_meldung2 =  trim(date("n",$_userdaten[1]))."nihct ganzes jahr";
	}else{
		//$_meldung2 =  trim(date("n",$_userdaten[1]))."nganzes jahr";
		$ferienguthabenerstesJahr = $_userdaten[5];
	}
	$ferienguthabenerstesJahr = round($ferienguthabenerstesJahr,2);
	//$_meldung2 = $_meldung2 . "<hr>" . $ferienguthabenerstesJahr;

	$_endejahr = date("Y", time());
	$_SummeFerienTotal = 0;
	$_SummeZeitTotal =0;

	$_uebertrag = explode(";", $_userdaten[6]);
	$_Stundenguthaben_uebertrag = $_uebertrag[0];
	$_Ferienguthaben_uebertrag = $_uebertrag[1];

	for($_DY = $_startjahr; $_DY<=$_endejahr; $_DY++ ){
		$_file = "./Data/".$_Userpfad."Timetable/" . $_DY;
		$_year_data = file($_file);
		$i=1;
		$_SummeFerienProJahr = 0;
		$_SummeZeitProJahr = 0;
		foreach($_year_data as $_tmp){
			$_tmp = explode(";", $_tmp);
			//echo $_monate [$i]. " = ". $_tmp[0]. " / Ferien = ". $_tmp[1]. "<br>";
			$_SummeFerienProJahr = $_SummeFerienProJahr + $_tmp[1];
			$_SummeZeitProJahr = $_SummeZeitProJahr + $_tmp[0];
			$i++;
		}
		if($_DY == $_startjahr){
			$ferienguthabenerstesJahr = $ferienguthabenerstesJahr + $_Ferienguthaben_uebertrag;
			$_SummeFerienTotal =  $_SummeFerienTotal + $ferienguthabenerstesJahr - $_SummeFerienProJahr;
		}else{
			$_SummeFerienTotal =  $_SummeFerienTotal + $_userdaten[5] - $_SummeFerienProJahr;
		}
		$_SummeFerienTotal= round($_SummeFerienTotal,2);
		//$_SummeFerienTotal =  $_SummeFerienTotal - $_userdaten[4] Vorholzeit pro Jahr -$_SummeFerienProJahr;
		$_SummeZeitTotal = $_SummeZeitTotal - $_userdaten[4] + $_SummeZeitProJahr;
	}
	//-----------------------------------------------------------------------------

	//Arial fett 15
	//$this->SetFont('Arial','B',12);
	//nach rechts gehen
	//$this->Cell(80);
	//Titel
	//$this->Cell(30,10,'Zeiterfassung',0,0,'C');
	//Zeilenumbruch
	//$this->Ln(20);
	//-----------------------------------------------------------------------------
	//erste Seite mit den Stempelzeiten
	//-----------------------------------------------------------------------------
	$pdf=new PDF();
	$pdf->SetFont('Arial','B',10);
	$pdf->AddPage();
	//$pdf->Image('smalltime.jpg',20);

	//$pdf->SetDrawColor(0, 0, 0);                // Linienfarbe auf Schwarz einstellen
	//$pdf->SetLineWidth(0.2);                        // Linienbreite einstellen, 0.5 mm
	//$pdf->Line(20, 20, 200, 20);                 // Linie zeichnen (x1, y1, x2,y2)
	//SetDrawColor(0, 0, 0);
	$pdf->SetDrawColor(150,150,150);

	$pdf->Cell(10,6,'',0,'','L');
	$pdf->Cell(40,6,'Name : ',0,0,'L');
	$pdf->Cell(60,6,$_userdaten[0],0,0,'L');
	$pdf->Cell(40,6,'Monat : ',0,0,'L');
	$pdf->Cell(60,6,$_monate[date("n", $_timestamp)] ." ".date("Y", $_timestamp),0,0,'L');
	$pdf->Ln();

	$pdf->Cell(10,6,'',0,'','L');
	$pdf->Cell(40,6,'Saldo Total: ',0,0,'L');
	$pdf->Cell(60,6,$_SummeZeitTotal." Std.",0,0,'L');
	$pdf->Cell(40,6,'Ferienguthaben : ',0,0,'L');
	$pdf->Cell(60,6,$_SummeFerienTotal." Tage",0,0,'L');
	$pdf->Ln();
	$pdf->Ln();

	$pdf->Cell(10,6,'',0,'','L');
	$pdf->Cell(40,6,'Saldo : ',0,0,'L');
	$pdf->Cell(60,6,$_SummeSaldoProMonat." Std.",0,0,'L');
	$pdf->Cell(40,6,'Sollstunden : ',0,0,'L');
	$pdf->Cell(60,6,$_SummeSollProMonat." Std.",0,0,'L');
	$pdf->Ln();

	$pdf->Cell(10,6,'',0,'','L');
	$pdf->Cell(40,6,'Gearbeitet : ',0,0,'L');
	$pdf->Cell(60,6,$_SummeSollProMonat+$_SummeSaldoProMonat." Std.",0,0,'L');
	$pdf->Ln();

	$pdf->Line(20, 60, 200, 60);

	$i=0;
	foreach($_abwesenheit as $_tmp){
		//if (!$_SummeAbsenzenProMonat[$i][2] == 0){
		$pdf->Cell(10,6,'',0,'','L');
		$pdf->Cell(40,6,$_tmp[0]." : ",0,0,'L');
		$pdf->Cell(60,6,$_SummeAbsenzenProMonat[$i][2]. " Tage (" . $_tmp[1].")",0,0,'L');
		$pdf->Ln();
		//}
		$i++;
	}
	//$pdf->Line(20, 20, 200, 20);
	$pdf->Ln();
	$pdf->Ln();
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

	//$pdf->ImprovedTable($header,$_drucktable);
	foreach($_drucktable as $zeile){
		if($zeile[14]){
			$pdf->SetFillColor(255, 255, 255);
		}else{
			$pdf->SetFillColor(220, 220, 220);
		}
		$pdf->Cell(11,5,'',0,'','C');
		$pdf->Cell(11,5,$zeile[0],1,'','C','1');
		$pdf->Cell(7,5,$zeile[1],1,'','C','1');
		$pdf->Cell(72,5,$zeile[2],1,'','L','1');
		$pdf->Cell(13,5,$zeile[3],1,'','C','1');
		$pdf->Cell(13,5,$zeile[4],1,'','C','1');
		$pdf->Cell(14,5,$zeile[5],1,'','L','1');
		$pdf->MultiCell(48,5,$zeile[24].$zeile[16],1,'','L','1');
		//$pdf->Ln();
		
		//echo "<hr>".$zeile[34];
	}

	$pdf->Output("./Data/".$_Userpfad."Dokumente/".date("Y.m", $_timestamp).".pdf");
}
// ----------------------------------------------------------------------------
// PDF erstellen mit kurzen Stempelzeiten und Kommentaren auf der neuer Seite - alte Version
// ----------------------------------------------------------------------------
function erstelle_pdf_more($_drucktable){
	global $_Userpfad;
	$_file = "./Data/".$_Userpfad."Dokumente/";
	global $_timestamp;
	$_drucktable;
	unset($_drucktable[0]);
	global $_monate;
	global $_userdaten;
	global $_SummeSaldoProMonat;
	global $_SummeFerienTotal;
	global $_SummeAbsenzenProMonat;
	global $_abwesenheit;
	global $_SummeSollProMonat;

	//-----------------------------------------------------------------------------
	//Jahres - Summen: / vor den Settings - Anzeigen zu berechnen
	//-----------------------------------------------------------------------------
	//echo "<hr>";
	//seit wann sollten Daten vorhanden sein?
	$_startjahr = date("Y",$_userdaten[1]);

	//wenn Startmonat nicht Januar, dann Ferien für das erste Jahr berechnen.
	$ferienguthabenerstesJahr =0;
	if(trim(date("n",$_userdaten[1]))<>"1"){
		$ferienguthabenerstesJahr = $_userdaten[5] / 12 * (13-date("n",$_userdaten[1]));
		//$_meldung2 =  trim(date("n",$_userdaten[1]))."nihct ganzes jahr";
	}else{
		//$_meldung2 =  trim(date("n",$_userdaten[1]))."nganzes jahr";
		$ferienguthabenerstesJahr = $_userdaten[5];
	}
	$ferienguthabenerstesJahr = round($ferienguthabenerstesJahr,2);
	//$_meldung2 = $_meldung2 . "<hr>" . $ferienguthabenerstesJahr;

	$_uebertrag = explode(";", $_userdaten[6]);
	$_Stundenguthaben_uebertrag = $_uebertrag[0];
	$_Ferienguthaben_uebertrag = $_uebertrag[1];

	$_endejahr = date("Y", time());
	$_SummeFerienTotal = 0;
	$_SummeZeitTotal =0;
	for($_DY = $_startjahr; $_DY<=$_endejahr; $_DY++ ){
		$_file = "./Data/".$_Userpfad."Timetable/" . $_DY;
		$_year_data = file($_file);
		$i=1;
		$_SummeFerienProJahr = 0;
		$_SummeZeitProJahr = 0;
		foreach($_year_data as $_tmp){
			$_tmp = explode(";", $_tmp);
			//echo $_monate [$i]. " = ". $_tmp[0]. " / Ferien = ". $_tmp[1]. "<br>";
			$_SummeFerienProJahr = $_SummeFerienProJahr + $_tmp[1];
			$_SummeZeitProJahr = $_SummeZeitProJahr + $_tmp[0];
			$i++;
		}
		if($_DY == $_startjahr){
			$ferienguthabenerstesJahr = $ferienguthabenerstesJahr + $_Ferienguthaben_uebertrag;
			$_SummeFerienTotal =  $_SummeFerienTotal + $ferienguthabenerstesJahr - $_SummeFerienProJahr;
		}else{
			$_SummeFerienTotal =  $_SummeFerienTotal + $_userdaten[5] - $_SummeFerienProJahr;
		}
		//$_SummeFerienTotal= round($_SummeFerienTotal,2);
		//$_SummeFerienTotal =  $_SummeFerienTotal - $_userdaten[4] Vorholzeit pro Jahr -$_SummeFerienProJahr;
		$_SummeZeitTotal = $_SummeZeitTotal - $_userdaten[4] + $_SummeZeitProJahr;
	}
	//-----------------------------------------------------------------------------

	//Arial fett 15
	//$this->SetFont('Arial','B',12);
	//nach rechts gehen
	//$this->Cell(80);
	//Titel
	//$this->Cell(30,10,'Zeiterfassung',0,0,'C');
	//Zeilenumbruch
	//$this->Ln(20);
	//-----------------------------------------------------------------------------
	//erste Seite mit den Stempelzeiten
	//-----------------------------------------------------------------------------
	$pdf=new PDF();
	$pdf->SetFont('Arial','B',10);
	$pdf->AddPage();
	//$pdf->Image('smalltime.jpg',20);

	//$pdf->SetDrawColor(0, 0, 0);                // Linienfarbe auf Schwarz einstellen
	//$pdf->SetLineWidth(0.2);                        // Linienbreite einstellen, 0.5 mm
	//$pdf->Line(20, 20, 200, 20);                 // Linie zeichnen (x1, y1, x2,y2)
	$pdf->SetDrawColor(150,150,150);

	$pdf->Cell(10,6,'',0,'','L');
	$pdf->Cell(40,6,'Name : ',0,0,'L');
	$pdf->Cell(60,6,$_userdaten[0],0,0,'L');
	$pdf->Cell(40,6,'Monat : ',0,0,'L');
	$pdf->Cell(60,6,$_monate[date("n", $_timestamp)] ." ".date("Y", $_timestamp),0,0,'L');
	$pdf->Ln();

	$pdf->Cell(10,6,'',0,'','L');
	$pdf->Cell(40,6,'Saldo Total: ',0,0,'L');
	$pdf->Cell(60,6,$_SummeZeitTotal." Std.",0,0,'L');
	$pdf->Cell(40,6,'Ferienguthaben : ',0,0,'L');
	$pdf->Cell(60,6,$_SummeFerienTotal." Tage",0,0,'L');
	$pdf->Ln();
	$pdf->Ln();

	$pdf->Cell(10,6,'',0,'','L');
	$pdf->Cell(40,6,'Saldo : ',0,0,'L');
	$pdf->Cell(60,6,$_SummeSaldoProMonat." Std.",0,0,'L');
	$pdf->Cell(40,6,'Sollstunden : ',0,0,'L');
	$pdf->Cell(60,6,$_SummeSollProMonat." Std.",0,0,'L');
	$pdf->Ln();

	$pdf->Cell(10,6,'',0,'','L');
	$pdf->Cell(40,6,'Gearbeitet : ',0,0,'L');
	$pdf->Cell(60,6,$_SummeSollProMonat+$_SummeSaldoProMonat." Std.",0,0,'L');
	$pdf->Ln();

	$pdf->Line(20, 60, 200, 60);

	$i=0;
	foreach($_abwesenheit as $_tmp){
		//if (!$_SummeAbsenzenProMonat[$i][2] == 0){
		$pdf->Cell(10,6,'',0,'','L');
		$pdf->Cell(40,6,$_tmp[0]." : ",0,0,'L');
		$pdf->Cell(60,6,$_SummeAbsenzenProMonat[$i][2]. " Tage (" . $_tmp[1].")",0,0,'L');
		$pdf->Ln();
		//}
		$i++;
	}
	//$pdf->Line(20, 20, 200, 20);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFillColor(200, 200, 200);
	$pdf->Cell(11,5,'',0,'','C');
	$pdf->Cell(13,5,"D",1,'','C', '1');
	$pdf->Cell(10,5,"T",1,'','C', '1');
	$pdf->Cell(109,5,"Stempelzeiten",1,'','L', '1');
	$pdf->Cell(13,5,"Summe",1,'','C', '1');
	$pdf->Cell(13,5,"Saldo",1,'','C', '1');
	$pdf->Cell(20,5,"Abw.",1,'','L', '1');
	$pdf->Ln();

	//$pdf->ImprovedTable($header,$_drucktable);
	foreach($_drucktable as $zeile){
		if($zeile[14]){
			$pdf->SetFillColor(255, 255, 255);
		}else{
			$pdf->SetFillColor(220, 220, 220);
		}
		$pdf->Cell(11,5,'',0,'','C');
		$pdf->Cell(13,5,$zeile[0],1,'','C', '1');
		$pdf->Cell(10,5,$zeile[1],1,'','C', '1');
		$pdf->Cell(109,5,$zeile[2],1,'','L', '1');
		$pdf->Cell(13,5,$zeile[3],1,'','C', '1');
		$pdf->Cell(13,5,$zeile[4],1,'','C', '1');
		$pdf->Cell(20,5,$zeile[5],1,'','L', '1');
		$pdf->Ln();
	}
	//-----------------------------------------------------------------------------
	//zweite Seite mit den Arbeitsrapporten
	//-----------------------------------------------------------------------------
	$pdf->AddPage();
	$pdf->Ln();
	$pdf->SetFillColor(200, 200, 200);
	$pdf->Cell(11,5,'',0,'','C');
	$pdf->Cell(13,5,"D",1,'','C', '1');
	$pdf->Cell(10,5,"T",1,'','C', '1');
	$pdf->Cell(155,5,"Bemerkung",1,'','L', '1');
	$pdf->Ln();
	$pdf->SetFillColor(255, 255, 255);
	//$pdf->ImprovedTable($header,$_drucktable);
	foreach($_drucktable as $zeile){
		if($zeile[14]){
			$pdf->SetFillColor(255, 255, 255);
		}else{
			$pdf->SetFillColor(220, 220, 220);
		}
		$pdf->Cell(11,5,'',0,'','C');
		$pdf->Cell(13,5,$zeile[0],1,'','C', '1');
		$pdf->Cell(10,5,$zeile[1],1,'','C', '1');
		//$pdf->WriteHTML($zeile[24]);
		//$pdf->WriteHTML("sdgr");
		$pdf->MultiCell(155,5,$zeile[24].$zeile[16],1,'','L');
		/*
		$zeile[24] = explode("\n", $zeile[24]);
		$i=0;
		foreach ($zeile[24] as $_spalte){
		if($i>0){
		$pdf->Cell(11,5,'',0,'','C');
		$pdf->Cell(13,5,'',0,'','C');
		$pdf->Cell(10,5,'',0,'','C');
		}
		$pdf->MultiCell(155,5,$zeile[24][$i],1,'','L');
		$pdf->Ln();
		$i++;
		} */
		//$pdf->Ln();
	}
	$pdf->Output("./Data/".$_Userpfad."Dokumente/".date("Y.m", $_timestamp).".pdf");
}

class PDF extends FPDF{
	//Kopfzeile
	function Header(){
		//Logo
		$this->Image('./images/smalltime.jpg',20,4,179);
		//Arial fett 15
		$this->SetFont('Arial','B',10);
		//nach rechts gehen
		$this->Cell(80);
		//Titel
		//$this->Cell(30,10,'SMALL - TIME - Zeiterfassung',0,0,'C');
		//Zeilenumbruch
		$this->Ln(20);
	}
	//Fusszeile
	function Footer(){
		//Position 1,5 cm von unten
		$this->SetY(-15);
		//Arial kursiv 8
		$this->SetFont('Arial','I',7);
		//Seitenzahl
		//$this->Cell(0,10,'Seite '.$this->PageNo().'/{nb}',0,0,'C');
		$this->Cell(0,10,'SMALL - TIME - Zeiterfassung, IT-Master GmbH / www.small.li',0,0,'C');
	}
	//Simple table
	function BasicTable($header,$data){
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
	function ImprovedTable($header,$data){
		//Column widths
		$w=array(10,10,20,10,10,10);
		//Header
		for($i=0;$i<count($header);$i++)                $this->Cell($w[$i],7,$header[$i],1,0,'C');
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
	function FancyTable($header,$data){
		//Colors, line width and bold font
		$this->SetFillColor(255,0,0);
		$this->SetTextColor(255);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		//Header
		$w=array(40,35,40,45);
		for($i=0;$i<count($header);$i++)                $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
		$this->Ln();
		//Color and font restoration
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		//Data
		$fill=0;
		foreach($data as $row){
			$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
			$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
			$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
			$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
			$this->Ln();
			$fill=!$fill;
		}
		$this->Cell(array_sum($w),0,'','T');
	}

}
?>