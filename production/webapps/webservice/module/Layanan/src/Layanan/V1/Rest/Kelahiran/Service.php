<?php
namespace Layanan\V1\Rest\Kelahiran;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService {
	
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("kelahiran", "layanan"));
		$this->entity = new AnamnesisEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
	}
	
	public function simpan($data, $isCreated = false, $loaded = true) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity = new AnamnesisEntity();
		$this->entity->exchangeArray($data);
		
		if($isCreated) {
			$this->table->insert($this->entity->getArrayCopy());
			$id = $this->table->getLastInsertValue();
		} else {
			$id = $this->entity->get("ID");
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		}
		
		if($loaded) return $this->load(array("ID" => $id));
		return $id;
	}
}