<?php
namespace Pendaftaran\V1\Rest\Kunjungan;
use DBService\SystemArrayObject;

class KunjunganEntity extends SystemArrayObject
{
	protected $fields = array(
		'NOMOR'=>1, 
		'NOPEN'=>1, 
		'RUANGAN'=>1, 
		'MASUK'=>1, 
		'KELUAR'=>1, 
		'RUANG_KAMAR_TIDUR'=>1, 
		'REF'=>1, 
		'DITERIMA_OLEH'=>1, 
		'BARU'=>1, 
		'TITIPAN'=>1,
		'TITIPAN_KELAS'=>1,
		'STATUS'=>1
	);
}
