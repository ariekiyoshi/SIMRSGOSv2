<?php
namespace LISService\winacom\dbservice\demographics;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use Zend\Db\Sql\TableIdentifier;

class Service extends DBService
{	
    public function __construct() {
        $this->table = DatabaseService::get('Winacom')->get('demographics');
		$this->entity = new Entity();
    }
		
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('patient_id');
		$rows = $this->table->select(array("patient_id" => $id))->toArray();
		if(count($rows) > 0){
			$this->table->update($this->entity->getArrayCopy(), array("patient_id" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return true;
	}
}