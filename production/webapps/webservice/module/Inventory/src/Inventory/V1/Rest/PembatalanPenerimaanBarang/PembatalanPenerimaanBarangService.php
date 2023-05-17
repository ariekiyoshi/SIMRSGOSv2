<?php
namespace Inventory\V1\Rest\PembatalanPenerimaanBarang;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Zend\Db\Sql\TableIdentifier;



class PembatalanPenerimaanBarangService extends Service
{
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pembatalan_penerimaan_barang", "inventory"));
		$this->entity = new PembatalanPenerimaanBarangEntity();
		
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = (int) $this->entity->get('ID');
		
		if($id > 0){
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array('ID' => $id));
		} else {
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
			$id = $this->table->getLastInsertValue();
			
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('ID' => $id))
		);
	}
	
	
}
