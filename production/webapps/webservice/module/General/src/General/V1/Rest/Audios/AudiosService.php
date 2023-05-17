<?php
namespace General\V1\Rest\Audios;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;

class AudiosService extends Service
{	
    public function __construct() {
        $this->config["entityName"] = "\\General\\V1\\Rest\\Audios\\AudiosEntity";
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("audios", "master"));
    }
}
