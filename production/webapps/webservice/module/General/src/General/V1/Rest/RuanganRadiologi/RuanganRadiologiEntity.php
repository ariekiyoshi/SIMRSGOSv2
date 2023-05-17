<?php
namespace General\V1\Rest\RuanganRadiologi;
use DBService\SystemArrayObject;

class RuanganRadiologiEntity extends SystemArrayObject
{
	protected $fields = array('RUANGAN'=>0, 'RADIOLOGI'=>1, 'STATUS'=>2);
}