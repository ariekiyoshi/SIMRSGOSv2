<?php
namespace Layanan\V1\Rest\HasilLab;
use DBService\SystemArrayObject;

class HasilLabEntity extends SystemArrayObject
{
	protected $fields = array(
	    'ID' => 1
	    , 'TINDAKAN_MEDIS' => 1
	    , 'PARAMETER_TINDAKAN' => 1
	    , 'TANGGAL' => 1
	    , 'HASIL' => 1
	    , 'NILAI_NORMAL' => 1
	    , 'SATUAN' => 1
	    , 'KETERANGAN' => 1
	    , 'OLEH' => 1
	    , 'STATUS' => 1	    
	);
}
