<?php
namespace Pendaftaran\V1\Rest\SuratRujukanPasien;
use DBService\SystemArrayObject;

class SuratRujukanPasienEntity extends SystemArrayObject
{
	protected $fields = array(
		'ID'=>1,
		'PPK'=>1, 
		'NORM'=>1, 
		'NOMOR'=>1, 
		'TANGGAL'=>1, 
		'DOKTER'=>1,
        'BAGIAN_DOKTER'=>1, 
		'DIAGNOSA_MASUK'=>1, 
		'STATUS'=>1);
}
