<?php
namespace Kemkes\db\rujukan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService {
	public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("rujukan", "kemkes"));
    }

    public function simpan($data, $isCreated = false, $loaded = false) {
        $data = is_array($data) ? $data : (array) $data;
        $this->entity = new Entity();
        $this->entity->exchangeArray($data);
        $params = array(
            "NOMOR" => $data["NOMOR"]
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