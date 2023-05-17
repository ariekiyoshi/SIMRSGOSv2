<?php
namespace Kemkes\V2\Rest\BedMonitor;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("tempat_tidur_kemkes", "informasi"));
		$this->entity = new BedMonitorEntity();
    }
	
	public function get($params = array()) {		
		$tanggal = isset($params["tanggal"]) ? "STR_TO_DATE('".$params["tanggal"]."', '%d-%m-%Y')" : "DATE(NOW())";
		return $this->execute("CALL informasi.listBedMonitorKemkes(".$tanggal.", ".$tanggal.")");
	}
}