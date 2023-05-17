<?php
namespace General\V1\Rest\TarifFarmasiPerKelas;
use DBService\SystemArrayObject;

class TarifFarmasiPerKelasEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'KELAS'=>1, 'FARMASI'=>1, 'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
