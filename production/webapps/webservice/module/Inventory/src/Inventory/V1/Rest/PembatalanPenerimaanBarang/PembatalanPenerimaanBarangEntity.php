<?php
namespace Inventory\V1\Rest\PembatalanPenerimaanBarang;
use DBService\SystemArrayObject;

class PembatalanPenerimaanBarangEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'PENERIMAAN_BARANG'=>1, 'JENIS'=>1, 'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
