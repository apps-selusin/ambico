CREATE DEFINER=`root`@`localhost` PROCEDURE `p_gen_rekon`(in mstart date, in mend date)
BEGIN
update
	t_jdw_krj_def a
	left join pegawai b on a.pegawai_id = b.pegawai_id
	left join t_jk c on a.jk_id = c.jk_id
	left join att_log d on
		b.pegawai_pin = d.pin
		and cast(d.scan_date as date) = cast(a.tgl as date)
        and cast(d.scan_date as time) between cast((concat('1974-12-24 ', c.jk_m) - interval '60' minute) as time) and cast(c.jk_m as time)
set
	scan_masuk = d.scan_date
where
    a.tgl between mstart and mend;
update
	t_jdw_krj_def a
	left join pegawai b on a.pegawai_id = b.pegawai_id
	left join t_jk c on a.jk_id = c.jk_id
	left join att_log e on
		b.pegawai_pin = e.pin
        and cast(e.scan_date as time) between cast(c.jk_k as time) and cast((concat('1974-12-24 ', c.jk_k) + interval '60' minute) as time)
        and cast(e.scan_date as date) = 
			(case when left(c.jk_kd, 2) = 'S3' then
				cast(a.tgl + interval 1 day as date)
			else
				cast(a.tgl as date)
			end)
set
	scan_keluar = e.scan_date
where
    a.tgl between mstart and mend;
END