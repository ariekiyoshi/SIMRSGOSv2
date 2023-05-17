<?php
namespace BPJService\db\kunjungan;

use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
    protected $fields = array(
        "noKartu" => 1
        , "noSEP" => 1
        , "tglSEP" => 1
        , "tglRujukan" => 1
        , "asalRujukan" => 1
        , "noRujukan" => 1
        , "ppkRujukan" => 1
        , "ppkPelayanan" => 1
        , "jenisPelayanan" => 1
        , "catatan" => 1
        , "diagAwal" => 1
        , "poliTujuan" => 1
        , "eksekutif" => 1
        , "klsRawat" => 1
        , "cob" => 1
        , "katarak" => 1
        , "noSuratSKDP" => 1
        , "dpjpSKDP" => 1
        , "lakaLantas" => 1
        , "penjamin" => 1
        , "tglKejadian" => 1
        , "suplesi" => 1
        , "noSuplesi" => 1
        , "lokasiLaka" => 1
        , "propinsi" => 1
        , "kabupaten" => 1
        , "kecamatan" => 1
        , "noTelp" => 1
        , "user" => 1
        , "cetak" => 1
        , "jmlCetak" => 1
        , "ip" => 1
        , "noTrans" => 1
        , "errMsgMapTrx" => 1
        , "manualUptNoTrans" => 1
        , "tglPlg" => 1
        , "errMsgUptTglPlg" => 1
        , "status" => 1
        , "user_batal" => 1
        , "batalSEP" => 1
        , "errMsgBatalSEP" => 1
    );
}
