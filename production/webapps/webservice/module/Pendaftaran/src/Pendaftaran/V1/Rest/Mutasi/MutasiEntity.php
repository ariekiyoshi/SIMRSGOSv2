<?php
namespace Pendaftaran\V1\Rest\Mutasi;
use DBService\SystemArrayObject;

class MutasiEntity extends SystemArrayObject
{
    protected $fields = array(
        'NOMOR'=>1,
        'KUNJUNGAN'=>1,
        'TANGGAL'=>1,
        'TUJUAN'=>1,
        'RESERVASI'=>1,
        'IKUT_IBU' => 1,
        'KUNJUNGAN_IBU' => 1,
        'OLEH'=>1,
        'STATUS'=>1
    );
}
