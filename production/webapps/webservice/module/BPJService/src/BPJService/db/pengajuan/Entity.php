<?php
namespace BPJService\db\pengajuan;

use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array(
		"noKartu" => 1
		, "tglSep" => 1
		, "jnsPelayanan" => 1
		, "keterangan" => 1
		, "user" => 1
		, "tgl" => 1
		, "tglAprove" => 1
		, "userAprove" => 1
		, "status" => 1
	);
}
