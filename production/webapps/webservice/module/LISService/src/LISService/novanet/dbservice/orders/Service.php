<?php
namespace LISService\novanet\dbservice\registration;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use Zend\Db\Sql\TableIdentifier;

class Service extends DBService
{	
    public function __construct() {
        $this->table = DatabaseService::get('Novanet')->get('orders');
		$this->entity = new Entity();
    }
		
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('order_number');
		$rows = $this->table->select(array("order_number" => $id))->toArray();
		if(count($rows) > 0){
			$this->table->update($this->entity->getArrayCopy(), array("order_number" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return true;
	}
}