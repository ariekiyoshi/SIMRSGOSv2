<?php
namespace Pendaftaran\V1\Rest\Kecelakaan;

use DBService\SystemArrayObject;

class KecelakaanEntity extends SystemArrayObject
{
	protected $fields = array(
		"NOMOR"=>1
		, "NOPEN"=>1
		, "PENJAMIN"=>1
		, "LOKASI"=>1 // Berubah menjadi keterangan
		, "TANGGAL_KEJADIAN"=>1
	    , "SUPLESI"=>1
	    , "NO_SEP_SUPLESI"=>1
	    , "KODE_PROPINSI"=>1
	    , "KODE_KABUPATEN"=>1
	    , "KODE_KECAMATAN"=>1
		, "STATUS"=>1
	);
}
