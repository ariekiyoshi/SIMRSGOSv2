<?php
namespace Inventory\V1\Rest\Pengiriman;
use DBService\SystemArrayObject;

class PengirimanEntity extends SystemArrayObject
{
	protected $fields = array('NOMOR'=>1, 'ASAL'=>1, 'TUJUAN'=>1, 'TANGGAL'=>1, 'PERMINTAAN'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
