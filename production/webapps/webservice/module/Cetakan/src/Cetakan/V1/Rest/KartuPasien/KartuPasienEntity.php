<?php
namespace Cetakan\V1\Rest\KartuPasien;
use DBService\SystemArrayObject;

class KartuPasienEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'NORM'=>1, 'JENIS'=>1, 'TANGGAL'=>1,'OLEH'=>1, 'STATUS'=>1);
}
