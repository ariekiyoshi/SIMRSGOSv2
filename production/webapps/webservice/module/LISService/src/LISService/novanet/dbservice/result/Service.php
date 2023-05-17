<?php
namespace LISService\novanet\dbservice\result;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use Zend\Db\Sql\TableIdentifier;

class Service extends DBService
{	
    public function __construct() {		
        $this->table = DatabaseService::get('Novanet')->get('result');
		$this->entity = new Entity();	
    }
		
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity-> exchangeArray($data);
		unset($data["status"]);
		$rows = $this->table->select($data)->toArray();
		if(count($rows) > 0){
			$this->table->update($this->entity->getArrayCopy(), $data);
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return true;
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
						
			$select->join(array('o' => 'Orders'), "o.ID = result._OID", array("InstrID"));
			$select->join(array('p' => 'Patient'), "p.ID = result._PID", array("Lab_PatientID"));
			$select->join(array('d' => 'POCTDeviceStatus'), "d.MsgID = o.MsgID", array("Location", "DeviceID"));
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}