<?php
namespace General\V1\Rest\Negara;
use DBService\SystemArrayObject;

class NegaraEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>0, 'DESKRIPSI'=>1, 'SINGKATAN'=>2, 'STATUS'=>3);
}
