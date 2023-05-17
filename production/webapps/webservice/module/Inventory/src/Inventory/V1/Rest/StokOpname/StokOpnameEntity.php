<?php
namespace Inventory\V1\Rest\StokOpname;
use DBService\SystemArrayObject;

class StokOpnameEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'RUANGAN'=>1, 'TANGGAL'=>1, 'TANGGAL_DIBUAT'=>1, 'KETERANGAN'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
