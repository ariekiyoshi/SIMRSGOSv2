<?php
namespace Cetakan\V1\Rest\KarcisPasien;
use DBService\SystemArrayObject;

class KarcisPasienEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'NOPEN'=>1, 'JENIS'=>1, 'TANGGAL'=>1,'OLEH'=>1, 'STATUS'=>1);
}
