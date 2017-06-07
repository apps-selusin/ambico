<?php

// Global user functions
// Filter for 'Last Month' (example)
function GetLastMonthFilter($FldExpression, $dbid) {
	$today = getdate();
	$lastmonth = mktime(0, 0, 0, $today['mon']-1, 1, $today['year']);
	$sVal = date("Y|m", $lastmonth);
	$sWrk = $FldExpression . " BETWEEN " .
		ewr_QuotedValue(ewr_DateVal("month", $sVal, 1, $dbid), EWR_DATATYPE_DATE, $dbid) .
		" AND " .
		ewr_QuotedValue(ewr_DateVal("month", $sVal, 2, $dbid), EWR_DATATYPE_DATE, $dbid);
	return $sWrk;
}

// Filter for 'Starts With A' (example)
function GetStartsWithAFilter($FldExpression, $dbid) {
	return $FldExpression . ewr_Like("'A%'", $dbid);
}

// Page Loading event
function Page_Loading() {

	//echo "Page Loading";
}

// Page Rendering event
function Page_Rendering() {

	//echo "Page Rendering";
}

// Page Unloaded event
function Page_Unloaded() {

	//echo "Page Unloaded";
}

function ewr_CurrentHost() {
	return ewr_ServerVar("HTTP_HOST");
}

function tgl_indo($tgl) {
	$a_namabln = array(
		1 => "Jan",
		"Feb",
		"Mar",
		"Apr",
		"Mei",
		"Jun",
		"Jul",
		"Ags",
		"Sep",
		"Okt",
		"Nov",
		"Des");
	$a_hari = array(
		"Min",
		"Sen",
		"Sel",
		"Rab",
		"Kam",
		"Jum",
		"Sab");
	$tgl_data = strtotime($tgl);

	//$tgl_data = $tgl;
	$tanggal = date("d", $tgl_data);
	$bulan = $a_namabln[intval(date("m", $tgl_data))];
	$tahun = date("Y", $tgl_data);

	//$hari = date("w", $tgl);
	return $a_hari[date("w", $tgl_data)].", ".$tanggal." ".$bulan." ".$tahun;
}

function tgl_indo_header($tgl) {
	$a_namabln = array(
		1 => "Januari",
		"Februari",
		"Maret",
		"April",
		"Mei",
		"Juni",
		"Juli",
		"Agustus",
		"September",
		"Oktober",
		"November",
		"Desember");
	$a_hari = array(
		"Min",
		"Sen",
		"Sel",
		"Rab",
		"Kam",
		"Jum",
		"Sab");
	$tgl_data = strtotime($tgl);

	//$tgl_data = $tgl;
	$tanggal = date("d", $tgl_data);
	$bulan = $a_namabln[intval(date("m", $tgl_data))];
	$tahun = date("Y", $tgl_data);

	//$hari = date("w", $tgl);
	//return $a_hari[date("w", $tgl_data)].", ".$tanggal." ".$bulan." ".$tahun;

	return $tanggal." ".$bulan." ".$tahun;
}

function pembulatan($uang) {
	$puluhan = substr(intval($uang), -2); //$ratusan = substr($uang, -3);
	if($puluhan == 50) { //if($ratusan<500)
		$akhir = $uang; // - $puluhan; //$akhir = $uang - $ratusan;
	}
	elseif($puluhan < 50) { //if($ratusan<500)

		//       16649 - 49
		// 16600

		$akhir = $uang - $puluhan; //$akhir = $uang - $ratusan;
	}
	else {

		//       16651 - (51 - 50)
		//       16651 - 1
		// 16650

		$akhir = $uang - ($puluhan - 50); //$akhir = $uang + (1000-$ratusan);
 	}

 	//echo number_format($akhir, 2, ',', '.');;
 	return $akhir;
}

//$uang = 133500;
//pembulatan($uang); // hasilnya adalah 134.000,00
//kalau tanpa pembulatan
//echo number_format($uang, 2, ',', '.');; // hasilnya 133.500,00

?>
