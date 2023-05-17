<?php
namespace Kemkes\V2\Rpc\Kematian;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("statistik_jumlah_kematian", "informasi"));
        $this->entity = new Entity();
    }
	
    public function simpan($data) {
        $data = is_array($data) ? $data : (array) $data;
        $this->entity = new Entity();
        $this->entity->exchangeArray($data);
        
        $params = [
            "TAHUN" => $this->entity->get("TAHUN"),
            "BULAN" => $this->entity->get("BULAN")
        ];
        $this->table->update($this->entity->getArrayCopy(), $params);
        
        return true;
    }
}