<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "userfn13.php" ?>
<?php
//echo "masuk";
/*if ($_SERVER["HTTP_HOST"] == "ambico.nma-indonesia.com") {
	//include "adodb5/adodb.inc.php";
	//$conn = ADONewConnection('mysql');
	//$conn->Connect('mysql.idhostinger.com','u945388674_ambi2','M457r1P 81','u945388674_ambi2');
	include "conn_adodb.php";
}
else {
	include_once "phpfn13.php";
	$conn =& DbHelper();
}*/

$msql = "delete from t_gjbrngan";
Conn()->Execute($msql);

$query = "
select
	a.*
    , b.*
    , c.*
    ,
    f_pembulatan(round((case when (c.tarif_acuan = 0) then 
		(a.hasil * c.tarif1)
	else
		case when (a.hasil <= c.tarif_acuan) then c.tarif1 else c.tarif2 end * a.hasil
	end) / d.pembagi)) as upah_peg
	, e.pegawai_nama
from
	t_keg_master a
	left join t_keg_detail b on a.kegm_id = b.kegm_id
    left join t_kegiatan c on a.keg_id = c.keg_id
    left join (select kegm_id, sum(case when not isnull(scan_masuk) and not isnull(scan_keluar) then 1 else 0 end) as pembagi from t_keg_detail group by kegm_id) d on a.kegm_id = d.kegm_id
	left join pegawai e on e.pegawai_id = b.pegawai_id
where
	a.tgl between '".$_POST["start"]."' and '".$_POST["end"]."'
"; //echo $query;
$rs = Conn()->Execute($query);
while (!$rs->EOF) {
	$mkegm_id = $rs->fields["kegm_id"];
	$mkeg_nama = $rs->fields["keg_nama"];
	$mpegawai_nama = $rs->fields["pegawai_nama"];
	$mupah_peg = 0;
	
	while ($mpegawai_nama == $rs->fields["pegawai_nama"] and !$rs->EOF) {
		$mupah_peg += $rs->fields["upah_peg"];
		$rs->MoveNext();
	}
	
	$query = "
		insert into t_gjbrngan values (null, ".$mkegm_id.", '".$mkeg_nama."', '".addslashes($mpegawai_nama)."', ".$mupah_peg.", '".$_POST["start"]."', '".$_POST["end"]."')
		";
	Conn()->Execute($query);

}
header("location: r_lapgjbrngansmry.php");
?>