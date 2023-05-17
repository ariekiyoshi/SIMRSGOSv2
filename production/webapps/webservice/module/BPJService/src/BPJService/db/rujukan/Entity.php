<?php
namespace BPJService\db\rujukan;

use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array(
		"noRujukan" => 1
		, "noSep" => 1
		, "tglRujukan" => 1
		, "ppkDirujuk" => 1
		, "jnsPelayanan" => 1
		, "catatan" => 1
		, "diagRujukan" => 1
		, "tipeRujukan" => 1
		, "poliRujukan" => 1
		, "user" => 1
		, "status" => 1
	);
}
