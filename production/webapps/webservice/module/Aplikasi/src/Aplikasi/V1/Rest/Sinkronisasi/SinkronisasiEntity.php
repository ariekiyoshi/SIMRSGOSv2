<?php
namespace Aplikasi\V1\Rest\Sinkronisasi;
use DBService\SystemArrayObject;

class SinkronisasiEntity extends SystemArrayObject
{
	protected $fields = array("ID"=>1, "DESKRIPSI"=>1, "TANGGAL_TERAKHIR"=>1, "ID_TERAKHIR"=>1);
}
