<?php
namespace Inventory\V1\Rest\Penyedia;
use DBService\SystemArrayObject;

class PenyediaEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'NAMA'=>1, 'ALAMAT'=>1, 'TELEPON'=>1, 'FAX'=>1, 'TANGGAL'=>1, 'STATUS'=>1);
}
