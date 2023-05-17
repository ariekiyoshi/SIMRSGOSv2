<?php
namespace Inventory\V1\Rest\Permintaan;
use DBService\SystemArrayObject;

class PermintaanEntity extends SystemArrayObject
{
	protected $fields = array('NOMOR'=>1, 'ASAL'=>1, 'TUJUAN'=>1, 'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1, 'TERIMA'=>1);
}
