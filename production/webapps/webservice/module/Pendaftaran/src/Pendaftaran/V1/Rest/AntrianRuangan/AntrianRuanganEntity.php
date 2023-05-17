<?php
namespace Pendaftaran\V1\Rest\AntrianRuangan;
use DBService\SystemArrayObject;

class AntrianRuanganEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'RUANGAN'=>1, 'TANGGAL'=>1, 'NOMOR'=>1, 'JENIS'=>1, 'REF'=>1, 'STATUS'=>1);
}