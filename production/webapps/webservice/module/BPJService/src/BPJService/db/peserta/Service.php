<?php
namespace BPJService\db\peserta;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService {
	public function __construct() {
        $this->table = DatabaseService::get('BPJS')->get(new TableIdentifier("peserta", "bpjs"));
    }

    public function simpan($data, $isCreated = false, $loaded = false) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity = new Entity();
		$this->entity->exchangeArray($data);
		if($isCreated) {
			$this->table->insert($this->entity->getArrayCopy());
		} else {
			$this->table->update($this->entity->getArrayCopy(), array("noKartu" => $data["noKartu"]));
		}
		
		if($loaded) return $this->load(array("noKartu" => $data["noKartu"]));
		return true;
	}
}