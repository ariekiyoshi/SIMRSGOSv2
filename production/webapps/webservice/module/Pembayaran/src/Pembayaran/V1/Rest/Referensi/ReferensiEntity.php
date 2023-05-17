<?php
namespace General\V1\Rest\Referensi;
use DBService\SystemArrayObject;

class ReferensiEntity extends SystemArrayObject
{
	protected $fields = array(
        'JENIS' => 1
        , 'ID' => 1
	    , 'DESKRIPSI' => 1
	    , 'REF_ID' => 1
	    , 'STATUS' => 1	   
	);	
}
