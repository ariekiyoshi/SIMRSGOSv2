<?php
namespace General\V1\Rest\PenjaminRuangan;

use DBService\SystemArrayObject;

class PenjaminRuanganEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'PENJAMIN'=>1, 'RUANGAN_PENJAMIN'=>1, 'RUANGAN_RS'=>1, 'STATUS'=>1);
}
