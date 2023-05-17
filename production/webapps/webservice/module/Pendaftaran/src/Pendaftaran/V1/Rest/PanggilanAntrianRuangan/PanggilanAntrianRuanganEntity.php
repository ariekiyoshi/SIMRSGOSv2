<?php
namespace Pendaftaran\V1\Rest\PanggilanAntrianRuangan;
use DBService\SystemArrayObject;

class PanggilanAntrianRuanganEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'ANTRIAN_RUANGAN'=>1, 'STATUS'=>1);
}
