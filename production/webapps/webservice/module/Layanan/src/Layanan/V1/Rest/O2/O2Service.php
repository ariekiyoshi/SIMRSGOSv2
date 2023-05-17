<?php
namespace Layanan\V1\Rest\O2;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;

use Zend\Db\Sql\Expression;
use DBService\System;
use DBService\generator\Generator;


class O2Service extends Service
{
	
	
    public function __construct($includeReferences = true, $references = array())  {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("o2", "layanan"));
		$this->entity = new O2Entity();
		
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : false;
		
		if($id) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("ID" => $id));
		} else {
			$id = Generator::generateIdO2();
			$this->entity->set('ID', $id);
		
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
		}
		
		return $this->load(array('o2.ID' => $id));
	}
	
	
}