<?php
namespace MedicalRecord\V1\Rest\PeminjamanBerkas;
use DBService\SystemArrayObject;

class PeminjamanBerkasEntity extends SystemArrayObject
{
    protected $fields = array(
        "ID"=>1
        , "NORM"=>1
        , "TGL_PEMINJAMAN"=>1
        , "NAMA_PEMINJAM"=>1
        , "NO_IDENTITAS"=>1
        , "TLP"=>1
        , "EMAIL"=>1
        , "ALASAN"=>1
        , "KEMBALI"=>1
        , "TGL_KEMBALI"=>1
        , "OLEH"=>1
        , "STATUS"=>1
    );
}
