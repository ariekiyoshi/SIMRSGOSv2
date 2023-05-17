<?php
namespace MedicalRecord\V1\Rest\Anamnesis;

use DBService\SystemArrayObject;

class AnamnesisEntity extends SystemArrayObject
{
	protected $fields = array(
		"ID"=>1
		, "KUNJUNGAN"=>1
		, "PENDAFTARAN"=>1
		, "DESKRIPSI"=>1
		, "TANGGAL"=>1
		, "OLEH"=>1
		, "STATUS"=>1
	);
}
