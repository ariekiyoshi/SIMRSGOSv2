<?php
namespace Kemkes\V2\Rpc\Diagnosa;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("diagnosa_rj", "informasi"));
    }
	
	public function getRJ($params = array()) {	
		$tglAwal = $tglAkhir = "DATE(NOW())";
		if(isset($params["bulan"])) {
			$data = explode("-", $params["bulan"]);
			$tglAwal = "CONCAT(".$data[1].", '-', ".$data[0].", '-', 1)";
			$tglAkhir = "informasi.getLastDate(".$data[1].",".$data[0].")";
		}
		return $this->execute("CALL informasi.list10DiagnosaRJKemkes(".$tglAwal.", ".$tglAkhir.")");
	}
	
	public function getRD($params = array()) {
		$tglAwal = $tglAkhir = "DATE(NOW())";
		if(isset($params["bulan"])) {
			$data = explode("-", $params["bulan"]);
			$tglAwal = "CONCAT(".$data[1].", '-', ".$data[0].", '-', 1)";
			$tglAkhir = "informasi.getLastDate(".$data[1].",".$data[0].")";
		}
		return $this->execute("CALL informasi.list10DiagnosaRDKemkes(".$tglAwal.", ".$tglAkhir.")");
	}
	
	public function getRI($params = array()) {
		$tglAwal = $tglAkhir = "DATE(NOW())";
		if(isset($params["bulan"])) {
			$data = explode("-", $params["bulan"]);
			$tglAwal = "CONCAT(".$data[1].", '-', ".$data[0].", '-', 1)";
			$tglAkhir = "informasi.getLastDate(".$data[1].",".$data[0].")";
		}
		return $this->execute("CALL informasi.list10DiagnosaRIKemkes(".$tglAwal.", ".$tglAkhir.")");
	}
}