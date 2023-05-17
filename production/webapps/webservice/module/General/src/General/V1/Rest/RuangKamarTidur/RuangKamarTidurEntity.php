<?php
namespace General\V1\Rest\RuangKamarTidur;
use DBService\SystemArrayObject;

class RuangKamarTidurEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'RUANG_KAMAR'=>1, 'TEMPAT_TIDUR'=>1, 'STATUS'=>1);
}
