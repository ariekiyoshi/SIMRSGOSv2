<?php
namespace Pembayaran\V1\Rest\EDC;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Zend\Db\Sql\TableIdentifier;

use General\V1\Rest\Referensi\ReferensiService;

class EDCService extends Service
{
	private $referensi;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("edc", "pembayaran"));
		$this->entity = new EDCEntity();
		
		$this->referensi = new ReferensiService();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = (int) $this->entity->get("ID");
		
		if($id > 0) {
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
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
			foreach($data as &$entity) {
				$bank = $this->referensi->load(array('ID' => $entity['BANK'], 'JENIS' => 16));
				if(count($bank) > 0) $entity['REFERENSI']['BANK'] = $bank;
				
				$jeniskartu = $this->referensi->load(array('ID' => $entity['JENIS_KARTU'], 'JENIS' => 17));
				if(count($jeniskartu) > 0) $entity['REFERENSI']['JENIS_KARTU'] = $jeniskartu;

			}

		return $data;
	}
   
		
}
