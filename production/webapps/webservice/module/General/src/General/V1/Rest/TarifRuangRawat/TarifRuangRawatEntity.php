<?php
namespace General\V1\Rest\TarifRuangRawat;
use DBService\SystemArrayObject;

class TarifRuangRawatEntity extends SystemArrayObject
{
	protected $fields = array(
		'ID'=>1
		, 'KELAS'=>1
		, 'SARANA'=>1
		, 'JASA_PELAYANAN'=>1
		, 'TARIF'=>1
		, 'TANGGAL'=>1
		, 'TANGGAL_SK'=>1
		, 'NOMOR_SK'=>1
		, 'OLEH'=>1
		, 'STATUS'=>1
	);
}
