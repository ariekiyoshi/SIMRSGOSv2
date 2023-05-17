<?php
namespace General\V1\Rest\Referensi;
use DBService\SystemArrayObject;

class ReferensiEntity extends SystemArrayObject
{
	protected $fields = array('JENIS'=>0, 'ID'=>1, 'DESKRIPSI'=>2, 'STATUS'=>3);
}
