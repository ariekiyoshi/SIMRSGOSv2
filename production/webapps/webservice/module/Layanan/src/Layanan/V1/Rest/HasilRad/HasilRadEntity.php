<?php
namespace Layanan\V1\Rest\HasilRad;
use DBService\SystemArrayObject;

class HasilRadEntity extends SystemArrayObject
{
	protected $fields = array('TINDAKAN_MEDIS'=>1, 'TANGGAL'=>1, 'KLINIS'=>1, 'KESAN'=>1, 'USUL'=>1, 'HASIL'=>1, 'BTK'=>1, 'DOKTER'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
