<?php
namespace Pendaftaran\V1\Rest\Konsul;
use DBService\SystemArrayObject;

class KonsulEntity extends SystemArrayObject
{
	protected $fields = array('NOMOR'=>1, 'KUNJUNGAN'=>1, 'TANGGAL'=>3, 'DOKTER_ASAL'=>4, 'ALASAN'=>5, 'PERMINTAAN_TINDAKAN'=>6, 'TUJUAN'=>7, 'OLEH'=>8, 'STATUS'=>9);
}
