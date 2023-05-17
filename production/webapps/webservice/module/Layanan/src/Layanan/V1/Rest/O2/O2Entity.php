<?php
namespace Layanan\V1\Rest\O2;
use DBService\SystemArrayObject;

class O2Entity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'KUNJUNGAN'=>1, 'FLOW'=>1, 'PASANG'=>1, 'LEPAS'=>1, 'PEMAKAIAN'=>1, 'TANGGAL'=>1,
								'OLEH'=>1, 'STATUS'=>1);
}
