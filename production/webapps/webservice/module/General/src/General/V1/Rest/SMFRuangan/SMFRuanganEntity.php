<?php
namespace General\V1\Rest\SMFRuangan;
use DBService\SystemArrayObject;

class SMFRuanganEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>0, 'TANGGAL'=>1, 'SMF'=>2, 'RUANGAN'=>3, 'STATUS'=>4);
}

