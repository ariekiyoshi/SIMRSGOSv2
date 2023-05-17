<?php
namespace Inventory\V1\Rest\HargaBarang;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use Inventory\V1\Rest\Barang\BarangService;

class HargaBarangService extends Service
{	
	private $barang;
	
	protected $references = array(
		'Barang' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("harga_barang", "inventory"));
		$this->entity = new HargaBarangEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['Barang']) $this->barang = new BarangService();
		}
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = (int) $this->entity->get('ID');
		
		if($id > 0){
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Barang']) {
					$barang = $this->barang->load(array('ID' => $entity['BARANG']));
					if(count($barang) > 0) $entity['REFERENSI']['BARANG'] = $barang[0];
				}
			}
		}
				
		return $data;
	}
}
