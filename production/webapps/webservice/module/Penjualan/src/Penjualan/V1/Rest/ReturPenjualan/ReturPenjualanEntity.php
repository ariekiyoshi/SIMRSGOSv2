<?php
namespace Penjualan\V1\Rest\ReturPenjualan;
use DBService\SystemArrayObject;

class ReturPenjualanEntity extends SystemArrayObject
{
	protected $fields = array(
		'ID'=>1
		, 'PENJUALAN_ID' => 1
		, 'PENJUALAN_DETIL_ID' => 1
		, 'BARANG' => 1
		, 'JUMLAH' => 1
		, 'TANGGAL' => 1
		, 'OLEH' => 1
		, 'STATUS' => 1
	);
}
