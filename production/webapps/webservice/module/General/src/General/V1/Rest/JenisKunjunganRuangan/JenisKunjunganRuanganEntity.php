<?php
namespace General\V1\Rest\JenisKunjunganRuangan;
use DBService\SystemArrayObject;

class JenisKunjunganRuanganEntity extends SystemArrayObject
{
	protected $fields = array('JENIS_KUNJUNGAN'=>1, 'RUANGAN'=>1, 'STATUS'=>1);
}
