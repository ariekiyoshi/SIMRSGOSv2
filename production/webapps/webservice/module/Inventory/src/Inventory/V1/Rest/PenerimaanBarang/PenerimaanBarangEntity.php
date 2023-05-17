<?php
namespace Inventory\V1\Rest\PenerimaanBarang;
use DBService\SystemArrayObject;

class PenerimaanBarangEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'FAKTUR'=>1,  'RUANGAN'=>1, 'TANGGAL'=>1, 'REKANAN'=>1, 'KETERANGAN'=>1, 'PPN'=>1, 'MASA_BERLAKU'=>1, 'TANGGAL_DIBUAT'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
