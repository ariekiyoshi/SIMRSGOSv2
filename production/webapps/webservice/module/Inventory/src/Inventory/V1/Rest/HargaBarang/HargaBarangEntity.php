<?php
namespace Inventory\V1\Rest\HargaBarang;
use DBService\SystemArrayObject;

class HargaBarangEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'BARANG'=>1, 'HARGA_BELI'=>1, 'HARGA_JUAL'=>1, 'PPN'=>1, 'MASA_BERLAKU'=>1, 'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1);	
}
