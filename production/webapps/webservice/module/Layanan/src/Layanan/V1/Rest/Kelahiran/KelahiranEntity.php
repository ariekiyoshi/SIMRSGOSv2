<?php
namespace Layanan\V1\Rest\Kelahiran;

use DBService\SystemArrayObject;

class KelahiranEntity extends SystemArrayObject
{
	protected $fields = array(
		"ID"=>1
		, "NOPEN"=>1
		, "KUNJUNGAN"=>1
		, "NAMA"=>1
		, "JENIS_KELAMIN"=>1
		, "TANGGAL"=>1
		, "BERAT"=>1
		, "PANJANG"=>1
		, "OLEH"=>1
		, "STATUS"=>1
	);
}
