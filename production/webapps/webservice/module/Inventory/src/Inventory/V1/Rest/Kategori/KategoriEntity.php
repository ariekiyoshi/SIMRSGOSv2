<?php
namespace Inventory\V1\Rest\Kategori;
use DBService\SystemArrayObject;

class KategoriEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'NAMA'=>1, 'JENIS'=>1, 'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
