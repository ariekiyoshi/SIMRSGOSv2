<?php
namespace Aplikasi\V1\Rest\PenggunaAksesRuangan;
use DBService\SystemArrayObject;

class PenggunaAksesRuanganEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'TANGGAL'=>1, 'PENGGUNA'=>1, 'RUANGAN'=>1, 'DIBERIKAN_OLEH'=>1, 'STATUS'=>3);
}
