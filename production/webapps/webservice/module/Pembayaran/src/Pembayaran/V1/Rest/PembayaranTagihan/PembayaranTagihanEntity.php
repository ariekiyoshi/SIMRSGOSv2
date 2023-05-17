<?php
namespace Pembayaran\V1\Rest\PembayaranTagihan;
use DBService\SystemArrayObject;

class PembayaranTagihanEntity extends SystemArrayObject
{
	protected $fields = array('TAGIHAN'=>1, 'TANGGAL'=>1, 'JENIS'=>1, 'REF'=>1, 'DESKRIPSI'=>1, 'TOTAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
