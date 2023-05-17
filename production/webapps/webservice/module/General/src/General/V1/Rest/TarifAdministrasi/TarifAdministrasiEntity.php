<?php
namespace General\V1\Rest\TarifAdministrasi;
use DBService\SystemArrayObject;

class TarifAdministrasiEntity extends SystemArrayObject
{
	protected $fields = array(
		'ID'=>1
		, 'ADMINISTRASI'=>1
		, 'JENIS_KUNJUNGAN'=>1
	    , 'PASIEN_BARU'=>1
		, 'TARIF'=>1
		, 'TANGGAL'=>1
		,'TANGGAL_SK'=>1
		,'NOMOR_SK'=>1
		,'OLEH'=>1
		,'STATUS'=>1);
}
