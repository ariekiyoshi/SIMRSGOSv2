<?php
namespace Pembayaran\V1\Rest\RincianTagihanPaket;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Zend\Db\Sql\TableIdentifier;

class RincianTagihanPaketService extends Service
{
	protected $limit = 5000;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("rincian_tagihan_paket", "pembayaran"));
		$this->entity = new RincianTagihanPaketEntity();
    }
   	
}