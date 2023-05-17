<?php
namespace General\V1\Rest\KAP;

use DBService\SystemArrayObject;

class KAPEntity extends SystemArrayObject
{
	protected $fields = array('JENIS'=>1, 'NORM'=>1, 'NOMOR'=>1);
}
