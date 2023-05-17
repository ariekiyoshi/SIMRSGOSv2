<?php
namespace Kemkes\db\rujukan;

use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array(
		"NOMOR" => 1
		, "REF" => 1
		, "JENIS_RUJUKAN" => 1
		, "TANGGAL" => 1
		, "FASKES_TUJUAN" => 1
		, "ALASAN" => 1		
		, "ALASAN_LAINNYA" => 1
		, "DIAGNOSA" => 1
		, "NIK_DOKTER" => 1
		, "NAMA_DOKTER" => 1
		, "NIK_PETUGAS" => 1
		, "NAMA_PETUGAS" => 1
	    , "NIK_PETUGAS_PEMBATALAN" => 1
	    , "NAMA_PETUGAS_PEMBATALAN" => 1
	    , "STATUS" => 1
	    , "STATUS_DITERIMA" => 1
	    , "KETERANGAN" => 1
	);
}
