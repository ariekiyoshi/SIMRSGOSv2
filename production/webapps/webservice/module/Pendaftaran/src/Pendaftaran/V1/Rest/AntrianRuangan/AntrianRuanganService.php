<?php
namespace Pendaftaran\V1\Rest\AntrianRuangan;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\generator\Generator;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Expression;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

use General\V1\Rest\Ruangan\RuanganService;
use Pendaftaran\V1\Rest\TujuanPasien\TujuanPasienService;

class AntrianRuanganService extends Service
{   
	protected $references = array(
		'ruangan' => true,
		'tujuanPasien' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("antrian_ruangan", "pendaftaran"));
		$this->entity = new AntrianRuanganEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['ruangan']) $this->ruangan = new RuanganService();
			if($this->references['tujuanPasien']) $this->tujuanPasien = new TujuanPasienService(true, array('AntrianRuangan' => false));
		}
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				
				if($this->references['ruangan']) {
					$ruangan = $this->ruangan->load(array('ID' => $entity['RUANGAN']));
					if(count($ruangan) > 0) $entity['REFERENSI']['RUANGAN'] = $ruangan[0];
				}
				
				if($this->references['tujuanPasien']) {
					$tujuanPasien = $this->tujuanPasien->load(array('NOPEN' => $entity['REF']));
					if(count($tujuanPasien) > 0) $entity['REFERENSI']['TUJUAN_PASIEN'] = $tujuanPasien[0];
				}
				
			}
		}
		
		return $data;
	}	
}