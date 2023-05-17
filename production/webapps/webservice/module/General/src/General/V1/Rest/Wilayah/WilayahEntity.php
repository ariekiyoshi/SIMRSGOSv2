<?php
namespace General\V1\Rest\Wilayah;
use DBService\SystemArrayObject;

class WilayahEntity extends SystemArrayObject
{
	protected $fields = array('JENIS'=>0, 'ID'=>1, 'DESKRIPSI'=>2, 'KOTA'=>3, 'STATUS'=>4);
}
