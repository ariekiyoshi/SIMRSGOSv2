<?php
namespace General\V1\Rest\RuanganMutasi;
use DBService\SystemArrayObject;

class RuanganMutasiEntity extends SystemArrayObject
{
	protected $fields = array('RUANGAN'=>0, 'MUTASI'=>1, 'STATUS'=>2);
}
