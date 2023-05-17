<?php
namespace General\V1\Rest\TindakanRuangan;
use DBService\SystemArrayObject;

class TindakanRuanganEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>0, 'TANGGAL'=>1, 'TINDAKAN'=>2, 'RUANGAN'=>3, 'STATUS'=>4);
}
