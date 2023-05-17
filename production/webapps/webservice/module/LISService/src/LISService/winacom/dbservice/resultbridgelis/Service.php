<?php
namespace LISService\winacom\dbservice\resultbridgelis;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use Zend\Db\Sql\TableIdentifier;

class Service extends DBService
{	
    public function __construct() {
        $this->table = DatabaseService::get('Winacom')->get('result_bridge_lis');
		$this->entity = new Entity();
    }
		
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity-> exchangeArray($data);
		unset($data["transfer_flag"]);
		$rows = $this->table->select($data)->toArray();
		if(count($rows) > 0){
			$this->table->update($this->entity->getArrayCopy(), $data);
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return true;
	}
}