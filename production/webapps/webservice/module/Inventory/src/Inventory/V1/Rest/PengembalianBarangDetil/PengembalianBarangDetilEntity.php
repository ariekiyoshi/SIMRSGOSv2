<?php
namespace Inventory\V1\Rest\PengembalianBarangDetil;
use DBService\SystemArrayObject;

class PengembalianBarangDetilEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'PENGEMBALIAN'=>1,  'BARANG'=>1, 'JUMLAH'=>1, 'STATUS'=>1);
}
