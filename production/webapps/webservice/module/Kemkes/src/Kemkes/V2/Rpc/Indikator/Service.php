<?php
namespace Kemkes\V2\Rpc\Indikator;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("statistik_indikator", "informasi"));
        $this->entity = new Entity();
    }
	
    public function simpan($data) {
        $data = is_array($data) ? $data : (array) $data;
        $this->entity = new Entity();
        $this->entity->exchangeArray($data);
        
        $params = [
            "TAHUN" => $this->entity->get("TAHUN"),
            "PERIODE" => $this->entity->get("PERIODE"),
            "JENIS" => $this->entity->get("JENIS")
        ];
        $this->table->update($this->entity->getArrayCopy(), $params);
        
        return true;
    }
}