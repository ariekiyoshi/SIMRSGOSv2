<?php
namespace MedicalRecord\V1\Rest\TandaVital;
use DBService\SystemArrayObject;
class TandaVitalEntity  extends SystemArrayObject
{
	protected $fields = array(
		"ID"=>1,
		"KUNJUNGAN"=>1,
		"KEADAAN_UMUM"=>1,
		"KESADARAN"=>1,
		"SISTOLIK"=>1,
		"DISTOLIK"=>1,
		"FREKUENSI_NADI"=>1,
		"FREKUENSI_NAFAS"=>1,
		"SUHU"=>1,
		"WAKTU_PEMERIKSAAN"=>1,
		"TANGGAL"=>1,
		"OLEH"=>1,
		"STATUS"=>1
	);
}
