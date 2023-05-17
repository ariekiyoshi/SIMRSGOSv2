<?php
namespace General\V1\Rest\RuanganLaboratorium;
use DBService\SystemArrayObject;

class RuanganLaboratoriumEntity extends SystemArrayObject
{
	protected $fields = array('RUANGAN'=>0, 'LABORATORIUM'=>1, 'STATUS'=>2);
}
