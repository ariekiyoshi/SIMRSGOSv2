<?php
namespace MedicalRecord\V1\Rest\OperasiDiTindakan;
use DBService\SystemArrayObject;

class OperasiDiTindakanEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'TINDAKAN_MEDIS'=>1, 'STATUS'=>1);
}
