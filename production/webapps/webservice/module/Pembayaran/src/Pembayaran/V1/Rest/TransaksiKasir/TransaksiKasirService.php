<?php
namespace Pembayaran\V1\Rest\TransaksiKasir;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Zend\Db\Sql\TableIdentifier;

class TransaksiKasirService extends Service
{
    public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("transaksi_kasir", "pembayaran"));
		$this->entity = new TransaksiKasirEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			/*if($this->references['Referensi']) $this->referensi = new ReferensiService();*/
		}
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = is_numeric($this->entity->get('NOMOR')) ? $this->entity->get('NOMOR') : false;
		
		if($id) {
			$this->entity->set('TUTUP', new \Zend\Db\Sql\Expression('NOW()'));
			$this->entity->set('STATUS', 2);
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("NOMOR" => $id));
		} else {
			$this->entity->set('BUKA', new \Zend\Db\Sql\Expression('NOW()'));
			$this->entity->set('STATUS', 1);
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
			$id = $this->table->getLastInsertValue();
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('NOMOR' => $id))
		);
	}
}
