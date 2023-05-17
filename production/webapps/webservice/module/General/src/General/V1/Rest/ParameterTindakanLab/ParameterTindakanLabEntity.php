<?php
namespace General\V1\Rest\ParameterTindakanLab;
use DBService\SystemArrayObject;

class ParameterTindakanLabEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'TINDAKAN'=>1, 'PARAMETER'=>1, 'NILAI_RUJUKAN'=>1, 'SATUAN'=>1, 'INDEKS'=>1, 'TANGGAL'=>1, 'STATUS'=>1 );
}
