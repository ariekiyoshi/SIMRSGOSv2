<?php
namespace Pembayaran\V1\Rest\PembatalanTagihan;
use DBService\SystemArrayObject;

class PembatalanTagihanEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'TAGIHAN'=>1, 'JENIS'=>1, 'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
