<?php
namespace BPJService\db\referensi\ppk;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService {
	private $peserta;
	
	public function __construct() {
        $this->table = DatabaseService::get('BPJS')->get(new TableIdentifier("ppk", "bpjs"));
    }

    public function simpan($data, $isCreated = false, $loaded = false) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity = new Entity();
		$this->entity->exchangeArray($data);
		$params = array(
			"kode" => $data["kode"]
		);
		if($isCreated) {
			$this->table->insert($this->entity->getArrayCopy());
		} else {
			$this->table->update($this->entity->getArrayCopy(), $params);
		}
		
		if($loaded) return $this->load($params);
		return true;
	}
}