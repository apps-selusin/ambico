create view v_lapgjhrn as  
Select e.lapgroup_id As lapgroup_id,
  e.lapgroup_nama As lapgroup_nama,
  e.lapgroup_index As lapgroup_index,
  d.lapsubgroup_index As lapsubgroup_index,
  a.pegawai_id As pegawai_id,
  a.tgl As tgl,
  a.jk_id As jk_id,
  a.scan_masuk As scan_masuk,
  a.scan_keluar As scan_keluar,
  a.hk_def As hk_def,
  a.pegawai_nip As pegawai_nip,
  a.pegawai_nama As pegawai_nama,
  a.jk_kd As jk_kd,
  a.pembagian2_nama As pembagian2_nama,
  a.pembagian2_id As pembagian2_id,
  c.rumus_id As rumus_id,
  c.rumus_nama As rumus_nama,
  c.hk_gol As hk_gol,
  c.umr As umr,
  c.hk_jml As hk_jml,
  c.upah As upah,
  c.premi_hadir As premi_hadir,
  c.premi_malam As premi_malam,
  c.pot_absen As pot_absen,
  c.lembur As lembur,
  (Case When (isnull(a.scan_masuk) And isnull(a.scan_keluar)) Then 0 Else c.upah
  End) As upah2,
  (Case When (Right(a.jk_kd, 2) = 'S3') Then c.premi_malam Else 0
  End) As premi_malam2,
  (Case
    When (Count((isnull(a.scan_masuk) And isnull(a.scan_keluar) And
    (Right(a.jk_kd, 1) <> 'L'))) > 1) Then 0 Else c.premi_hadir
  End) As premi_hadir2
From (((v_jdw_krj_def a
  Left Join t_rumus_peg b On a.pegawai_id = b.pegawai_id)
  Left Join t_rumus c On b.rumus_id = c.rumus_id)
  Left Join t_lapsubgroup d On a.pembagian2_id = d.pembagian2_id)
  Left Join t_lapgroup e On d.lapgroup_id = e.lapgroup_id
Where (c.hk_gol = a.hk_def) And
  Not (a.pegawai_id In (Select t_rumus2_peg.pegawai_id As pegawai_id
  From t_rumus2_peg))
Order By lapgroup_index,
  lapsubgroup_index,
  pegawai_id,
  tgl;