<?php
namespace General\V1\Rest\Instansi;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use General\V1\Rest\PPK\PPKService;

class InstansiService extends Service
{
	private $PPK;
	
	protected $references = array(
		'PPK' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("instansi", "aplikasi"));
		$this->entity = new InstansiEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			if($this->references['PPK']) $this->ppk = new PPKService();
		}
    }
	   
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['PPK']) {
					$ppk = $this->ppk->load(array('ID' => $entity['PPK']));
					if(count($ppk) > 0) $entity['REFERENSI']['PPK'] = $ppk[0];
				}
			}
		}
		
		return $data;
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = (int) $this->entity->get('ID');
		
		if($id > 0){
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
			$id = $this->table->getLastInsertValue();
		}
				
		return array(
			'success' => true,
			'data' => $this->load(array('ID' => $id))
		);
	}
}
