<?php
namespace General\V1\Rest\Administrasi;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

class AdministrasiService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("administrasi", "master"));
		$this->entity = new AdministrasiEntity();
    }
}