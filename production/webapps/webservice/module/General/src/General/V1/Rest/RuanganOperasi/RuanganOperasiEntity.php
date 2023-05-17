<?php
namespace General\V1\Rest\RuanganOperasi;
use DBService\SystemArrayObject;

class RuanganOperasiEntity extends SystemArrayObject
{
	protected $fields = array('RUANGAN'=>0, 'OPERASI'=>1, 'STATUS'=>2);
}