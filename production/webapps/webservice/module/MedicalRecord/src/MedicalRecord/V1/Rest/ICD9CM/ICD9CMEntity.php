<?php
namespace MedicalRecord\V1\Rest\ICD9CM;
use DBService\SystemArrayObject;

class ICD9CMEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'NOPEN'=>1, 'KODE'=>1, 'TINDAKAN'=>1, 'INACBG'=>1, 'TANGGAL'=>1,  'OLEH'=>1, 'STATUS'=>1);
}


//class ICD9CMEntity
//{
//}
