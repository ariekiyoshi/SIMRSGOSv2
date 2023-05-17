<?php
namespace Pegawai\V1\Rest\KontakPegawai;
use DBService\SystemArrayObject;

class KontakPegawaiEntity extends SystemArrayObject
{
	protected $fields = array(
		"ID"=>1
		,"JENIS"=>1
		, "NIP"=>1
		, "NOMOR"=>1
		, "STATUS"=>1
	);
}
