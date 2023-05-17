<?php
namespace Pendaftaran\V1\Rest\Kecelakaan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use DBService\generator\Generator;

class Service extends DBService {
	
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("kecelakaan", "pendaftaran"));
		$this->entity = new KecelakaanEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
	}
	
	public function simpan($data, $isCreated = false, $loaded = true) {
		$nomor = "";
		$data = is_array($data) ? $data : (array) $data;
		$this->entity = new KecelakaanEntity();
		$this->entity->exchangeArray($data);
		
		if($isCreated) {
			$nomor = Generator::generateNoKejadian();
			$this->entity->set('NOMOR', $nomor);
			$this->table->insert($this->entity->getArrayCopy());			
		} else {
			$nomor = $this->entity->get("NOMOR");
			$this->table->update($this->entity->getArrayCopy(), array("NOMOR" => $nomor));
		}
		
		if($loaded) return $this->load(array("NOMOR" => $nomor));
		return $nomor;
	}
}