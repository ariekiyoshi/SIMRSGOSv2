<?php
namespace General\V1\Rest\PenjaminRuangan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\Ruangan\RuanganService;


class PenjaminRuanganService extends Service
{
	private $referensi;
	private $ruangan;

	protected $references = array(
		'Referensi' => true,
		'Ruangan' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("penjamin_ruangan", "master"));
		$this->entity = new PenjaminRuanganEntity();

		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['Referensi']) $this->referensi = new ReferensiService();
			if($this->references['Ruangan']) $this->ruangan = new RuanganService(true, array(
				'Referensi' => true,
				'PenjaminRuangan' => false
			));
		}
    }

    public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);

		if($this->includeReferences) {
			foreach($data as &$entity) {				
				if($this->references['Referensi']) {
					$penjamin = $this->referensi->load(array('JENIS' => 10, 'ID' => $entity['PENJAMIN']));
					if(count($penjamin) > 0) $entity['REFERENSI']['PENJAMIN'] = $penjamin[0];
				}
				if($this->references['Ruangan']) {
					$ruangan = $this->ruangan->load(array('JENIS' => 5, 'ID' => $entity['RUANGAN_RS']));
					if(count($ruangan) > 0) $entity['REFERENSI']['RUANGAN_RS'] = $ruangan[0];
				}
			}
		}
		
		return $data;
	}


    public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		
		$id = (int) $this->entity->get('ID');		
		
		if($id == 0) {
			$this->table->insert($this->entity->getArrayCopy());
			$id = $this->table->getLastInsertValue();
		} else {
			$this->table->update($this->entity->getArrayCopy(), array('ID' => $id));
		}

		return array(
			'success' => true,
			'data' => $this->load(array('ID'=>$id))
		);
	}
}
