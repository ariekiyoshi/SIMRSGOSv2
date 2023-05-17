<?php
namespace General\V1\Rest\PPK;
use DBService\SystemArrayObject;

class PPKEntity extends SystemArrayObject
{
	protected $fields = array(
		'ID'=>1, 
		'KODE'=>array(
			"NAMA" => "Kode Kemkes"
		), 
		'BPJS'=>array(
			"NAMA" => "Kode BPJS"
		),
		'JENIS'=> array(
			"NAMA" => "Jenis PPK"
		),
		'KEPEMILIKAN'=>1, 
		'JPK'=>1,
        'NAMA'=>1, 
		'KELAS'=>1, 
		'ALAMAT'=>1, 
		'RT'=>1, 
		'RW'=>1, 
		'KODEPOS'=>1, 
		'TELEPON'=>1, 
		'FAX'=>1,
        'WILAYAH'=>array(
			"NAMA" => "Kode Wilayah"
		), 
		'DESWILAYAH'=> array(
			"NAMA" => "Nama Wilayah"
		), 
		'MULAI'=>1, 
		'BERAKHIR'=>1, 
		'TANGGAL'=>1,
		'OLEH'=>1,
		'STATUS'=>1
	);
}
