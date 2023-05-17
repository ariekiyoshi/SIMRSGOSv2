<?php
namespace Penjualan\V1\Rest\PenjualanDetil;
use DBService\SystemArrayObject;

class PenjualanDetilEntity extends SystemArrayObject
{
	protected $fields = array(
		'ID'=>1, 
		'PENJUALAN_ID'=>1,  
		'BARANG'=>1,   
		'HARGA_BARANG'=>1, 
		'ATURAN_PAKAI'=>1, 
		'JUMLAH'=>1, 
		'MARGIN'=>1, 
		'STATUS'=>1
	);
}
