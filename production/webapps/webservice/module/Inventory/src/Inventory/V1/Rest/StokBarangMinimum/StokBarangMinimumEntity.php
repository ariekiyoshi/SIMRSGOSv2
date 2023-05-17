<?php
namespace Inventory\V1\Rest\StokBarangMinimum;
use DBService\SystemArrayObject;

class StokBarangMinimumEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'RUANGAN'=>1, 'BARANG'=>1, 'STOK'=>1, 'TANGGAL'=>1, 'TRANSAKSI_STOK_RUANGAN'=>1, 'STATUS'=>1);
}
