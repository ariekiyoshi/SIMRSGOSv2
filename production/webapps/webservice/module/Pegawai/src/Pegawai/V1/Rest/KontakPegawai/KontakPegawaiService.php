<?php
namespace Pegawai\V1\Rest\KontakPegawai;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use General\V1\Rest\Referensi\ReferensiService;

class KontakPegawaiService extends DBService {
	
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("kontak_pegawai", "pegawai"));
		$this->entity = new KontakPegawaiEntity();
		$this->setReferences($references);
		$this->includeReferences = $includeReferences;
		$this->referensi = new ReferensiService();
	}
	
	public function simpan($data, $isCreated = false, $loaded = true) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity = new KontakPegawaiEntity();
		$this->entity->exchangeArray($data);
		
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
	
	public function load($params = array(), $columns = array('*'), $edukasipasienkeluargas = array()) {
		$data = parent::load($params, $columns, $edukasipasienkeluargas);
		
		foreach($data as &$entity) {
			$jeniskontak = $this->referensi->load(array('ID' => $entity['JENIS'], 'JENIS' => 8));
			if(count($jeniskontak) > 0) $entity['REFERENSI']['JENIS'] = $jeniskontak[0];
		}
		
		return $data;
	}
	
	protected function query($columns, $params, $isCount = false, $orders = array()) {
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				if(!$isCount) $select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(!System::isNull($params, 'NIP')) {
				$nip = $params['NIP'];
				$params['kontak_pegawai.NIP'] = $nip;
				unset($params['NIP']);
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}