<?php
namespace Pendaftaran\V1\Rest\PanggilanAntrianRuangan;

use DBService\DatabaseService;
use DBService\Service;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\TableIdentifier;
use DBService\System;
use Pendaftaran\V1\Rest\AntrianRuangan\AntrianRuanganService;

class PanggilanAntrianRuanganService extends Service
{	
	protected $references = array(
		'AntrianRuangan' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("panggilan_antrian_ruangan", "pendaftaran"));
		$this->entity = new PanggilanAntrianRuanganEntity();
		
		$this->setReferences($references);
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			if($this->references['AntrianRuangan']) $this->antrianRuangan = new AntrianRuanganService();
		}
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		
		$id = is_numeric($data['ID']) ? $data['ID'] : false;
		
		if($id) {
			$this->table->update($this->entity->getArrayCopy(), array('ID' => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
			$id = $this->table->getLastInsertValue();
		}
		
		$results = $this->load(array('ID'=>$id));
		if(count($results) > 0) $data = $results[0];
		return array(
			'success' => true,
			'data' => $data
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {				
				if($this->references['AntrianRuangan']) {
					$antrianRuangan = $this->antrianRuangan->load(array('ID' => $entity['ANTRIAN_RUANGAN']));
					if(count($antrianRuangan) > 0) $entity['REFERENSI']['ANTRIAN'] = $antrianRuangan[0];
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
			
			if(!System::isNull($params, 'STATUS')) {
				$status = $params['STATUS'];
				$params['panggilan_antrian_ruangan.STATUS'] = $status;
				unset($params['STATUS']);
			}
			
			if(!System::isNull($params, 'IN')) {
				$select->join(
					array('antrian' => new TableIdentifier('antrian_ruangan', 'pendaftaran')),
					'antrian.ID = panggilan_antrian_ruangan.ANTRIAN_RUANGAN',
					array()
				);
				$select->where('antrian.STATUS = 1');
				$select->where('panggilan_antrian_ruangan.STATUS = 1');
				$select->where('antrian.RUANGAN IN ('.$params['IN'].')');
				unset($params['IN']);
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}
