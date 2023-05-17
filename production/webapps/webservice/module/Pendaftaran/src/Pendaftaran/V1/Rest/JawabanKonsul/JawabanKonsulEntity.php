<?php
namespace Pendaftaran\V1\Rest\JawabanKonsul;
use DBService\SystemArrayObject;

class JawabanKonsulEntity extends SystemArrayObject
{
	protected $fields = array('NOMOR'=>1, 'TANGGAL'=>1, 'JAWABAN'=>2, 'ANJURAN'=>3, 'DOKTER'=>4, 'OLEH'=>1, 'STATUS'=>5);
}
