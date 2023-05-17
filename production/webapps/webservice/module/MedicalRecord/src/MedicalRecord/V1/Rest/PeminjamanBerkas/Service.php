<?php
namespace MedicalRecord\V1\Rest\PeminjamanBerkas;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use General\V1\Rest\Pasien\PasienService;

class Service extends DBService {
    
    private $pasien;
    protected $references = array(
        'Pasien' => true
    );
	
	public function __construct($includeReferences = true, $references = array()) {
	    $this->config["entityName"] = "\\MedicalRecord\\V1\\Rest\\PeminjamanBerkas\\PeminjamanBerkasEntity";
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("peminjaman_berkas", "medicalrecord"));
		$this->entity = new PeminjamanBerkasEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;

		if($includeReferences) {
		    if($this->references['Pasien']) $this->pasien = new PasienService();
		}
		
	
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
	    $data = parent::load($params, $columns, $orders);
	    if($this->includeReferences) {
	        foreach($data as &$entity) {
	            if($this->references['Pasien']) {
	                if(is_object($this->references['Pasien'])) {
	                    $references = isset($this->references['Pasien']->REFERENSI) ? (array) $this->references['Pasien']->REFERENSI : array();
	                    $this->pasien->setReferences($references, true);
	                    if(isset($this->references['Pasien']->COLUMNS)) $this->pasien->setColumns((array) $this->references['Pasien']->COLUMNS);
	                }
	                $pasien = $this->pasien->load(array('NORM'=>$entity['NORM']));
	                if(count($pasien) > 0) $entity['REFERENSI']['PASIEN'] = $pasien[0];
	            }
	        }
	        
	        return $data;
	    }
	}
	
	
	
	
	
	
}