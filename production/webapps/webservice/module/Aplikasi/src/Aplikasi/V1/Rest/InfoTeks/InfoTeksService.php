<?php
namespace Aplikasi\V1\Rest\InfoTeks;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

class InfoTeksService extends Service
{	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("info_teks", "aplikasi"));
		$this->entity = new InfoTeksEntity();
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = $this->entity->get('ID');
		$cek = $this->load(array('ID' => $id));
		
		if(count($cek) > 0) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array('ID' => $id));
		} else {
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);		
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('ID' => $id))
		);
	}
	
}
