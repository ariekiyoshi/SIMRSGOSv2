<?php
namespace Layanan\V1\Rest\ReturFarmasi;
use DBService\SystemArrayObject;

class ReturFarmasiEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'ID_FARMASI'=>1, 'JUMLAH'=>1, 'TANGGAL'=>1, 'OLEH'=>1);
}
