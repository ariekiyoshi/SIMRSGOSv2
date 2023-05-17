<?php
namespace BPJService\db\peserta;

use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array(
		"noKartu" => 1
		, "nik" => 1
		, "norm" => 1
		, "nama" => 1
		, "pisa" => 1
		, "sex" => 1
		, "tglLahir" => 1
		, "tglCetakKartu" => 1
		, "kdProvider" => 1
		, "nmProvider" => 1
		, "kdCabang" => 1
		, "nmCabang" => 1
		, "kdJenisPeserta" => 1
		, "nmJenisPeserta" => 1
		, "kdKelas" => 1
		, "nmKelas" => 1
		, "tglTAT" => 1
		, "tglTAT" => 1
		, "tglTMT" => 1		
		, "umurSaatPelayanan" => 1
		, "umurSekarang" => 1
		, "dinsos" => 1
		, "iuran" => 1
		, "noSKTM" => 1
		, "prolanisPRB" => 1
		, "kdStatusPeserta" => 1
		, "ketStatusPeserta" => 1
	);
}
