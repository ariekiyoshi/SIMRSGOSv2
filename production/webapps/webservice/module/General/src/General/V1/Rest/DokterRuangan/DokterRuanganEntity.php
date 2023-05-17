<?php
namespace General\V1\Rest\DokterRuangan;
use DBService\SystemArrayObject;

class DokterRuanganEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'TANGGAL'=>1, 'DOKTER'=>2, 'RUANGAN'=>3, 'STATUS'=>4);
}


