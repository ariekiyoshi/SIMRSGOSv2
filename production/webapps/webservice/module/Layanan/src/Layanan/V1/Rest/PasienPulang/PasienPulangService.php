<?php
namespace Layanan\V1\Rest\PasienPulang;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use DBService\System;

use General\V1\Rest\Dokter\DokterService;
use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\Diagnosa\DiagnosaService;

class PasienPulangService extends Service
{
	private $dokter;
	private $referensi;
	private $diagnosa;
	
	protected $references = array(
		'Dokter' => true,
		'Cara' => true,
		'Keadaan' => true,
		'Diagnosa' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pasien_pulang", "layanan"));
		$this->entity = new PasienPulangEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
				
		if($includeReferences) {
			$this->referensi = new ReferensiService();
			if($this->references['Dokter']) $this->dokter = new DokterService();
			if($this->references['Diagnosa']) $this->diagnosa = new DiagnosaService();			
		}
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : false;
		
		if($id) {			
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
			$id = $this->table->getLastInsertValue();
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('ID'=>$id))
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
	
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Dokter']) {
					$dokter = $this->dokter->load(array('ID' => $entity['DOKTER']));
					if(count($dokter) > 0) $entity['REFERENSI']['DOKTER'] = $dokter[0];
				}
				
				if($this->references['Cara']) {
					$cara = $this->referensi->load(array('ID' => $entity['CARA'], 'JENIS' => 45));
					if(count($cara) > 0) $entity['REFERENSI']['CARA'] = $cara[0];
				}
				
				if($this->references['Keadaan']) {
					$keadaan = $this->referensi->load(array('ID' => $entity['KEADAAN'], 'JENIS' => 46));
					if(count($keadaan) > 0) $entity['REFERENSI']['KEADAAN'] = $keadaan[0];
				}
				
				if($this->references['Diagnosa']) {
					if(isset($entity['DIAGNOSA'])) {
						$diagnosa = $this->diagnosa->load(array('CODE' => $entity['DIAGNOSA']));
						if(count($diagnosa) > 0) $entity['REFERENSI']['DIAGNOSA'] = $diagnosa[0];
					}
				}
			}
		}
				
		return $data;
	}
}