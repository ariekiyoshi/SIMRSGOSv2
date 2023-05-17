<?php
namespace LISService\winacom\dbservice\orderitem ;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use Zend\Db\Sql\TableIdentifier;

class Service extends DBService
{	
    public function __construct() {
        $this->table = DatabaseService::get('Winacom')->get('ordered_item');
		$this->entity = new Entity();
    }
		
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$orderItemId = $this->entity->get('order_item_id');
		$orderNumber = $this->entity->get('order_number');
		$rows = $this->table->select(array("order_item_id" => $orderItemId, "order_number" => $orderNumber))->toArray();
		if(count($rows) > 0){
			$this->table->update($this->entity->getArrayCopy(), array("order_item_id" => $orderItemId, "order_number" => $orderNumber));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return true;
	}
}