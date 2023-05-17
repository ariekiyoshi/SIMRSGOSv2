<?php
namespace Pembayaran\V1\Rest\Tagihan;
use DBService\SystemArrayObject;

class TagihanEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'REF'=>1, 'TANGGAL'=>1, 'JENIS'=>1, 'TOTAL'=>1
	, "PROSEDUR_NON_BEDAH" => 1
	, "PROSEDUR_BEDAH" => 1
	, "KONSULTASI" => 1
	, "TENAGA_AHLI" => 1
	, "KEPERAWATAN" => 1
	, "PENUNJANG" => 1
	, "RADIOLOGI" => 1
	, "LABORATORIUM" => 1
	, "BANK_DARAH" => 1
	, "REHAB_MEDIK" => 1
	, "AKOMODASI" => 1
	, "AKOMODASI_INTENSIF" => 1
	, "OBAT" => 1
	, "OBAT_KRONIS" => 1
	, "OBAT_KEMOTERAPI" => 1
	, "ALKES" => 1
	, "BMHP" => 1
	, "SEWA_ALAT" => 1
	, "RAWAT_INTENSIF" => 1
	, "LAMA_RAWAT_INTENSIF" => 1
	, "LAMA_PENGGUNAAN_VENTILATOR" => 1
	, 'OLEH'=>1, 'STATUS'=>1);
}
