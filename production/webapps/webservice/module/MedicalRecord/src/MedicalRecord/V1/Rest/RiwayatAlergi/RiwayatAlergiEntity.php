<?php
namespace MedicalRecord\V1\Rest\RiwayatAlergi;
use DBService\SystemArrayObject;

class RiwayatAlergiEntity extends SystemArrayObject
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
