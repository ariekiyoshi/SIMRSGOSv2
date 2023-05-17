<?php
namespace General\V1\Rest\TindakanRuangan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

use General\V1\Rest\Tindakan\TindakanService;

class TindakanRuanganService extends Service
{
	private $tindakan;
	
	protected $references = array(
		'Tindakan' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("tindakan_ruangan", "master"));
		$this->entity = new TindakanRuanganEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			if($this->references['Tindakan']) $this->tindakan = new TindakanService(true, array('TindakanRuangan' => false));
		}			
    }    
		
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);			
		
		$id = $this->entity->get('ID');
		if($id > 0){
			$this->table->update($this->entity->getArrayCopy(), array('ID' => $id));
		} else {
			$rows = $this->load(array("RUANGAN" => $data['RUANGAN'], 'TINDAKAN' => $data['TINDAKAN'], 'STATUS' => 1));
        
			if(count($rows) > 0) {
				$id = $rows[0]['ID'];
				$this->table->update($this->entity->getArrayCopy(), array('ID' => $id));
			} else {
				$this->table->insert($this->entity->getArrayCopy());
				$id = $this->table->getLastInsertValue();
			}
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('ID' => $id))
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Tindakan']) {
					$tindakan = $this->tindakan->load(array('ID' => $entity['TINDAKAN']));
					if(count($tindakan) > 0) $entity['REFERENSI']['TINDAKAN'] = $tindakan[0];
				}
			}
		}
		
		return $data;
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
			
			if(!System::isNull($params, 'ID')) {
				$params['tindakan_ruangan.ID'] = $params['ID'];
				unset($params['ID']);
			}
			if(!System::isNull($params, 'STATUS')) {
				$params['tindakan_ruangan.STATUS'] = $params['STATUS'];
				unset($params['STATUS']);
			}
			
			$select->join(
				array('t' => new TableIdentifier('tindakan', 'master')),
				't.ID = TINDAKAN',
				array('TINDAKAN_DESKRIPSI' => 'NAMA')
			);
			
			if(isset($params['QUERY'])) {
				if(!System::isNull($params, 'QUERY')) {
					$select->where->like('t.NAMA', '%'.$params['QUERY'].'%');
					unset($params['QUERY']);
				}
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}