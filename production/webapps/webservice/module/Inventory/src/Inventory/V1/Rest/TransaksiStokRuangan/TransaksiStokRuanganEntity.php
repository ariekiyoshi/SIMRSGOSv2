<?php
namespace Inventory\V1\Rest\TransaksiStokRuangan;
use DBService\SystemArrayObject;

class TransaksiStokRuanganEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'BARANG_RUANGAN'=>1, 'JENIS'=>1, 'TANGGAL'=>1, 'JUMLAH'=>1, 'FLAG'=>1);
}
