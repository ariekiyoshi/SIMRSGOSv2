<?php
namespace Penjualan\V1\Rest\Penjualan;
use DBService\SystemArrayObject;

class PenjualanEntity extends SystemArrayObject
{
	protected $fields = array(
		'NOMOR'=>1, 
		'RUANGAN'=>1, 
		'PENGUNJUNG'=>1, 
		'JENIS'=>1,
		'DOKTER'=>1, 
		'KETERANGAN'=>1, 
		'TANGGAL'=>1, 
		'OLEH'=>1, 
		'STATUS'=>1
	);
}