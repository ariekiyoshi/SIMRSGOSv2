<?php
namespace Layanan\V1\Rest\OrderDetilResep;
use DBService\SystemArrayObject;

class OrderDetilResepEntity extends SystemArrayObject
{
	protected $fields = array('ORDER_ID'=>1, 'FARMASI'=>1, 'JUMLAH'=>1, 'ATURAN_PAKAI'=>1, 'KETERANGAN'=>1, 'RACIKAN'=>1, 'GROUP_RACIKAN'=>1,
		'PETUNJUK_RACIKAN'=>1, 'JUMLAH_RACIKAN'=>1, 'REF'=>1);
}
