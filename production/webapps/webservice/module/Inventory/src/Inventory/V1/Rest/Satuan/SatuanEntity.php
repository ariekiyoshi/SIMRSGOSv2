<?php
namespace Inventory\V1\Rest\Satuan;
use DBService\SystemArrayObject;

class SatuanEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'NAMA'=>1, 'DESKRIPSI'=>1, 'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
