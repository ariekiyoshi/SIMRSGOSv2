<?php
namespace General\V1\Rest\SMFRuangan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\Ruangan\RuanganService;

class SMFRuanganService extends Service
{   
	private $ruangan;
	private $referensi;
	
	protected $references = array(
		'Ruangan' => true,
		'Referensi' => true
	);
	
	public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("smf_ruangan", "master"));
		$this->entity = new SMFRuanganEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			if($this->references['Ruangan']) $this->ruangan = new RuanganService();
			if($this->references['Referensi']) $this->referensi = new ReferensiService();
		}
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
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {				
				if($this->references['Ruangan']) {
					$ruangan = $this->ruangan->load(array('ID' => $entity['RUANGAN']));
					if(count($ruangan) > 0) $entity['REFERENSI']['RUANGAN'] = $ruangan[0];
				}				
				if($this->references['Referensi']) {
					$referensi = $this->referensi->load(array('JENIS' => 26, 'ID' => $entity['SMF']));					
					if(count($referensi) > 0) $entity['REFERENSI']['SMF'] = $referensi[0];
				}
			}
		}
		
		return $data;
	}
	
	public function tambahSemuaSMF($ruangan) {
        $adapter = $this->table->getAdapter();
        $stmt = $adapter->query('CALL master.tambahSemuaSMFKeRuangan(?)');
		$stmt->execute(array($ruangan));
        
        return array(
			'success' => true
		);
    }
}