<?php
namespace General\V1\Rest\Audios;
use DBService\SystemArrayObject;

class AudiosEntity extends SystemArrayObject
{
	protected $fields = array(
	    'ID'=>1
	    , 'JENIS'=>1
	    , 'TEKS'=>1
	    , 'AUDIO'=>1
	    , 'TIPE'=>1
	    , 'DURASI'=>1
	    , 'STATUS'=>1	   
	);
}
