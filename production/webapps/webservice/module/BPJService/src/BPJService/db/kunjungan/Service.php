<?php
namespace BPJService\db\kunjungan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService {
	public function __construct() {
        $this->table = DatabaseService::get('BPJS')->get(new TableIdentifier("kunjungan", "bpjs"));
    }

    public function simpan($data, $isCreated = false, $loaded = false) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity = new Entity();
		$this->entity->exchangeArray($data);
		if($isCreated) {
			$this->table->insert($this->entity->getArrayCopy());
		} else {
			$this->table->update($this->entity->getArrayCopy(), array("noSEP" => $data["noSEP"]));
		}
		
		if($loaded) return $this->load(array("noSEP" => $data["noSEP"]));
		return true;
	}
}