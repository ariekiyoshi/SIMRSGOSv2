<?php
namespace Layanan\V1\Rest\Farmasi;
use DBService\SystemArrayObject;

class FarmasiEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'KUNJUNGAN'=>1, 'FARMASI'=>1, 'TANGGAL'=>1, 'JUMLAH'=>1, 'ATURAN_PAKAI'=>1, 'KETERANGAN'=>1,
	'RACIKAN'=>1, 'GROUP_RACIKAN'=>1,'ALASAN_TIDAK_TERLAYANI'=>1,'HARI'=>1,'OLEH'=>1, 'STATUS'=>1, 'REF'=>1);
}
