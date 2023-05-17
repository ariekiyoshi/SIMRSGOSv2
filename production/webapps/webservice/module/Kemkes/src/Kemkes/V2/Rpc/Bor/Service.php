<?php
namespace Kemkes\V2\Rpc\Bor;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("statistik_indikator", "informasi"));
    }
	
	public function get($params = array()) {
		$tglAwal = "DATE_SUB(DATE(NOW()), INTERVAL (DAY(DATE(NOW())) - 1) DAY)";
		$tglAkhir = "DATE(NOW())";
		if(isset($params["bulan"])) {
			$data = explode("-", $params["bulan"]);
			$tglAwal = "CONCAT(".$data[1].", '-', ".$data[0].", '-', 1)";
			$tglAkhir = "informasi.getLastDate(".$data[1].",".$data[0].")";
		}
		return $this->execute("CALL informasi.listBorKemkes(".$tglAwal.", ".$tglAkhir.")");
	}
}