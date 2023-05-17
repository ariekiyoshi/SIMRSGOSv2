<?php
namespace Inventory\V1\Rest\TransaksiStokRuangan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use Inventory\V1\Rest\Barang\BarangService;

class TransaksiStokRuanganService extends Service
{
	private $barang;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("transaksi_stok_ruangan", "inventory"));
		$this->entity = new TransaksiStokRuanganEntity();
		
		$this->barang = new BarangService();		
    }
	
	public function load($params = array(), $columns = array('*'), $penjualans = array()) {
		$data = parent::load($params, $columns, $penjualans);
		
		foreach($data as &$entity) {
			
			$barang = $this->barang->load(array('ID' => $entity['BARANG_RUANGAN']));
			if(count($barang) > 0) $entity['REFERENSI']['BARANG'] = $barang[0];
			
		}
		
		return $data;
	}
}
