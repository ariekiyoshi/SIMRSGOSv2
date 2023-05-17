<?php
namespace General\V1\Rest\RuangKamarTidur;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use General\V1\Rest\RuangKamar\RuangKamarService;

class RuangKamarTidurService extends Service
{
	private $ruangKamar;
	
	protected $references = array(
		'RuangKamar' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("ruang_kamar_tidur", "master"));
		$this->entity = new RuangKamarTidurEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
				
		if($includeReferences) {			
			if($this->references['RuangKamar']) $this->ruangKamar = new RuangKamarService();
		}
    }
		
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['RuangKamar']) {
					if(is_object($this->references['RuangKamar'])) {
						$references = isset($this->references['RuangKamar']->REFERENSI) ? (array) $this->references['RuangKamar']->REFERENSI : array();
						$this->ruangKamar->setReferences($references, true);
						if(isset($this->references['RuangKamar']->COLUMNS)) $this->ruangKamar->setColumns((array) $this->references['RuangKamar']->COLUMNS);
					}
					$ruangKamar = $this->ruangKamar->load(array('ID' => $entity['RUANG_KAMAR']));
					if(count($ruangKamar) > 0) $entity['REFERENSI']['RUANG_KAMAR'] = $ruangKamar[0];
				}
			}
		}
		
		return $data;
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = (int) $this->entity->get('ID');
		
		if($id > 0){
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {			
			$this->table->insert($this->entity->getArrayCopy());
			$id = $this->table->getLastInsertValue();
		}
				
		return array(
			'success' => true,
			'data' => $this->load(array('ID' => $id))
		);
	}
}
