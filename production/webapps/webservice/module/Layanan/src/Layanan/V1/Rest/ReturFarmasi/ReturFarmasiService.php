<?php
namespace Layanan\V1\Rest\ReturFarmasi;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use DBService\System;
use DBService\generator\Generator;

class ReturFarmasiService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("retur_farmasi", "layanan"));
		$this->entity = new ReturFarmasiEntity();
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : false;
		if($id) {
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$id = Generator::generateIdReturFarmasi();
			$this->entity->set('ID', $id);
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('ID' => $id))
		);
	}
	
}