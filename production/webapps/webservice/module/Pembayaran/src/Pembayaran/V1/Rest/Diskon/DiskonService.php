<?php
namespace Pembayaran\V1\Rest\Diskon;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Zend\Db\Sql\TableIdentifier;

class DiskonService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("diskon", "pembayaran"));
		$this->entity = new DiskonEntity();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get("ID");
		$cek = $this->load(array("ID" => $id));
		if(count($cek) === 0) {
			$tagihan = $this->entity->get('TAGIHAN');
			$cek = $this->load(array('TAGIHAN'=>$tagihan));
			if(count($cek) > 0) {
				$id = $cek[0]["ID"];
			}
		}
		
		if(count($cek) > 0) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array('ID'=>$id));
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
