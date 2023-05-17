<?php
namespace Layanan\V1\Rest\JasaTuslahFarmasi;
use DBService\SystemArrayObject;

class JasaTuslahFarmasiEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'KUNJUNGAN'=>1, 'TANGGAL'=>1, 'FARMASI'=>1, 'JUMLAH'=>1, 'HARGA'=>1,'OLEH'=>1, 'STATUS'=>1);
}
