<?php
namespace Layanan\V1\Rest\OrderResep;
use DBService\SystemArrayObject;

class OrderResepEntity extends SystemArrayObject
{
	protected $fields = array('NOMOR'=>1, 'KUNJUNGAN'=>1, 'TANGGAL'=>1, 'DOKTER_DPJP'=>1, 'PEMBERI_RESEP'=>1, 'BERAT_BADAN'=>1, 
	'TINGGI_BADAN'=>1, 'DIAGNOSA'=>1, 'ALERGI_OBAT'=>1, 'GANGGUAN_FUNGSI_GINJAL'=>1, 'MENYUSUI'=>1, 'HAMIL'=>1, 'RESEP_PASIEN_PULANG'=>1,
	'TUJUAN'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
