<?php
namespace General\V1\Rest\TarifO2;
use DBService\SystemArrayObject;

class TarifO2Entity extends SystemArrayObject
{
	protected $fields = array(
		'ID'=>1
		, 'TARIF'=>1
		, 'TANGGAL'=>1
		, 'TANGGAL_SK'=>1
		, 'NOMOR_SK'=>1
		, 'OLEH'=>1
		,'STATUS'=>1
	);
}
