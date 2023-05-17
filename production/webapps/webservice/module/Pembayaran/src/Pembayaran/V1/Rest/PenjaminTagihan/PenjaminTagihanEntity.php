<?php
namespace Pembayaran\V1\Rest\PenjaminTagihan;

use DBService\SystemArrayObject;

class PenjaminTagihanEntity extends SystemArrayObject
{
    protected $fields = array(
        "TAGIHAN"=>1
        , "PENJAMIN"=>1
        , "KE"=>1
        , "TOTAL"=>1
        , "TOTAL_NAIK_KELAS"=>1
        , "NAIK_KELAS"=>1
        , "NAIK_KELAS_VIP"=>1
        , "NAIK_DIATAS_VIP"=>1
        , "KELAS"=>1
        , "LAMA_NAIK"=>1
        , "TOTAL_TAGIHAN_HAK"=>1
        , "TARIF_INACBG_KELAS1"=>1
        , "SUBSIDI_TAGIHAN"=>1
        , "SELISIH_MINIMAL"=>1
        , "KELAS_KLAIM"=>1
        , "PERSENTASE_TARIF_INACBG_KELAS1"=>1
    );
}
