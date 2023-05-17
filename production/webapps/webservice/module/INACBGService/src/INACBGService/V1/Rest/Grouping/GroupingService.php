<?php
namespace INACBGService\V1\Rest\Grouping;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

class GroupingService extends Service
{	
    public function __construct() {
        $this->table = DatabaseService::get("INACBG")->get('grouping'); 
		$this->entity = new GroupingEntity();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$nopen = $this->entity->get('NOPEN');
		
		$found = $this->load(array('NOPEN' => $nopen));
		if(count($found) > 0){
			$this->table->update($this->entity->getArrayCopy(), array("NOPEN" => $nopen));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return $this->load(array('NOPEN' => $nopen));
	}
}
