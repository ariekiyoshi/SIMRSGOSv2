<?php
namespace Pembayaran\V1\Rest\RincianTagihan;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Zend\Db\Sql\TableIdentifier;

class RincianTagihanService extends Service
{
	protected $limit = 5000;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("rincian_tagihan", "pembayaran"));
		$this->entity = new RincianTagihanEntity();
    }
   
	public function listRincianTagihan($tagihan, $status) {
		$adapter = DatabaseService::get('SIMpel')->getAdapter(); 
		$stmt = $adapter->query('CALL pembayaran.listRincianTagihan(?,?)');
		$results = $stmt->execute(array($tagihan, $status));
		$data = array();
		foreach($results as $row) {
			$data[] = $row;
		}
		
		return $data;
	}
}