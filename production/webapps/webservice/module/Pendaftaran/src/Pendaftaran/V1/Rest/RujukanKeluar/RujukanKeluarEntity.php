<?php
namespace Pendaftaran\V1\Rest\RujukanKeluar;

use DBService\SystemArrayObject;

class RujukanKeluarEntity extends SystemArrayObject
{
	protected $fields = array(
		'NOMOR'=>1,
		'NOPEN'=>1,
		'KUNJUNGAN'=>1,
		'JENIS'=>1,
		'TANGGAL'=>1,
		'TUJUAN'=>1,
		'TUJUAN_RUANGAN'=>1,
		'DIAGNOSA'=>1, 
		'DOKTER'=>1,
		'KETERANGAN'=>1,
		'OLEH'=>1,
		'STATUS'=>1
	);
}
