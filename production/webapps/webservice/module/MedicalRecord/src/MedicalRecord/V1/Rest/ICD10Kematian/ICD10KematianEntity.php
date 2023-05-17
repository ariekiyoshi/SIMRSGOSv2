<?php
namespace MedicalRecord\V1\Rest\ICD10Kematian;
use DBService\SystemArrayObject;

class ICD10KematianEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'NOPEN'=>1, 'KODE'=>1, 'DIAGNOSA'=>1, 'UTAMA'=>1, 'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
