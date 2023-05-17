<?php
namespace General\V1\Rest\DiagnosaMasuk;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use General\V1\Rest\Diagnosa\DiagnosaService;

class DiagnosaMasukService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("diagnosa_masuk", "master"));
		$this->entity = new DiagnosaMasukEntity();
		
		$this->diagnosa = new DiagnosaService();
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {		
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			$results = $this->diagnosa->load(array('CODE' => $entity['ICD']), array('CODE', 'STR'));
			if(count($results) > 0) $entity['REFERENSI']['DIAGNOSA'] = $results[0];
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
									
			if(isset($params['DIAGNOSA'])) {
				if(!System::isNull($params, 'DIAGNOSA')) {
					$select->where->like('DIAGNOSA', '%'.$params['DIAGNOSA'].'%');
					unset($params['DIAGNOSA']);
				}
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : 0;
		$diagnosa = is_numeric($this->entity->get('DIAGNOSA')) ? true : false;
		if($diagnosa) {
			$found = $this->load(array('ID'=>$this->entity->get('DIAGNOSA')));
			if(count($found) > 0) {
				$data = $found[0];
				$this->entity->set('DIAGNOSA', $data['DIAGNOSA']);
			}
		}
		$data = $this->entity->getArrayCopy();
		$found = $this->load(array('ICD'=>$this->entity->get('ICD'), 'DIAGNOSA'=>$this->entity->get('DIAGNOSA')));
		
		if(count($found) > 0) {
			$data = $found[0];
		} else {		
			if($id > 0){
				$this->table->update($data, array("ID" => $id));
			} else {			
				$this->table->insert($data);
				$id = $this->table->getLastInsertValue();	
			}
		}
		
		$data = $found ? $found : $this->load(array("ID" => $id));
		
		return array(
			'success' => true,
			'data' => count($data) > 0 ? $data[0] : null
		);
	}
}
