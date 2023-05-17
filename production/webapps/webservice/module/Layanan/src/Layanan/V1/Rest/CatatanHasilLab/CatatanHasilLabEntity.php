<?php
namespace Layanan\V1\Rest\CatatanHasilLab;
use DBService\SystemArrayObject;

class CatatanHasilLabEntity extends SystemArrayObject 
{
	protected $fields = array('KUNJUNGAN'=>1, 'TANGGAL'=>1, 'CATATAN'=>1, 'DOKTER'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
