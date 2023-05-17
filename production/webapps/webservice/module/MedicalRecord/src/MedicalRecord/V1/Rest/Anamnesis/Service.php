<?php
namespace MedicalRecord\V1\Rest\Anamnesis;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService {
	
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("anamnesis", "medicalrecord"));
		$this->entity = new AnamnesisEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
	}
	
	public function simpan($data, $isCreated = false, $loaded = true) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity = new AnamnesisEntity();
		$this->entity->exchangeArray($data);
		
		if($isCreated) {
			$this->table->insert($this->entity->getArrayCopy());
			$id = $this->table->getLastInsertValue();
		} else {
			$id = $this->entity->get("ID");
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		}
		
		if($loaded) return $this->load(array("ID" => $id));
		return $id;
	}
	
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				if(!$isCount) $select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(!System::isNull($params, 'STATUS')) {
				$status = $params['STATUS'];
				$params['anamnesis.STATUS'] = $status;
				unset($params['STATUS']);
			}
			
			if(!System::isNull($params, 'HISTORY')) {
				unset($params['HISTORY']);
			
				$select->join(array('k'=>new TableIdentifier('kunjungan', 'pendaftaran')), 'k.NOMOR = anamnesis.KUNJUNGAN', array());
				$select->join(array('p'=>new TableIdentifier('pendaftaran', 'pendaftaran')), 'p.NOMOR = k.NOPEN', array());
				
				if(!System::isNull($params, 'NORM')) {
					$norm = $params['NORM'];
					$params['p.NORM'] = $norm;
					unset($params['NORM']);
				}
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}