<?php
namespace Inventory\V1\Rest\BarangRuangan;
use DBService\SystemArrayObject;

class BarangRuanganEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'RUANGAN'=>1, 'BARANG'=>1, 'STOK'=>1, 'TANGGAL'=>1, 'TRANSAKSI_STOK_RUANGAN'=>1, 'STATUS'=>1);	
}
