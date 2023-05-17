<?php
namespace Aplikasi\V1\Rest\PenggunaAksesLog;

use DBService\SystemArrayObject;

class PenggunaAksesLogEntity extends SystemArrayObject
{
	protected $fields = array("ID"=>1, "TANGGAL"=>1, "PENGGUNA"=>1, "AKSI"=>1, "OBJEK"=>1, "REF"=>1, "SEBELUM"=>1, "SESUDAH"=>1);
}