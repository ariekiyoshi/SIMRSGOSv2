<?php
namespace BPJService\db\klaim;

use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
    protected $fields = array(
        "noSEP" => 1
        , "noFPK" => 1
        , "peserta_noKartu" => 1
        , "peserta_nama" => 1
        , "peserta_noMR" => 1
        , "kelasRawat" => 1
        , "poli" => 1
        , "tglSep" => 1
        , "tglPulang" => 1
        , "inacbg_kode" => 1
        , "inacbg_nama" => 1
        , "biaya_byPengajuan" => 1
        , "biaya_bySetujui" => 1
        , "biaya_byTarifGruper" => 1
        , "biaya_byTarifRS" => 1
        , "biaya_byTopup" => 1
        , "status" => 1
        , "jenisPelayanan" => 1
        , "status_id" => 1
    );
}
