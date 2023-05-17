<?php
namespace Kemkes\V2\Rpc\Indikator;

use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array(
		"TAHUN"=>1
		, "PERIODE"=>1
		, "JENIS"=>1
		, "REF_ID_KEMKES"=>1
		, "BOR"=>1
		, "ALOS"=>1
		, "BTO"=>1
		, "TOI"=>1
		, "NDR"=>1
		, "GDR"=>1
		, "TANGGAL_UPDATED"=>1
	    , "KIRIM"=>1
	);
}
