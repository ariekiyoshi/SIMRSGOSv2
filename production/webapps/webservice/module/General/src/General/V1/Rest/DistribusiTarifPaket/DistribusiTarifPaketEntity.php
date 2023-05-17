<?php
namespace General\V1\Rest\DistribusiTarifPaket;
use DBService\SystemArrayObject;

class DistribusiTarifPaketEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'PAKET'=>1, 'ADMINISTRASI'=>1, 'SARANA'=>1, 'BHP'=>1, 'DOKTER_OPERATOR'=>1, 'DOKTER_ANASTESI'=>1, 'DOKTER_LAINNYA'=>1,
	'PENATA_ANASTESI'=>1, 'PARAMEDIS'=>1, 'NON_MEDIS'=>1, 'TARIF'=>1, 'TANGGAL'=>1, 'TANGGAL_SK'=>1, 'NOMOR_SK'=>1, 'STATUS'=>1);
}
