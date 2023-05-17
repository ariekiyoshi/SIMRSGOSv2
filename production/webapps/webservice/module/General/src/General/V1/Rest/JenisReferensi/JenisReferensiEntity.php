<?php
namespace General\V1\Rest\JenisReferensi;
use DBService\SystemArrayObject;

class JenisReferensiEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'DESKRIPSI'=>1, 'SINGKATAN'=>1);
}
