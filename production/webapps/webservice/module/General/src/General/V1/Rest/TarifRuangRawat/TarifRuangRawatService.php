<?php
namespace General\V1\Rest\TarifRuangRawat;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

class TarifRuangRawatService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("tarif_ruang_rawat", "master"));
		$this->entity = new TarifRuangRawatEntity();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('ID');        
		
		$rows = $this->table->select(array("ID" => $id))->toArray();
		if(count($rows) > 0){
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
            $this->entity->set('TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}
}