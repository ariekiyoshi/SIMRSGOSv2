<?php
namespace Inventory\V1\Rest\StokBarangMinimum;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use Inventory\V1\Rest\Barang\BarangService;

class StokBarangMinimumService extends Service
{
	private $barang;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("barang_ruangan", "inventory"));
		$this->entity = new StokBarangMinimumEntity();
		$this->limit = 1000;
		$this->barang = new BarangService();		
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		foreach($data as &$entity) {
			$barang = $this->barang->load(array('ID' => $entity['BARANG']));
			if(count($barang) > 0) $entity['REFERENSI']['BARANG'] = $barang[0];
		}
				
		return $data;
	}
	
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		//array_push($columns, "STOKTERSEDIA" => new \Zend\Db\Sql\Expression('SUM(barang_ruangan.STOK)'));
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				$select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			$select->join(array('c' => new TableIdentifier("barang", "inventory")), "barang_ruangan.BARANG = c.ID", array());
			
			if(isset($params['QUERY'])) {
				if(!System::isNull($params, 'QUERY')) {
					$select->where("(c.NAMA LIKE '%".$params['QUERY']."%')");
					unset($params['QUERY']);
				}
			}
			
			$select->group("barang_ruangan.BARANG");
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}
