<?php
namespace DukcapilService\db\keluarga;

use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array(
		"NO_KK" => 1
		, "NO_PROP" => 1
		, "PROP_NAME" => 1
		, "NO_KAB" => 1
		, "KAB_NAME" => 1
		, "NO_KEC" => 1
		, "KEC_NAME" => 1
		, "NO_KEL" => 1
		, "KEL_NAME" => 1
		, "ALAMAT" => 1
		, "NO_RT" => 1
	    , "NO_RW" => 1
	    , "DUSUN" => 1
	    , "KODE_POS" => 1
	);
}
