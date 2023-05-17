<?php
namespace General\V1\Rest\GroupLab;
use DBService\SystemArrayObject;

class GroupLabEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'JENIS'=>1, 'DESKRIPSI'=>1, 'STATUS'=>1);
}
