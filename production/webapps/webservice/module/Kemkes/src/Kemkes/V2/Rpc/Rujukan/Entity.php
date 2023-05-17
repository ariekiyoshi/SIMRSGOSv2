<?php
namespace Kemkes\V2\Rpc\Rujukan;

use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array(
		"TANGGAL"=>1
		, "REF_ID_KEMKES"=>1
		, "MASUK"=>1
		, "KELUAR"=>1
		, "BALIK"=>1
		, "TANGGAL_UPDATED"=>1
	    , "KIRIM"=>1
	);
}
