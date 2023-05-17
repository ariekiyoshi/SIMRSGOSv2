<?php
namespace Layanan\V1\Rest\HasilPa;
use DBService\SystemArrayObject;

class HasilPaEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'KUNJUNGAN'=>1, 'JENIS_PEMERIKSAAN'=>1, 'LOKASI'=>1, 'DIDAPAT_DENGAN'=>1, 'CAIRAN_FIKSASI'=>1, 'DIAGNOSA_KLINIK'=>1, 'KETERANGAN_KLINIK'=>1, 'MAKROSKOPIK'=>1, 'MIKROSKOPIK'=>1, 
	'KESIMPULAN'=>1,'IMUNO_HISTOKIMIA'=>1,
	'REEVALUASI'=>1,'TANGGAL_IMUNO'=>1,'DOKTER'=>1,'TANGGAL'=>1,'OLEH'=>1,'STATUS'=>1);
}
