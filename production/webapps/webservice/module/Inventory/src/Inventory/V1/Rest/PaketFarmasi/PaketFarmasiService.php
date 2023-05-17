<?php
namespace Inventory\V1\Rest\PaketFarmasi;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use Inventory\V1\Rest\Barang\BarangService;
use General\V1\Rest\TindakanPaket\TindakanPaketService;

class PaketFarmasiService extends Service
{
	private $barang;
    private $tindakan;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("barang_paket_tindakan", "inventory"));
		$this->entity = new PaketFarmasiEntity();
		
		$this->barang = new BarangService();
        $this->tindakan = new TindakanPaketService();
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
            $tindakan = $this->tindakan->load(array('ID' => $entity['TINDAKAN']));
			if(count($tindakan) > 0) $entity['REFERENSI']['TINDAKAN'] = $tindakan[0];
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
			/*
			if(!System::isNull($params, 'QUERY')) { 
				$select->where("NAMA LIKE '%".$params['QUERY']."%'");
				unset($params['QUERY']);
			}
			*/
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}
