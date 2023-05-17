<?php
namespace General\V1\Rest\PerawatRuangan;
use DBService\SystemArrayObject;

class PerawatRuanganEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>0, 'TANGGAL'=>1, 'PERAWAT'=>2, 'RUANGAN'=>3, 'STATUS'=>4);
}
