<?php
namespace Pembayaran\V1\Rest\PengembalianDeposit;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Zend\Db\Sql\TableIdentifier;

class PengembalianDepositService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pengembalian_deposit", "pembayaran"));
		$this->entity = new PengembalianDepositEntity();
    }
}
