<?php
namespace General\V1\Rest\StaffRuangan;
use DBService\SystemArrayObject;

class StaffRuanganEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>0, 'TANGGAL'=>1, 'STAFF'=>2, 'RUANGAN'=>3, 'STATUS'=>4);
}
