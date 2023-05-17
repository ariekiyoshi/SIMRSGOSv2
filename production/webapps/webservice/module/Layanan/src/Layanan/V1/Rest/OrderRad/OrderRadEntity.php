<?php
namespace Layanan\V1\Rest\OrderRad;
use DBService\SystemArrayObject;

class OrderRadEntity extends SystemArrayObject
{
	protected $fields = array('NOMOR'=>1, 'KUNJUNGAN'=>1, 'TANGGAL'=>3, 'DOKTER_ASAL'=>4, 'TUJUAN'=>5, 'OLEH'=>6, 'ALASAN'=>7, 'STATUS'=>8);
}
