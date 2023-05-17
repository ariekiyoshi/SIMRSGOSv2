<?php
namespace Aplikasi\V1\Rest\PenggunaLog;

use DBService\SystemArrayObject;

class PenggunaLogEntity extends SystemArrayObject
{
	protected $fields = array("ID"=>1, "PENGGUNA"=>1, "TANGGAL_AKSES"=>1, "LOKASI"=>1, "AGENT"=>1);
}