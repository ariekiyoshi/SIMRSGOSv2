<?php
namespace INACBGService\V1\Rest\Map;
use DBService\SystemArrayObject;

class MapEntity extends SystemArrayObject
{
	protected $fields = array('JENIS'=>1, 'INACBG'=>1, 'SIMRS'=>1);
}
