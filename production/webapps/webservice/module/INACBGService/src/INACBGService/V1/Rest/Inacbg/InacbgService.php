<?php
namespace INACBGService\V1\Rest\Inacbg;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

class InacbgService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get("INACBG")->get('inacbg'); 
		$this->entity = new InacbgEntity();
		$this->limit = 5000;
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('ID');
		
		if($id > 0){
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}
}
