<?php
namespace Pembayaran\V1\Rest\GabungTagihan;
use DBService\SystemArrayObject;

class GabungTagihanEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'DARI'=>1, 'KE'=>1, 'TANGGAL'=>1, 'OLEH'=>1,  'STATUS'=>1);
}
