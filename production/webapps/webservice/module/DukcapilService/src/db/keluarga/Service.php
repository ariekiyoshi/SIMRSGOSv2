<?php
namespace DukcapilService\db\keluarga;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService {
	public function __construct() {
	    $this->config["entityName"] = "\\DukcapilService\\db\\keluarga\\Entity";
	    $this->config["entityId"] = "NO_KK";
	    $this->config["autoIncrement"] = false;
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("keluarga", "dukcapil"));
    }
}