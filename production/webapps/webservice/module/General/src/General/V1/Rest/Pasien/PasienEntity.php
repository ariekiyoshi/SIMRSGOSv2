<?php
namespace General\V1\Rest\Pasien;
use DBService\SystemArrayObject;

class PasienEntity extends SystemArrayObject
{
    protected $fields = array(
        'NORM'=>1
        , 'NAMA'=>1
        , 'PANGGILAN'=>1
        , 'GELAR_DEPAN'=>1
        , 'GELAR_BELAKANG'=>1
        , 'TEMPAT_LAHIR'=>1
        , 'TANGGAL_LAHIR'=>1
        , 'JENIS_KELAMIN'=>1
        , 'ALAMAT'=>1
        , 'RT'=>1
        , 'RW'=>1
        , 'KODEPOS'=>1
        , 'WILAYAH'=>1
        , 'AGAMA'=>1
        , 'PENDIDIKAN'=>1
        , 'PEKERJAAN'=>1
        , 'STATUS_PERKAWINAN'=>1
        , 'GOLONGAN_DARAH'=>1
        , 'KEWARGANEGARAAN'=>1
        , 'SUKU'=>1
        , 'TANGGAL'=>1
        , 'OLEH'=>1
        , 'STATUS'=>1
    );
		
	public function getNorm() {
		return isset($this->storage['NORM']) ? $this->storage['NORM'] : 0;
	}
}
