<?php
namespace Aplikasi\V1\Rest\Pengguna;
use DBService\SystemArrayObject;

class PenggunaEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'LOGIN'=>1, 'PASSWORD'=>1, 'NAMA'=>1, 'NIP'=>1, 'NIK'=>1, 'JENIS'=>1, 'STATUS'=>1);
}
