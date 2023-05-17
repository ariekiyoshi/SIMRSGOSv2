<?php
namespace Pembayaran\V1\Rest\PiutangPerusahaan;
use DBService\SystemArrayObject;

class PiutangPerusahaanEntity extends SystemArrayObject
{
	protected $fields = array('TAGIHAN'=>1, 'PENJAMIN'=>1, 'ALAMAT'=>1, 'TELEPON'=>1, 'TANGGAL'=>1, 'TOTAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
