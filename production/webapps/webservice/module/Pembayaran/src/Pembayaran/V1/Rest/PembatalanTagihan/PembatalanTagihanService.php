<?php
namespace Pembayaran\V1\Rest\PembatalanTagihan;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Zend\Db\Sql\TableIdentifier;



class PembatalanTagihanService extends Service
{
	private $referensi; 
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pembatalan_tagihan", "pembayaran"));
		$this->entity = new PembatalanTagihanEntity();
		
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
