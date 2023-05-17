<?php
namespace General\V1\Rest\RuangKamar;
use DBService\SystemArrayObject;

class RuangKamarEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'RUANGAN'=>1, 'KAMAR'=>1, 'KELAS'=>1, 'STATUS'=>1);
}
