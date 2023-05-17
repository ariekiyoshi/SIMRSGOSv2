<?php
namespace Layanan\V1\Rest\TindakanMedis;
use DBService\SystemArrayObject;

class TindakanMedisEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'KUNJUNGAN'=>2, 'TINDAKAN'=>3, 'TANGGAL'=>4, 'OLEH'=>5, 'STATUS'=>6);
}
