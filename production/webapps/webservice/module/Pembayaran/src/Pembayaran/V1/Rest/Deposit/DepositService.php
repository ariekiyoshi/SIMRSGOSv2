<?php
namespace Pembayaran\V1\Rest\Deposit;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;


use General\V1\Rest\Referensi\ReferensiService;
use Aplikasi\V1\Rest\Pengguna\PenggunaService;


class DepositService extends Service
{
	private $referensi;
	private $pengguna;
	
	protected $references = array(
		'Jenis' => true,
		'Oleh' => true
	);
	
     public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("deposit", "pembayaran"));
		$this->entity = new DepositEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['Jenis']) $this->referensi = new ReferensiService();
			if($this->references['Oleh']) $this->pengguna = new PenggunaService();
		}
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : 0;

		if($id > 0) {
			$this->table->update($this->entity->getArrayCopy(), array('ID'=>$id));
		} else {
			$data = $this->entity->getArrayCopy();	
			$this->table->insert($data);
			$id = $this->table->getLastInsertValue();
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('ID'=>$id))
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);

		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Jenis']) {
					$jenis = $this->referensi->load(array('ID' => $entity['JENIS'], 'JENIS' => 56));
					if(count($jenis) > 0) $entity['REFERENSI']['JENIS'] = $jenis[0];
				}
				
				if($this->references['Oleh']) {
					$oleh = $this->pengguna->load(array('ID' => $entity['OLEH']));
					if(count($oleh) > 0) $entity['REFERENSI']['PENGGUNA'] = $oleh[0];
				}
				
				
			}
		}
		
		return $data;
	}
}
