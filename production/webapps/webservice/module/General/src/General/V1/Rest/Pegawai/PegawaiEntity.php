<?php
namespace General\V1\Rest\Pegawai;
use DBService\SystemArrayObject;

class PegawaiEntity extends SystemArrayObject
{
	protected $fields = array('NIP'=>1, 'NAMA'=>1, 'PANGGILAN'=>1, 'GELAR_DEPAN'=>1, 'GELAR_BELAKANG'=>1,
            'TEMPAT_LAHIR'=>1, 'TANGGAL_LAHIR'=>1, 'AGAMA'=>1, 'JENIS_KELAMIN'=>1, 'PROFESI'=>1, 'SMF'=>1,
            'ALAMAT'=>1, 'RT'=>1, 'RW'=>1, 'KODEPOS'=>1, 'WILAYAH'=>1, 'STATUS'=>1);
}
