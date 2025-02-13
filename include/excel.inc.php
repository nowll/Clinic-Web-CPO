<?php
include_once ("dbh.inc.php");
$export1 = "";
$export2 = "";
$pasienData = array();
$export1 = $_POST['tglExport1'];
$export2 = $_POST['tglExport2'];
$exportQuery = "SELECT waktu, nik, perusahaan, dept, nama, lahir, berobat, diagnosa, obat, 
				tindak, keterangan, dokter, keluhan 
				FROM daftar WHERE berobat BETWEEN '$export1' AND '$export2' ";
$exportResult = mysqli_query($conn, $exportQuery);
while($pasien = mysqli_fetch_assoc($exportResult)){
	$pasienData[] = $pasien;
}
if(isset($_POST["exportExcel"])) {
	$fileName = "klinik_export_".date('dmY') . ".xls";			
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$fileName\"");	
	$showColoumn = false;
	if(!empty($pasienData)) {
	  foreach($pasienData as $pasienInfo) {
		if(!$showColoumn) {		 
		  echo implode("\t", array_keys($pasienInfo)) . "\n";
		  $showColoumn = true;
		}
		echo implode("\t", array_values($pasienInfo)) . "\n";
	  }
	}
	exit;
}