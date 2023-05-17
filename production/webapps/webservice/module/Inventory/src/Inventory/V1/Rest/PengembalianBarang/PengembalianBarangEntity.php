<?php
namespace Inventory\V1\Rest\PengembalianBarang;
use DBService\SystemArrayObject;

class PengembalianBarangEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'RUANGAN'=>1, 'REKANAN'=>1, 'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
