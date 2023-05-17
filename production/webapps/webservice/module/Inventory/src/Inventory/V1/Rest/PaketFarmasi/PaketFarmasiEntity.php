<?php
namespace Inventory\V1\Rest\PaketFarmasi;
use DBService\SystemArrayObject;

class PaketFarmasiEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'TINDAKAN'=>1, 'BARANG'=>1, 'JUMLAH'=>1, 'TANGGAL'=>1, 'STATUS'=>1, 'UPDATE_TIME'=>1);	
}
