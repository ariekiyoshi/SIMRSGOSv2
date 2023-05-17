<?php
namespace Inventory\V1\Rest\PermintaanDetil;
use DBService\SystemArrayObject;

class PermintaanDetilEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'PERMINTAAN'=>1, 'BARANG'=>1, 'JUMLAH'=>1, 'STATUS'=>1);
}
