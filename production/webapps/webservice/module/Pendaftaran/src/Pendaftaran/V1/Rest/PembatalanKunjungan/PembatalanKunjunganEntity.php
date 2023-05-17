<?php
namespace Pendaftaran\V1\Rest\PembatalanKunjungan;
use DBService\SystemArrayObject;

class PembatalanKunjunganEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'KUNJUNGAN'=>1, 'JENIS'=>1, 'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
