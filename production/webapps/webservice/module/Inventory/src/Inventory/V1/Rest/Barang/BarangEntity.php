<?php
namespace Inventory\V1\Rest\Barang;
use DBService\SystemArrayObject;

class BarangEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'NAMA'=>1, 'KATEGORI'=>1, 'SATUAN'=>1, 'MERK'=>1, 'PENYEDIA'=>1, 'GENERIK'=>1, 'JENIS_GENERIK'=>1, 'FORMULARIUM'=>1, 'STOK'=>1
							, 'HARGA_BELI'=>1, 'PPN'=>1, 'HARGA_JUAL'=>1, 'MASA_BERLAKU'=>1, 'JENIS_PENGGUNAAN_OBAT'=>1, 'TANGGAL'=>1, 'OLEH'=>1, 'STATUS'=>1);
}