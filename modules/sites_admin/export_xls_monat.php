<?php
/********************************************************************************
* Small Time - Excel - Monatsübersicht
/*******************************************************************************
* Version 0.896
* Author: IT-Master
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master, All rights reserved
*******************************************************************************/
$stempelzeiten = 6; // minimale initialisation
for($z=1; $z< count($_monat->_MonatsArray); $z++){
	if (count($_monat->_MonatsArray[$z][12]) > $stempelzeiten) {
		$stempelzeiten = count($_monat->_MonatsArray[$z][12]);
	}
}

function excel_escape_xml($value) {
	$value = str_replace(array("\r\n", "\r", "\n"), " ", (string)$value);
	return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

$rows = array();

$rows[] = array('User', $_user->_name);
$rows[] = array('Monat', $_time->_monatname.' '.$_time->_jahr);
$rows[] = array();

$header = array('Datum', 'Tag');
for ($x = 1; $x <= $stempelzeiten; $x++) {
	$header[] = 'Stempel '.$x;
}
$header[] = 'Gearbeitet';
$header[] = 'Saldo';
$header[] = 'Absenz';
$header[] = 'Rapport';
$rows[] = $header;

for($z = 1; $z < count($_monat->_MonatsArray); $z++){
	$row = array();
	$row[] = $_monat->_MonatsArray[$z][1];
	$row[] = $_monat->_MonatsArray[$z][3];

	for ($x = 0; $x < $stempelzeiten; $x++) {
		$row[] = (isset($_monat->_MonatsArray[$z][12][$x]) ? $_monat->_MonatsArray[$z][12][$x] : '');
	}

	$row[] = ($_monat->_MonatsArray[$z][13] > 0 ? number_format($_monat->_MonatsArray[$z][13], 2, '.', '') : '');
	$saldo = number_format($_monat->_MonatsArray[$z][20], 2, '.', '');
	$row[] = ($saldo <> 0 ? $saldo : '');
	$row[] = trim($_monat->_MonatsArray[$z][15].' '.$_monat->_MonatsArray[$z][14]);
	$row[] = trim($_monat->_MonatsArray[$z][6].' '.$_monat->_MonatsArray[$z][34].' '.$_monat->_MonatsArray[$z][32]);

	$rows[] = $row;
}

$_sum = '';
if($_monat->_SummeWorkProMonat > 0) {
	$_sum = $_monat->_SummeWorkProMonat;
}

$footer = array('', '');
for ($x = 0; $x < $stempelzeiten; $x++) {
	$footer[] = '';
}
$footer[] = $_sum;
$footer[] = number_format($_monat->_SummeSaldoProMonat, 2, '.', '');
$footer[] = $_monat->_SummeAbsenzProMonat;
$footer[] = '';
$rows[] = $footer;

if (class_exists('ZipArchive')) {
	function excel_col_name($index) {
		$name = '';
		while ($index >= 0) {
			$name = chr(($index % 26) + 65).$name;
			$index = (int)($index / 26) - 1;
		}
		return $name;
	}

	$sheetData = '';
	for ($r = 0; $r < count($rows); $r++) {
		$sheetData .= '<row r="'.($r + 1).'">';
		for ($c = 0; $c < count($rows[$r]); $c++) {
			$cellRef = excel_col_name($c).($r + 1);
			$cellValue = excel_escape_xml($rows[$r][$c]);
			$sheetData .= '<c r="'.$cellRef.'" t="inlineStr"><is><t>'.$cellValue.'</t></is></c>';
		}
		$sheetData .= '</row>';
	}

	$sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		.'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
		.'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
		.'<sheetData>'.$sheetData.'</sheetData>'
		.'</worksheet>';

	$workbookXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		.'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
		.'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
		.'<sheets><sheet name="Monat" sheetId="1" r:id="rId1"/></sheets>'
		.'</workbook>';

	$contentTypesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		.'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
		.'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
		.'<Default Extension="xml" ContentType="application/xml"/>'
		.'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
		.'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
		.'<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
		.'</Types>';

	$relsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		.'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
		.'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
		.'</Relationships>';

	$workbookRelsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		.'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
		.'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
		.'<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
		.'</Relationships>';

	$stylesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		.'<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
		.'<fonts count="1"><font><sz val="11"/><name val="Calibri"/></font></fonts>'
		.'<fills count="1"><fill><patternFill patternType="none"/></fill></fills>'
		.'<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
		.'<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
		.'<cellXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/></cellXfs>'
		.'<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
		.'</styleSheet>';

	$tmpFile = tempnam(sys_get_temp_dir(), 'smalltime_xlsx_');
	$zip = new ZipArchive();
	if ($tmpFile !== false && $zip->open($tmpFile, ZipArchive::OVERWRITE) === true) {
		$zip->addFromString('[Content_Types].xml', $contentTypesXml);
		$zip->addFromString('_rels/.rels', $relsXml);
		$zip->addFromString('xl/workbook.xml', $workbookXml);
		$zip->addFromString('xl/_rels/workbook.xml.rels', $workbookRelsXml);
		$zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
		$zip->addFromString('xl/styles.xml', $stylesXml);
		$zip->close();

		echo file_get_contents($tmpFile);
		@unlink($tmpFile);
	} else {
		if ($tmpFile !== false) {
			@unlink($tmpFile);
		}
		echo 'Excel-Export konnte nicht erstellt werden.';
	}
} else {
	$xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$xml .= '<?mso-application progid="Excel.Sheet"?>';
	$xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" '
		.'xmlns:o="urn:schemas-microsoft-com:office:office" '
		.'xmlns:x="urn:schemas-microsoft-com:office:excel" '
		.'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">';
	$xml .= '<Worksheet ss:Name="Monat"><Table>';
	foreach ($rows as $row) {
		$xml .= '<Row>';
		foreach ($row as $value) {
			$xml .= '<Cell><Data ss:Type="String">'.excel_escape_xml($value).'</Data></Cell>';
		}
		$xml .= '</Row>';
	}
	$xml .= '</Table></Worksheet></Workbook>';
	echo $xml;
}