<?php
namespace Pendaftaran\V1\Rest\Reservasi;
use DBService\SystemArrayObject;

class ReservasiEntity extends SystemArrayObject
{
	protected $fields = array('NOMOR'=>1, 'TANGGAL'=>1, 'RUANG_KAMAR_TIDUR'=>1, 'BERAKHIR'=>1, 'ATAS_NAMA'=>1, 'KONTAK_INFO'=>1
		, 'OLEH'=>1, 'STATUS'=>1);
}
