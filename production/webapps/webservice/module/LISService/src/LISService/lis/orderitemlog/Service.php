<?php
namespace LISService\lis\orderitemlog;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use Zend\Db\Sql\TableIdentifier;

class Service extends DBService
{	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier('order_item_log', 'lis'));
		$this->entity = new Entity();
    }
		
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('HIS_ID');
		$rows = $this->load(array("HIS_ID" => $id));
		if(count($rows) > 0){
			$this->table->update($this->entity->getArrayCopy(), array("HIS_ID" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return true;
	}
}