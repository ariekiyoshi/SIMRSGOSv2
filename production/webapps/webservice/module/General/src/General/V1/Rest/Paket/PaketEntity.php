<?php
namespace General\V1\Rest\Paket;
use DBService\SystemArrayObject;

class PaketEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'NAMA'=>1, 'TANGGAL'=>1, 'KELAS'=>1, 'LAMA'=>1, 'UNTUK_KUNJUNGAN'=>1, 'TARIF'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
