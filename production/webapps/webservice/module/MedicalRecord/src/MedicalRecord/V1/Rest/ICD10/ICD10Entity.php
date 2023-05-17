<?php
namespace MedicalRecord\V1\Rest\ICD10;
use DBService\SystemArrayObject;

class ICD10Entity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'NOPEN'=>1, 'KODE'=>1, 'DIAGNOSA'=>1, 'UTAMA'=>1, 'INACBG'=>1, 'BARU'=>1,'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
