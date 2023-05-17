<?php
namespace General\V1\Rest\PPK;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use General\V1\Rest\Referensi\ReferensiService;

class PPKService extends Service
{
	private $referensi;

	protected $references = array(
		'Referensi' => true		
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("ppk", "master"));
		$this->entity = new PPKEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['Referensi']) $this->referensi = new ReferensiService();
		}
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Referensi']) {
					// get referensi jenis
					$referensi = $this->referensi->load(array('JENIS' => 11,'ID' => $entity['JENIS']));
					if(count($referensi) > 0) $entity['REFERENSI']['JENIS'] = $referensi[0];
					
					// get referensi kepemilikan
					$referensi = $this->referensi->load(array('JENIS' => 28,'ID' => $entity['KEPEMILIKAN']));
					if(count($referensi) > 0) $entity['REFERENSI']['KEPEMILIKAN'] = $referensi[0];
					
					// get referensi jenis pelayanan
					$referensi = $this->referensi->load(array('JENIS' => 29,'ID' => $entity['JPK']));
					if(count($referensi) > 0) $entity['REFERENSI']['JPK'] = $referensi[0];
				}
			}
		}
		
		return $data;
	}
	
	public function simpan($data, $isCreated = false, $loaded = true) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity = new PPKEntity();
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
				$select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(isset($params['NAMA'])) {
				if(!System::isNull($params, 'NAMA')) {
					$select->where->like('NAMA', '%'.$params['NAMA'].'%');
					unset($params['NAMA']);
				}
			}
			
			if(isset($params['QUERY'])) {
				if(!System::isNull($params, 'QUERY')) {
					$select->where("(KODE LIKE '".$params['QUERY']."%' OR BPJS = '".$params['QUERY']."' OR NAMA LIKE '%".$params['QUERY']."%')");
					unset($params['QUERY']);
				}
			}
						
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}
