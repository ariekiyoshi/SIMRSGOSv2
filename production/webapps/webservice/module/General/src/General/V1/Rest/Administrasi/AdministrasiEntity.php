<?php
namespace General\V1\Rest\Administrasi;
use DBService\SystemArrayObject;

class AdministrasiEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'NAMA'=>1,'STATUS'=>1);
}
