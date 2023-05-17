<?php
namespace DukcapilService\db\penduduk;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService {
	public function __construct() {
	    $this->config["entityName"] = "\\DukcapilService\\db\\penduduk\\Entity";
	    $this->config["entityId"] = "NIK";
	    $this->config["autoIncrement"] = false;
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("penduduk", "dukcapil"));
    }
}