<?php
namespace General\V1\Rest\Negara;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Stdlib\Hydrator;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

class NegaraService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("negara", "master"));
		$this->entity = new NegaraEntity();
		$this->limit = 5000;
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('ID');
		
		if(count($id) > 0){
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}

	
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);					
			
			/*if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				$select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);*/
			$select->offset(0)->limit($this->limit);
			
			if(isset($params['ID'])) $select->where(array('ID' => $params['ID']));
			if(isset($params['DESKRIPSI'])) $select->where->like('DESKRIPSI', $params['DESKRIPSI'].'%');
			if(isset($params['SINGKATAN'])) $select->where->like('SINGKATAN', $params['SINGKATAN'].'%');
			$select->order($orders);
		})->toArray();
	}
	
	
}