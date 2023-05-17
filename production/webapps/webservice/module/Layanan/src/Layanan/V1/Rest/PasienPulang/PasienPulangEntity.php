<?php
namespace Layanan\V1\Rest\PasienPulang;
use DBService\SystemArrayObject;

class PasienPulangEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'KUNJUNGAN'=>1, 'NOPEN'=>1, 'TANGGAL'=>1, 'CARA'=>1, 'KEADAAN'=>1, 'DIAGNOSA'=>1, 'DOKTER'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
