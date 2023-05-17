<?php
namespace Aplikasi\V1\Rest\PenggunaAkses;
use DBService\SystemArrayObject;

class PenggunaAksesEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'PENGGUNA'=>1, 'GROUP_PENGGUNA_AKSES_MODULE'=>1, 'STATUS'=>1);
}
