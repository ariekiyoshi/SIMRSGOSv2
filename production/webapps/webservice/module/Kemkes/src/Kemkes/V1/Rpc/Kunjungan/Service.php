<?php
namespace Kemkes\V1\Rpc\Kunjungan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("kunjungan", "informasi"));
    }
	
	public function getRJ($params = array()) {		
		$tanggal = isset($params["tanggal"]) ? "STR_TO_DATE('".$params["tanggal"]."', '%d-%m-%Y')" : "DATE(NOW())";
		return $this->execute("CALL informasi.listKunjunganRJKemkes(".$tanggal.", ".$tanggal.")");
	}
	
	public function getRD($params = array()) {
		$tanggal = isset($params["tanggal"]) ? "STR_TO_DATE('".$params["tanggal"]."', '%d-%m-%Y')" : "DATE(NOW())";
		return $this->execute("CALL informasi.listKunjunganRDKemkes(".$tanggal.", ".$tanggal.")");
	}
	
	public function getRI($params = array()) {
		$tanggal = isset($params["tanggal"]) ? "STR_TO_DATE('".$params["tanggal"]."', '%d-%m-%Y')" : "DATE(NOW())";
		return $this->execute("CALL informasi.listKunjunganRIKemkes(".$tanggal.", ".$tanggal.")");
	}
}