<?php
namespace General\V1\Rest\RuangKamar;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use General\V1\Rest\Ruangan\RuanganService;
use General\V1\Rest\Referensi\ReferensiService;

class RuangKamarService extends Service
{
	private $ruangan;
	private $referensi;
	
	protected $references = array(
		'Ruangan' => true,
		'Kelas' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("ruang_kamar", "master"));
		$this->entity = new RuangKamarEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			$this->referensi = new ReferensiService();
			if($this->references['Ruangan']) $this->ruangan = new RuanganService();
		}
    }
	   
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Ruangan']) {
					$ruangan = $this->ruangan->load(array('ID' => $entity['RUANGAN']));
					if(count($ruangan) > 0) $entity['REFERENSI']['RUANGAN'] = $ruangan[0];
				}
				if($this->references['Kelas']) {
					$kelas = $this->referensi->load(array('ID' => $entity['KELAS'], 'JENIS' => 19));
					if(count($kelas) > 0) $entity['REFERENSI']['KELAS'] = $kelas[0];
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
