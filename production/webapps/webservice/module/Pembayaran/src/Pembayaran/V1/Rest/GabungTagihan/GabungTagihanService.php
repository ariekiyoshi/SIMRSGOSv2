<?php
namespace Pembayaran\V1\Rest\GabungTagihan;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Zend\Db\Sql\TableIdentifier;

class GabungTagihanService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("gabung_tagihan", "pembayaran"));
		$this->entity = new GabungTagihanEntity();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$cek = $this->load(array('ID' => $this->entity->get('ID')));
		$this->entity->set('TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));
		
		if(count($cek) > 0) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array('ID'=>$this->entity->get('ID')));
		} else {
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
		}
		
		return array(
			'success' => true
		);
	}
}
