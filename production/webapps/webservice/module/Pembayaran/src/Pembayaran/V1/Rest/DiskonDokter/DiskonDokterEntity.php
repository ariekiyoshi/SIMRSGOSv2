<?php
namespace Pembayaran\V1\Rest\DiskonDokter;
use DBService\SystemArrayObject;

class DiskonDokterEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'TAGIHAN'=>1, 'DOKTER'=>1, 'TOTAL'=>1, 'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
