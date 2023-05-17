<?php
namespace Pegawai\V1\Rest\KartuIdentitas;
use DBService\SystemArrayObject;

class KartuIdentitasEntity extends SystemArrayObject
{
	protected $fields = array(
		"ID"=>1
		,"JENIS"=>1
		, "NIP"=>1
		, "NOMOR"=>1
		, "ALAMAT"=>1
		, "RT"=>1
		, "RW"=>1
		, "KODEPOS"=>1
		, "WILAYAH"=>1
	);
}
