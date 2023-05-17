<?php
namespace Pembayaran\V1\Rest\EDC;
use DBService\SystemArrayObject;

class EDCEntity extends SystemArrayObject
{
	protected $fields = array(
		'ID'=>1, 
		'TAGIHAN'=>1, 
		'BANK'=>1, 
		'JENIS_KARTU'=>1, 
		'NOMOR'=>1, 
		'PEMILIK'=>1, 
		'APRV_CODE'=>1, 
		'TOTAL'=>1, 
		'TANGGAL'=>1, 
		'OLEH'=>1, 
		'STATUS'=>1
	);
}
