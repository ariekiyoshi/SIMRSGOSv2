<?php
namespace INACBGService\V1\Rest\HasilGrouping;
use DBService\SystemArrayObject;

class HasilGroupingEntity extends SystemArrayObject
{
	protected $fields = array('NOPEN'=>1, 'NOSEP'=>1, 'CODECBG'=>1, 'TARIFCBG'=>1,'TARIFSP'=>1, 'TARIFSR'=>1, 'TARIFSI'=>1, 'TARIFSD'=>1,
	'TARIFSA'=>1, 'TARIFSC'=>1, 'TARIFKLS1'=>1, 'TARIFKLS2'=>1, 'TARIFKLS3'=>1,'TOTALTARIF'=>1, 'TARIFRS'=>1, 'UNUSR'=>1, 'UNUSI'=>1, 'UNUSP'=>1, 'UNUSD'=>1,
	'UNUSA'=>1, 'UNUSC'=>1, 'TANGGAL'=>1, 'USER'=>1, 'STATUS'=>1, 'TIPE'=>1, 'DC_KEMKES'=>1, 'DC_BPJS'=>1, 'RESPONSE'=>1
	);
}
