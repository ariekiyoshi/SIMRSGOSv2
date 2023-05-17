<?php
namespace Inventory\V1\Rest\PengembalianBarang;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use DBService\generator\Generator;

use Inventory\V1\Rest\PengembalianBarangDetil\PengembalianBarangDetilService;
use Inventory\V1\Rest\Penyedia\PenyediaService;

class PengembalianBarangService extends Service
{
	private $pengembalianbarangdetil;
	private $penyedia;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pengembalian_barang", "inventory"));
		$this->entity = new PengembalianBarangEntity();
		
		$this->pengembalianbarangdetil = new PengembalianBarangDetilService();
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
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		}
		
		$this->SimpanDetilPengembalian($data, $id);
		
		return array(
			'success' => true
		);
	}
	
	private function SimpanDetilPengembalian($data, $id) {
		if(isset($data['PENGEMBALIAN_BARANG_DETIL'])) {
			
			foreach($data['PENGEMBALIAN_BARANG_DETIL'] as $dtl) {
				$dtl['PENGEMBALIAN'] = $id;
				$this->pengembalianbarangdetil->simpan($dtl);
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
