<?php
namespace Kemkes\V2\Rpc\SITT;

use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array(
		"id"=>1
		, "nourut_pasien"=>1
		, "id_tb_03"=>1
		, "id_periode_laporan"=>1
		, "RI"=>1
		, "tanggal_buat_laporan"=>1
	    , "tahun_buat_laporan"=>1
	    , "kd_wasor"=>1
	    , "noregkab"=>1
	    , "kd_pasien"=>1
	    , "nik"=>1
	    , "jenis_kelamin"=>1
	    , "alamat_lengkap"=>1
	    , "id_propinsi"=>1
	    , "kd_kabupaten"=>1
	    , "id_kecamatan"=>1
	    , "id_kelurahan"=>1
	    , "kd_fasyankes"=>1
	    , "nama_rujukan"=>1
	    , "sebutkan1"=>1
	    , "tipe_diagnosis"=>1
	    , "klasifikasi_lokasi_anatomi"=>1
	    , "klasifikasi_riwayat_pengobatan"=>1
	    , "klasifikasi_status_hiv"=>1
	    , "total_skoring_anak"=>1
	    , "konfirmasiSkoring5"=>1
	    , "konfirmasiSkoring6"=>1
	    , "tanggal_mulai_pengobatan"=>1
	    , "paduan_oat"=>1
	    , "sumber_obat"=>1
	    , "sebutkan"=>1
	    , "sebelum_pengobatan_hasil_mikroskopis"=>1
	    , "sebelum_pengobatan_hasil_tes_cepat"=>1
	    , "sebelum_pengobatan_hasil_biakan"=>1
	    , "noreglab_bulan_2"=>1
	    , "hasil_mikroskopis_bulan_2"=>1
	    , "noreglab_bulan_3"=>1
	    , "hasil_mikroskopis_bulan_3"=>1
	    , "noreglab_bulan_5"=>1
	    , "hasil_mikroskopis_bulan_5"=>1
	    , "akhir_pengobatan_noreglab"=>1
	    , "akhir_pengobatan_hasil_mikroskopis"=>1
	    , "tanggal_hasil_akhir_pengobatan"=>1
	    , "hasil_akhir_pengobatan"=>1
	    , "tanggal_dianjurkan_tes"=>1
	    , "tanggal_tes_hiv"=>1
	    , "hasil_tes_hiv"=>1
	    , "ppk"=>1
	    , "art"=>1
	    , "tb_dm"=>1
	    , "terapi_dm"=>1
	    , "umur"=>1
	    , "status_pengobatan"=>1
	    , "foto_toraks"=>1
	    , "toraks_tdk_dilakukan"=>1
	    , "pindah_ro"=>1
	    , "keterangan"=>1
	    , "tahun"=>1
	    , "no_bpjs"=>1
	    , "tgl_lahir"=>1
	    , "kode_icd_x"=>1
	    , "kirim"=>1
	);
}