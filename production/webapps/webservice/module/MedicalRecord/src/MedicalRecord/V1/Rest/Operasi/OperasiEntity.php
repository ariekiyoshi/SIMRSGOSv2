<?php
namespace MedicalRecord\V1\Rest\Operasi;
use DBService\SystemArrayObject;

class OperasiEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'KUNJUNGAN' => 1, 'DOKTER'=>1, 'ASISTEN_DOKTER'=>1, 'ANASTESI'=>1,'ASISTEN_ANASTESI'=>1, 'JENIS_ANASTESI'=>1,
							'GOLONGAN_OPERASI'=>1, 'PRA_BEDAH'=>1, 'PASCA_BEDAH'=>1, 'INDIKASI'=>1, 'NAMA_OPERASI'=>1, 'PA'=>1, 'JARINGAN_DIEKSISI'=>1,
							'TANGGAL'=>1, 'WAKTU_MULAI'=>1, 'WAKTU_SELESAI'=>1, 'DURASI'=>1, 'KOMPLIKASI'=>1, 'PERDARAHAN'=>1, 'RUANGAN_PASCA_OPERASI'=>1,
							'LAPORAN_OPERASI'=>1, 'DIBUAT_TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1
							);
}
