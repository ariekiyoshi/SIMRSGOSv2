<?php
namespace Inventory\V1\Rest\PenerimaanBarang;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use DBService\generator\Generator;

use Inventory\V1\Rest\PenerimaanBarangDetil\PenerimaanBarangDetilService;
use Inventory\V1\Rest\Penyedia\PenyediaService;

class PenerimaanBarangService extends Service
{
	private $penerimaanbarangdetil;
	private $penyedia;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("penerimaan_barang", "inventory"));
		$this->entity = new PenerimaanBarangEntity();
		
		$this->penerimaanbarangdetil = new PenerimaanBarangDetilService();
		$this->penyedia = new PenyediaService();		
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : 0;
		if($id == 0) {
			$this->table->insert($this->entity->getArrayCopy());
			$id = $this->table->getLastInsertValue();
		} else {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("ID" => $id));
			//$this->table->update($this->entity->getArrayCopy(), array('ID' => $id));
		}
		
		$this->SimpanDetilPenerimaan($data, $id);
		
		return array(
			'success' => true
		);
	}
	
	private function SimpanDetilPenerimaan($data, $id) {
		if(isset($data['PENERIMAAN_BARANG_DETIL'])) {
			
			foreach($data['PENERIMAAN_BARANG_DETIL'] as $dtl) {
				$dtl['PENERIMAAN'] = $id;
				$this->penerimaanbarangdetil->simpan($dtl);
			}
		}
	}
	
	public function load($params = array(), $columns = array('*'), $penjualans = array()) {
		$data = parent::load($params, $columns, $penjualans);
		
		foreach($data as &$entity) {			
			$rekanan = $this->penyedia->load(array('ID' => $entity['REKANAN']));
			if(count($rekanan) > 0) $entity['REFERENSI']['REKANAN'] = $rekanan[0];			
		}
		
		return $data;
	}
}
