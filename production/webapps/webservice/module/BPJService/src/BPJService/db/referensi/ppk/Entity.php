<?php
namespace BPJService\db\referensi\ppk;

use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array(
		"kode" => 1
		, "jenis" => 1
		, "nama" => 1
		, "deswilayah" => 1
		, "status" => 1
	);
}
