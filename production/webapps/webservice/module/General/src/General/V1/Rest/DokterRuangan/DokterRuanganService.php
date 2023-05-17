<?php
namespace General\V1\Rest\DokterRuangan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

use General\V1\Rest\Ruangan\RuanganService;
use General\V1\Rest\Dokter\DokterService;

class DokterRuanganService extends Service
{
	private $ruangan;
	private $dokter;	
	
	protected $references = array(
		'Ruangan' => true,
		'Dokter' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("dokter_ruangan", "master"));
		$this->entity = new DokterRuanganEntity();
		
		$this->limit = 1000;
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			if($this->references['Ruangan']) $this->ruangan = new RuanganService();
			if($this->references['Dokter']) $this->dokter = new DokterService();
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
				if($this->references['Dokter']) {
					$dokter = $this->dokter->load(array('ID' => $entity['DOKTER']));
					if(count($dokter) > 0) $entity['REFERENSI']['DOKTER'] = $dokter[0];
				}
			}
		}
		
		return $data;
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : 0;
		$this->entity->set('TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));
        if($id > 0) {
            $this->table->update($this->entity->getArrayCopy(), array('ID' => $id));
        } else {
            $this->table->insert($this->entity->getArrayCopy());
        }
        
		return array(
			'success' => true
		);
	}
    
    public function tambahSemuaDokter($ruangan) {
        $adapter = $this->table->getAdapter();
        $stmt = $adapter->query('CALL master.tambahSemuaDokterKeRuangan(?)');
		$stmt->execute(array($ruangan));
        
        return array(
			'success' => true
		);
    }
}