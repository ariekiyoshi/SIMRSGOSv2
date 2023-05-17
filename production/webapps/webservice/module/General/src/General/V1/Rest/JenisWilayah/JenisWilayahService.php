<?php
namespace General\V1\Rest\JenisWilayah;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

class JenisWilayahService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("jenis_wilayah", "master"));
		$this->entity = new JenisWilayahEntity();
    }
}
