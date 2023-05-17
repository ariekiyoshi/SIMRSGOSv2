<?php
namespace Pendaftaran\V1\Rest\Penjamin;
use DBService\SystemArrayObject;

class PenjaminEntity extends SystemArrayObject
{
	protected $fields = array(
	    'JENIS'=>1, 
	    'NOPEN'=>1, 
	    'NOMOR'=>1, 
	    'KELAS'=>1, 
	    'COB'=>1,
	    'KATARAK'=>1,
	    'NO_SURAT'=>1,
	    'DPJP'=>1,
	    'CATATAN'=>1
	);
}
