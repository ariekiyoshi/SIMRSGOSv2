<?php
namespace Inventory\V1\Rest\PengirimanDetil;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use Inventory\V1\Rest\PermintaanDetil\PermintaanDetilService;

class PengirimanDetilService extends Service
{
	private $permintaanDetil;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pengiriman_detil", "inventory"));
		$this->entity = new PengirimanDetilEntity();
		
		$this->permintaanDetil = new PermintaanDetilService();		
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : 0;
		if($id == 0) {
			$this->table->insert($this->entity->getArrayCopy());
			
		} else {
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		}
		
		return array(
			'success' => true
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {			
			$permintaanDetil = $this->permintaanDetil->load(array('ID' => $entity['PERMINTAAN_BARANG_DETIL']));
			if(count($permintaanDetil) > 0) $entity['REFERENSI']['PERMINTAAN_DETIL'] = $permintaanDetil[0];			
		}
		
		return $data;
	}
}
