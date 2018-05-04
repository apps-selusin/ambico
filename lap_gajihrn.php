<?php
if ($_SERVER["HTTP_HOST"] == "ambico.nma-indonesia.com") {
	include "adodb5/adodb.inc.php";
	$conn = ADONewConnection('mysql');
	$conn->Connect('mysql.idhostinger.com','u945388674_ambi2','M457r1P 81','u945388674_ambi2');
}
else {
	include_once "phpfn13.php";
	$conn =& DbHelper();
}

function f_sesuaikanjam($jam_selesai, $jam_mulai) {
	$lama_lembur = strtotime("+12 hours", strtotime($jam_selesai)) - strtotime("-12 hours", strtotime($jam_mulai));
	return $lama_lembur;
}

function f_carilamakerja($p_pegawai_id, $p_tgl, $p_conn) {
	$query = "select * from t_pengecualian_peg where pegawai_id = ".$p_pegawai_id." and tgl = '".$p_tgl."'";
	$rs = $p_conn->Execute($query);
	if (!$rs->EOF) {
		$lama_kerja = strtotime($rs->fields["jam_keluar"]) - strtotime($rs->fields["jam_masuk"]);
		$lama_kerja = floor($lama_kerja / (60 * 60));
		return $lama_kerja;
		/*$awal  = strtotime('2017-08-10 10:05:25');
		$akhir = strtotime('2017-08-11 11:07:33');
		$diff  = $akhir - $awal;

		$jam   = floor($diff / (60 * 60));
		$menit = $diff - $jam * (60 * 60);
		echo 'Waktu tinggal: ' . $jam .  ' jam, ' . floor( $menit / 60 ) . ' menit';*/
	}
}

function f_carilamaijin($p_pegawai_id, $p_tgl, $p_conn) {
	$query = "select * from t_pengecualian_peg where pegawai_id = ".$p_pegawai_id." and tgl = '".$p_tgl."'";
	$rs = $p_conn->Execute($query);
	if (!$rs->EOF) {
		if (strtotime($rs->fields["jam_keluar"]) < strtotime($rs->fields["jam_masuk"])) {
			$lama_ijin = f_sesuaikanjam($rs->fields["jam_keluar"], $rs->fields["jam_masuk"]);
		}
		else {
			$lama_ijin = strtotime($rs->fields["jam_keluar"]) - strtotime($rs->fields["jam_masuk"]);
		}
		$lama_ijin = floor($lama_ijin / 60);
		return $lama_ijin;
		/*$awal  = strtotime('2017-08-10 10:05:25');
		$akhir = strtotime('2017-08-11 11:07:33');
		$diff  = $akhir - $awal;

		$jam   = floor($diff / (60 * 60));
		$menit = $diff - $jam * (60 * 60);
		echo 'Waktu tinggal: ' . $jam .  ' jam, ' . floor( $menit / 60 ) . ' menit';*/
	}
}

function f_carikodepengecualian($mpegawai_id, $mtgl, $mconn) {
	$msql = "select f_carikodepengecualian(".$mpegawai_id.", '".$mtgl."') as r_kode";
	$rsf = $mconn->Execute($msql);
	if (!$rsf->EOF) {
		return $rsf->fields["r_kode"];
	}
}

function f_harilibur($mtgl, $mconn) {
	$msql = "select f_harilibur('".$mtgl."') as ada";
	$rsf = $mconn->Execute($msql);
	if (!$rsf->EOF) {
		return $rsf->fields["ada"];
	}
}

$msql = "delete from t_gjhrn";
$conn->Execute($msql);

$msql = "
	select
		e.lapgroup_id
		, e.lapgroup_nama
		, e.lapgroup_index
		, d.lapsubgroup_index
		, a.*
		, c.*
		, b.t_jabatan
	from
		v_jdw_krj_def a
		left join t_rumus_peg b on a.pegawai_id = b.pegawai_id
		left join t_rumus c on b.rumus_id = c.rumus_id
		left join t_lapsubgroup d on a.pembagian2_id = d.pembagian2_id
		left join t_lapgroup e on d.lapgroup_id = e.lapgroup_id
	where
		tgl between '".$_POST['start']."' and '".$_POST['end']."'
		and c.hk_gol = a.hk_def
		and a.pegawai_id not in (select pegawai_id from t_rumus2_peg)
	order by
		e.lapgroup_index
		, d.lapsubgroup_index
		, a.pegawai_id
		, a.tgl
	"; //echo $msql; exit;
$rs = $conn->Execute($msql);
while (!$rs->EOF) { 
	$mlapgroup_id   = $rs->fields["lapgroup_id"];
	$mlapgroup_nama = $rs->fields["lapgroup_nama"];
	$mlapgroup_index = $rs->fields["lapgroup_index"];
	$mtotal1 = 0;
	while ($rs->fields["lapgroup_id"] == $mlapgroup_id and !$rs->EOF) {
		$mpembagian2_id   = $rs->fields["pembagian2_id"];
		$mpembagian2_nama = $rs->fields["pembagian2_nama"];
		$mtotal2 = 0;
		while ($rs->fields["pembagian2_id"] == $mpembagian2_id and !$rs->EOF) {
			$mpegawai_id      = $rs->fields["pegawai_id"];
			$mpegawai_nama    = $rs->fields["pegawai_nama"];
			$mpegawai_nip     = $rs->fields["pegawai_nip"];
			$mt_jabatan       = $rs->fields["t_jabatan"];
			$mupah            = 0;
			$mpremi_malam     = 0;
			$mpremi_hadir     = 0;
			$mtidak_masuk     = 0;
			$mpot_absen       = 0;
			$mjml_premi_malam = 0;
			$mabsen           = 0;
			$mterlambat       = 0;
			$mflag_S1         = 0;
			$dapat_premi      = 1;
			while ($mpegawai_id == $rs->fields["pegawai_id"] and !$rs->EOF) {
				
				// check data valid
				$data_valid = false; // => data belum dicheck
				if (!is_null($rs->fields["scan_masuk"]) and !is_null($rs->fields["scan_keluar"])) { // => data sedang dicheck
					$data_valid = true; // => data sudah dicheck dan valid
				}
								
				// hitung premi hadir & pot. absen
				/*
				if (!$data_valid and substr($rs->fields["jk_kd"], -1) != "L") {
					$msql = "select f_cari_pengecualian(".$mpegawai_id.", '".$rs->fields["tgl"]."') as ada";
					$rs3 = $conn->Execute($msql); // echo $msql; exit;
					if ($rs3->fields["ada"]) {
						
					}
					else {
						$mpremi_hadir = 0;
						$mpot_absen += $rs->fields["pot_absen"];
					}
				}
				*/
				
				// cari data pengecualian
				$kode_pengecualian = f_carikodepengecualian($mpegawai_id, $rs->fields["tgl"], $conn);

				if (!$data_valid and $kode_pengecualian == null) {
					//echo $rs->fields["tgl"]." - ".f_harilibur($rs->fields["tgl"], $conn);
					// tidak ada data pengecualian
					
					// check hari libur
					if (substr($rs->fields["jk_kd"], -1) == "L" or f_harilibur($rs->fields["tgl"], $conn) == 1) {
						
					//if (substr($rs->fields["jk_kd"], -1) == "L") {
						/*if ($bagian == "KEAMANAN" or $bagian == "KENDARAAN") {
							$mt_um += $t_um;
						}*/
					}
					else {
						//$mpremi_hadir = 0;
						$mabsen = 1;
						$mupah += $rs->fields["upah"]; // 10:01 PM 8/28/2017
						$mpot_absen += $rs->fields["pot_absen"];
						//$mabsen = 1; // untuk acuan perhitungan tunjangan hadir
						//$mp_absen += ($hk_def == 5 ? $p_absen5 : $p_absen6);
					}
				}
				else {
					// $mdata_valid = 0;
					$data_valid = true;
					// ada data pengecualian
					// S1 => tidak diproses; tidak ada potongan absen;
					// P4 => tidak diproses; tidak ada potongan absen;
					// IS => tidak diproses; tidak ada potongan absen; invalid scan;
					// TS => tidak diproses; tidak ada potongan absen; tukar shift;
					// LB => tidak diproses; tidak ada potongan absen; lembur;

					// TL => terlambat;
					if ($kode_pengecualian == "TL") {
						$mterlambat = 1; // untuk acuan perhitungan tunjangan hadir
					}

					// HD => half day;
					if ($kode_pengecualian == "HD") { // 1:14 PM 2/4/2018
						//$lama_kerja = f_carilamakerja($mpegawai_id, $rs->fields["tgl"], $conn);
						$lama_ijin = f_carilamaijin($mpegawai_id, $rs->fields["tgl"], $conn);
						
						/*$lama_ijin060119 = 0;
						$lama_ijin120239 = 0;
						$lama_ijin240480 = 0;
						if ($lama_ijin != null and $lama_ijin >= 60 and $lama_ijin < 120) {
							$lama_ijin060119 = 1;
							$dapat_premi = 0;
						}
						else if ($lama_ijin != null and $lama_ijin >= 120 and $lama_ijin < 240) {
							$lama_ijin120239 = 1;
							$dapat_premi = 0;
							$mpot_absen += $rs->fields["pot_absen"] / 2;
						}
						else {
							$lama_ijin240480 = 1;
							$dapat_premi = 0;
							$mpot_absen += $rs->fields["pot_absen"];
						}*/
						
						if ($lama_ijin != null and $lama_ijin >= 240) {
							$lama_ijin120239 = 1;
							$dapat_premi = 0;
							$mpot_absen += $rs->fields["pot_absen"] / 2;
						}
						else {
							$lama_ijin240480 = 1;
							$dapat_premi = 0;
							$mpot_absen += $rs->fields["pot_absen"];
						}
						
						/*if ($lama_kerja != null and $lama_kerja >= 3) {
							//$mp_absen += ($hk_def == 5 ? $p_absen5 : $p_absen6) / 2;
							$mpot_absen += $rs->fields["pot_absen"] / 2;
						}
						else {
							$mpot_absen += $rs->fields["pot_absen"];
							//$mp_absen += ($hk_def == 5 ? $p_absen5 : $p_absen6);
						}*/
					}
				}
				
				if ($data_valid) {
					// upah
					if (substr($rs->fields["jk_kd"], -1) == "L" or f_harilibur($rs->fields["tgl"], $conn) == 1) {
					}
					else {
						$mupah += $rs->fields["upah"];
					}
					//$mupah += $rs->fields["upah"];
					
					// premi hadir
					$mpremi_hadir = $rs->fields["premi_hadir"]; // 10:03 PM 8/28/2017
					
					/*
					10:03 PM 8/28/2017
					*/
					
					if ($kode_pengecualian == "S1" 
						or $kode_pengecualian == "P4" 
						or $kode_pengecualian == "TL"
						//or $kode_pengecualian == "HD" 1:21 PM 2/4/2018
						) {
							
						$mpremi_hadir = 0;
						$mflag_S1 = 1;
					}
					
					
					/*
					1:22 PM 2/4/2018
					*/
					if ($kode_pengecualian == "HD") {
						if ($dapat_premi == 0) {
							$mpremi_hadir = 0;
							$mflag_S1 = 1;
						}
					}
					
					/*
					10:03 PM 8/28/2017
					*/
					
					// hitung premi malam
					//if (!$data_valid and substr($rs->fields["jk_kd"], 0, 2) == "S3") {
					if (substr($rs->fields["jk_kd"], 0, 2) == "S3") {
						$mpremi_malam += $rs->fields["premi_malam"];
					}
				}
				
				/*if ($mpegawai_nip == '2821') { // checking per pegawai
					echo $mpegawai_nama;
					echo " - ".$rs->fields["tgl"];
					echo " - ".$mpremi_hadir;
					echo "</br>";
					//exit;
				}*/
				
				$rs->MoveNext();
			}
			
			//echo $mpegawai_nip."<br>";
			if ($mpegawai_nip == '2821') {
				//echo $mpegawai_nama;
				//echo " - ".$rs->fields["tgl"];
				//echo " - ".$mpremi_hadir;
				//echo "</br>";
				//exit;
			}
			
			if (isset($_POST["radio_proses"]) and $_POST["radio_proses"]) {
				//$mupah += $mt_jabatan;
			}
			
			if ($mabsen == 1 or $mterlambat == 1 or $mflag_S1 == 1) $mpremi_hadir = 0; //$t_hadir = 0;
			
			$mtotal = $mupah + $mt_jabatan + $mpremi_malam + $mpremi_hadir - $mpot_absen;
			//$mpegawai_nama = mysql_real_escape_string($mpegawai_nama);
			$mpegawai_nama = addslashes($mpegawai_nama);
			$msql = "
				insert into t_gjhrn values (null, 
				".$mlapgroup_index.",
				'".$mlapgroup_nama."'
				, '".$mpembagian2_nama."'
				, '".$mpegawai_nama."'
				, '".$mpegawai_nip."'
				, ".$mupah."
				, ".$mt_jabatan."
				, ".$mpremi_malam."
				, ".$mpremi_hadir."
				, ".$mpot_absen."
				, ".$mtotal."
				, '".$_POST["start"]."'
				, '".$_POST["end"]."'
				)
				"; //if ($mpegawai_nip == '2930') {echo $msql; exit;}
			$conn->Execute($msql);
			$mtotal2 += $mupah;
			//$mno++;
		}
		$mtotal1 += $mtotal2;
	}
	$mgrand_total += $mtotal1;
}
$rs->Close();
//header("location: r_lapgjhrnsmry.php?start=".$_POST["start"]."&end=".$_POST["end"]."");
header("location: r_lapgjhrnsmry.php");
?>