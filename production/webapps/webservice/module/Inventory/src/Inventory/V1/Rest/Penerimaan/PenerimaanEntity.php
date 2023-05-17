<?php
namespace Inventory\V1\Rest\Penerimaan;
use DBService\SystemArrayObject;

class PenerimaanEntity extends SystemArrayObject
{
	protected $fields = array('NOMOR'=>1, 'RUANGAN'=>1, 'JENIS'=>1, 'REF'=>1, 'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
