<?php
namespace Kemkes\V2\Rpc\Kunjungan;

use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array(
		"TANGGAL"=>1
		, "REF_ID_KEMKES"=>1
		, "RJ"=>1
		, "RD"=>1
		, "RI"=>1
		, "TANGGAL_UPDATED"=>1
	    , "KIRIM"=>1
	);
}
