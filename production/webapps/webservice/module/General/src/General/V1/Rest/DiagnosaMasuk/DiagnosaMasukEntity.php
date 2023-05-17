<?php
namespace General\V1\Rest\DiagnosaMasuk;
use DBService\SystemArrayObject;

class DiagnosaMasukEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>0, 'ICD'=>1, 'DIAGNOSA'=>2);
}
