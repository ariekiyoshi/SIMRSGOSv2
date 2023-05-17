<?php
namespace General\V1\Rest\StaffRuangan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use Zend\json\Json;
use DBService\System;
use DBService\Service;
use General\V1\Rest\Staff\StaffService;

class StaffRuanganService extends Service
{
	private $staff;
	
	protected $references = array(
		'staff' => true
	);
    
	public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("staff_ruangan", "master"));
		$this->entity = new StaffRuanganEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			if($this->references['staff']) $this->staff = new StaffService(true, array(
				'StaffRuangan' => false
			));
			
		}
    }
		
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('ID');
		if($id > 0){
			$this->table->update($this->entity->getArrayCopy(), array('ID' => $id));
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
			$staff = $this->staff->load(array('ID' => $entity['STAFF']));
			if(count($staff) > 0) $entity['REFERENSI']['STAFF'] = $staff[0];
		}
		
		return $data;
	}
}