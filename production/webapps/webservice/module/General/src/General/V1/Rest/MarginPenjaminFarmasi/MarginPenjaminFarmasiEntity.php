<?php
namespace General\V1\Rest\MarginPenjaminFarmasi;

use DBService\SystemArrayObject;

class MarginPenjaminFarmasiEntity extends SystemArrayObject
{
	protected $fields = array(
		"ID"=>1
		, "PENJAMIN"=>1
		, "JENIS"=>1
		, "MARGIN"=>1
		, "TANGGAL"=>1
		, "TANGGAL_SK"=>1
		, "NOMOR_SK"=>1
		, "OLEH"=>1
		, "STATUS"=>1
	);
}