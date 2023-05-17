<?php
namespace Inventory\V1\Rest\PengirimanDetil;
use DBService\SystemArrayObject;

class PengirimanDetilEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'PENGIRIMAN'=>1, 'PERMINTAAN_BARANG_DETIL'=>1, 'JUMLAH'=>1, 'STATUS'=>1);
}
