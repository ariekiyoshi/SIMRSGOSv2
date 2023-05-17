<?php
namespace Aplikasi\V1\Rest\PenggunaAksesLog;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use DBService\generator\Generator;
use Aplikasi\V1\Rest\Pengguna\PenggunaService;
use Aplikasi\V1\Rest\Objek\Service as Objek;

class Service extends DBService {
	private $pengguna;
	private $objek;
	
	private $actions = array(
		"C" => "Penambahan/pembuatan",
		"R" => "Mengakses/Menampilkan",
		"U" => "Perubahan",
		"D" => "Penghapusan"
	);
	
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("pengguna_akses_log", "logs"));
		$this->entity = new PenggunaAksesLogEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		$this->objek = new Objek();

		$this->pengguna = new PenggunaService();
	}
	
	public function load($params = array(), $columns = array("*"), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				$pengguna = $this->pengguna->load(array("ID" => $entity["PENGGUNA"]));
				if(count($pengguna) > 0) $entity["PENGGUNA"] = $pengguna[0]["NAMA"];
				
				$entity["AKSI"] = $this->actions[$entity["AKSI"]];				
				
				$entity["SEBELUM"] = $entity["SEBELUM"] == "" ? "" : (array) json_decode($entity["SEBELUM"]);
				$entity["SESUDAH"] = $entity["SESUDAH"] == "" ? "" : (array) json_decode($entity["SESUDAH"]);
				
				$objs = $this->objek->load(array("ID" => $entity["OBJEK"]));
				if(count($objs) > 0) {
					$objArray = explode(".", $objs[0]["TABEL"]);
					if(count($objArray) > 1) {
						if($objArray[0] == $objArray[1]) unset($objArray[1]);
					}
					$entity["OBJEK"] = ucwords(strtolower(implode(" ", $objArray)));

					
					$obj = new $objs[0]["ENTITY"];
					if(is_array($entity["SEBELUM"])) {
						$entity["SEBELUM"] = $obj->getDataWithDescription($entity["SEBELUM"]);
					}
					if(is_array($entity["SESUDAH"])) {						
						$entity["SESUDAH"] = $obj->getDataWithDescription($entity["SESUDAH"]);
					}
				}
			}
		}
		
		return $data;
	}
	
	public function simpan($data, $isCreated = false, $loaded = true) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		if($isCreated) {
			$id = Generator::generateIdPenggunaAksesLog();
			$this->entity->set("ID", $id);		
			$this->table->insert($this->entity->getArrayCopy());
		} else {
			$id = $this->entity->get("ID");
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		}
		
		if($loaded) return $this->load(array("ID" => $id));
		return true;
	}
}