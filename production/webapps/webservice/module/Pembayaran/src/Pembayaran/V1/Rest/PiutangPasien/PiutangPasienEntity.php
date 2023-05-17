<?php
namespace Pembayaran\V1\Rest\PiutangPasien;
use DBService\SystemArrayObject;

class PiutangPasienEntity extends SystemArrayObject
{
	protected $fields = array('TAGIHAN'=>1, 'NAMA'=>1, 'SHDK'=>1, 'NO_KARTU_IDENTITAS'=>1, 'JENIS_KARTU'=>1, 'ALAMAT_KARTU'=>1, 'ALAMAT_TEMPAT_TINGGAL'=>1, 
	'TELEPON'=>1,  'ALASAN'=>1, 'TANGGAL'=>1, 'TOTAL'=>1, 'JENIS_PEMBAYARAN'=>1, 'LAMA_ANGSURAN'=>1, 'BESAR_ANGSURAN'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
