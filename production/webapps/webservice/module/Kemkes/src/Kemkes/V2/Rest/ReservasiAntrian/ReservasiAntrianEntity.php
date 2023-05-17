<?php
namespace Kemkes\V2\Rest\ReservasiAntrian;

use DBService\SystemArrayObject;

class ReservasiAntrianEntity extends SystemArrayObject
{
	protected $fields = array(
		"ID"=>1
		, "PASIEN"=>1
		, "NAMA"=>1
		, "TEMPAT_LAHIR"=>1
		, "TANGGAL_LAHIR"=>1
		, "KONTAK"=>1
		, "JENIS"=>1
		, "TANGGAL_DAFTAR"=>1
		, "TANGGAL_KUNJUNGAN"=>1
		, "RUANGAN"=>1
		, "DOKTER"=>1
		, "PENJAMIN"=>1
		, "NOMOR"=>1
		, "JAM"=>1
		, "STATUS"=>1
	);
}