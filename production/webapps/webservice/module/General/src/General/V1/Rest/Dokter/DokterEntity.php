<?php
namespace General\V1\Rest\Dokter;
use DBService\SystemArrayObject;

class DokterEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'NIP'=>1, 'STATUS'=>2);
}
