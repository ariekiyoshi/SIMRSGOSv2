<?php
namespace DukcapilService\db\penduduk;

use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array(
		"NIK" => 1
		, "NO_KK" => 1
		, "NAMA_LGKP" => 1
		, "JENIS_KLMIN" => 1
		, "TMPT_LHR" => 1
		, "TGL_LHR" => 1
		, "GOL_DARAH" => 1
		, "AGAMA" => 1
		, "STATUS_KAWIN" => 1
		, "STAT_HBKEL" => 1
		, "PNYDNG_CCT" => 1
	    , "PDDK_AKH" => 1
	    , "JENIS_PKRJN" => 1
	    , "NO_AKTA_LHR" => 1
	    , "NAMA_LGKP_IBU" => 1
	    , "NAMA_LGKP_AYAH" => 1
	    , "EKTP_STATUS" => 1
	    , "EKTP_CREATED" => 1
	);
}
