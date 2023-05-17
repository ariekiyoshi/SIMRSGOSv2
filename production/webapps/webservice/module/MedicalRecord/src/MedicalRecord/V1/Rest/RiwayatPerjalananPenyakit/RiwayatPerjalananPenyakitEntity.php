<?php
namespace MedicalRecord\V1\Rest\RiwayatPerjalananPenyakit;

use DBService\SystemArrayObject;

class RiwayatPerjalananPenyakitEntity extends SystemArrayObject
{
	protected $fields = array(
		"ID"=>1
		, "KUNJUNGAN"=>1
		, "DESKRIPSI"=>1
		, "TANGGAL"=>1
		, "OLEH"=>1
		, "STATUS"=>1
	);
}
