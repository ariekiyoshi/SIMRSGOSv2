<?php
namespace General\V1\Rest\PerawatRuangan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

use General\V1\Rest\Ruangan\RuanganService;
use General\V1\Rest\Perawat\PerawatService;


class PerawatRuanganService extends Service
{
	private $ruangan;
	private $perawat;
	
	protected $references = array(
		'Ruangan' => true,
		'Perawat' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("perawat_ruangan", "master"));
		$this->entity = new PerawatRuanganEntity();
		
		$this->limit = 1000;
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			if($this->references['Ruangan']) $this->ruangan = new RuanganService();
			if($this->references['Perawat']) $this->perawat = new PerawatService(true, array(
				'Ruangan' => false,
				'Pegawai' => true
			));
			
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
				if($this->references['Perawat']) {
					$perawat = $this->perawat->load(array('ID' => $entity['PERAWAT']));
					if(count($perawat) > 0) $entity['REFERENSI']['PERAWAT'] = $perawat[0];
				}
			}
		}
		
		return $data;
	}
      		
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('ID');
		if($id > 0){
			$this->table->update($this->entity->getArrayCopy(), array('ID' => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}
	
}