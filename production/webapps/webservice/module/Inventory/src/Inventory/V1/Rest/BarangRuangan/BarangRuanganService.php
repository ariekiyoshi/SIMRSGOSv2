<?php
namespace Inventory\V1\Rest\BarangRuangan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use Inventory\V1\Rest\Barang\BarangService;

class BarangRuanganService extends Service
{
	private $barang;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("barang_ruangan", "inventory"));
		$this->entity = new BarangRuanganEntity();
		
		$this->barang = new BarangService();		
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = (int) $this->entity->get('ID');
		
		if($id > 0){
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
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
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				$select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(!System::isNull($params, 'STATUS')) {
				$status = $params['STATUS'];
				$params['barang_ruangan.STATUS'] = $status;
				unset($params['STATUS']);
			}
			
			
			if(isset($params['KATEGORI_BARANG'])) {
				if(!System::isNull($params, 'KATEGORI_BARANG')) {
					$select->join(array('d' => new TableIdentifier("barang", "inventory")), "barang_ruangan.BARANG = d.ID", array());
					$select->where("(d.KATEGORI LIKE '".$params['KATEGORI_BARANG']."%')");
					
					unset($params['KATEGORI_BARANG']);
					
				}
			}
			if(isset($params['CEKSTOK'])) {
				if(!System::isNull($params, 'CEKSTOK')) { 
					$select->where("STOK != '0'");
					unset($params['CEKSTOK']);
				}
			}
			if(isset($params['BARANG'])) {
				if(!System::isNull($params, 'BARANG')) { 
					$select->where("BARANG = '".$params['BARANG']."'");
					unset($params['BARANG']);
				}
			}
			if(isset($params['RUANGAN'])) {
				if(!System::isNull($params, 'RUANGAN')) { 
					$select->where("RUANGAN = '".$params['RUANGAN']."'");
					unset($params['RUANGAN']);
				}
			}
			if(isset($params['QUERY'])) {
				if(!System::isNull($params, 'QUERY')) {
					$select->join(array('c' => new TableIdentifier("barang", "inventory")), "barang_ruangan.BARANG = c.ID", array());
					$select->where("(c.NAMA LIKE '%".$params['QUERY']."%')");
					
					unset($params['QUERY']);
				}
			}
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}
