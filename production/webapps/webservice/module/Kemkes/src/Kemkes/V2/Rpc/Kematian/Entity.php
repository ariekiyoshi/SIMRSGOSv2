<?php
namespace Kemkes\V2\Rpc\Kematian;

use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array(
		"TAHUN"=>1
		, "BULAN"=>1
		, "REF_ID_KEMKES"=>1
		, "KONTEN"=>1
		, "TANGGAL_UPDATED"=>1
	    , "KIRIM"=>1
	);
}
