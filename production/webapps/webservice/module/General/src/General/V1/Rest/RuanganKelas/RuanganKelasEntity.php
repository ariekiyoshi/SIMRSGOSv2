<?php
namespace General\V1\Rest\RuanganKelas;

use DBService\SystemArrayObject;

class RuanganKelasEntity extends SystemArrayObject
{
	protected $fields = array(
		'ID'=>1, 
		'TANGGAL'=>1,
		'RUANGAN'=>1,
		'KELAS'=>1,
		'STATUS'=>1
	);
}