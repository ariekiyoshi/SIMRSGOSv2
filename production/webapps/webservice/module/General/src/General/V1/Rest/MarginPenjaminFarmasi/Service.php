<?php
namespace General\V1\Rest\MarginPenjaminFarmasi;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use General\V1\Rest\Referensi\ReferensiService;

class Service extends DBService {
	
	private $referensi;
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("margin_penjamin_farmasi", "master"));
		$this->entity = new MarginPenjaminFarmasiEntity();
		$this->referensi = new ReferensiService();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);

		if($this->includeReferences) {
			foreach($data as &$entity) {
				$carabayar = $this->referensi->load(array('ID' => $entity['PENJAMIN'],'JENIS' => 10));
				if(count($carabayar) > 0) $entity['REFERENSI']['PENJAMIN'] = $carabayar[0];
				$jenis = $this->referensi->load(array('ID' => $entity['JENIS'],'JENIS' => 78));
				if(count($jenis) > 0) $entity['REFERENSI']['JENIS'] = $jenis[0];
			}
		}
		return $data;
	}
	
	public function simpan($data, $isCreated = false, $loaded = true) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity = new MarginPenjaminFarmasiEntity();
		$this->entity->exchangeArray($data);
		
		$this->entity->set('TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));
		
		if($isCreated) {
			$this->table->insert($this->entity->getArrayCopy());
			$id = $this->table->getLastInsertValue();
		} else {
			$id = $this->entity->get("ID");
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		}
		
		if($loaded) return $this->load(array("ID" => $id));
		return $id;
	}
}