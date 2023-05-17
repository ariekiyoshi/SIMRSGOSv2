<?php
namespace General\V1\Rest\Tindakan;
use DBService\SystemArrayObject;

class TindakanEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>0, 'JENIS'=>1, 'NAMA'=>1, 'STATUS'=>2);
}

