<?php
namespace General\V1\Rest\RuanganFarmasi;
use DBService\SystemArrayObject;

class RuanganFarmasiEntity extends SystemArrayObject
{
	protected $fields = array('RUANGAN'=>0, 'FARMASI'=>1, 'STATUS'=>2);
}