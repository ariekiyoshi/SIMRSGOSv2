<?php
namespace Inventory\V1\Rest\StokOpnameDetil;
use DBService\SystemArrayObject;

class StokOpnameDetilEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'STOK_OPNAME'=>1, 'BARANG_RUANGAN'=>1, 'SISTEM'=>1, 'MANUAL'=>1, 'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
