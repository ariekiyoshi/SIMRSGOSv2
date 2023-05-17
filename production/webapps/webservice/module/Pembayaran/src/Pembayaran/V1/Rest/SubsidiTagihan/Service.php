<?php
namespace Pembayaran\V1\Rest\SubsidiTagihan;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use Zend\Db\Sql\TableIdentifier;

class Service extends DBService
{
	public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("subsidi_tagihan", "pembayaran"));
		$this->entity = new SubsidiTagihanEntity();
    }
}
