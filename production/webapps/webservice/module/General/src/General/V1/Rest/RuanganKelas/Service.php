<?php
namespace General\V1\Rest\RuanganKelas;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

use General\V1\Rest\Ruangan\RuanganService;
use General\V1\Rest\Referensi\ReferensiService;

class Service extends DBService
{   
	private $ruangan;
	private $referensi;
	
	protected $references = array(
		'Ruangan' => true,
		'Kelas' => true
	);
	
	public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("ruangan_kelas", "master"));
		$this->entity = new RuanganKelasEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['Ruangan']) $this->ruangan = new RuanganService();
			if($this->references['Kelas']) $this->referensi = new ReferensiService();
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
					$referensi = $this->referensi->load(array('JENIS' => 19,'ID' => $entity['KELAS']));
					if(count($referensi) > 0) $entity['REFERENSI']['KELAS'] = $referensi[0];
				}
			}
		}
		
		return $data;
	}
	
	public function simpan($data, $isCreated = false, $loaded = true) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity = new RuanganKelasEntity();
		$this->entity->exchangeArray($data);
		
		if($isCreated) {
			$this->entity->set('TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));
			$this->table->insert($this->entity->getArrayCopy());
			$id = $this->table->getLastInsertValue();
		} else {
			$id = $this->entity->get("ID");
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		}
		
		if($loaded) return $this->load(array("ID" => $id));
		return $id;
	}
}