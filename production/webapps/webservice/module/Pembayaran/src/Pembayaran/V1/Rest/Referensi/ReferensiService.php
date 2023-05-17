<?php
namespace General\V1\Rest\Referensi;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

class ReferensiService extends Service
{
    protected $limit = 3000;
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("referensi", "master"));
		$this->entity = new ReferensiEntity();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : false;
        $jenis = $this->entity->get('JENIS');
		if($id) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("JENIS" => $jenis, "ID" => $id));
		} else {
			if(($jenis == 41) && ($id != 0)){
				
				$this->entity->set('DESKRIPSI', $this->entity->get('DESKRIPSI'));
				$this->entity->set('ID', '');
			}
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
			$id = $this->table->getLastInsertValue();
		}
		$results = $this->load(array('ID'=>$id, 'JENIS'=>$jenis));
		if(count($results) > 0) $data = $results[0];
		return array(
			'success' => true,
			'data' => $data
		);
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
			
			if(!System::isNull($params, 'QUERY')) { 
				$select->where("DESKRIPSI LIKE '".$params['QUERY']."%'");
				unset($params['QUERY']);
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}
