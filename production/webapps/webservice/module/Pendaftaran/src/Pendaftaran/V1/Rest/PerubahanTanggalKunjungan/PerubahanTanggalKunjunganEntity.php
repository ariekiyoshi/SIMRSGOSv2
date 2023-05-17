<?php
namespace Pendaftaran\V1\Rest\PerubahanTanggalKunjungan;
use DBService\SystemArrayObject;

class PerubahanTanggalKunjunganEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'KUNJUNGAN'=>1, 'TANGGAL_LAMA'=>1, 'TANGGAL_BARU'=>1, 'JENIS'=>1, 'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
