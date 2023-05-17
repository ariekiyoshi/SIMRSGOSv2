<?php
namespace Pendaftaran\V1\Rest\JawabanKonsul;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\generator\Generator;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Expression;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

use Pendaftaran\V1\Rest\Konsul\KonsulService;
use General\V1\Rest\Dokter\DokterService;

class JawabanKonsulService extends Service
{   
	private $konsul;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("jawaban_konsul", "pendaftaran"));
		$this->entity = new JawabanKonsulEntity();
		
		$this->konsul = new KonsulService();
		$this->dokter = new DokterService();
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			$konsul = $this->konsul->load(array('NOMOR' => $entity['NOMOR']));
			if(count($konsul) > 0) $entity['REFERENSI']['KONSUL'] = $konsul[0];
			
			$dokter = $this->dokter->load(array('ID' => $entity['DOKTER']));
			if(count($dokter) > 0) $entity['REFERENSI']['DOKTER'] = $dokter[0];
		}
		
		return $data;
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$found = $this->load(array('jawaban_konsul.NOMOR' => $this->entity->get('NOMOR')));
		if(count($found) > 0) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("NOMOR" => $this->entity->get('NOMOR')));
		} else {	
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
		}
				
		return array(
			'success' => true,
			'data' => $this->load(array('jawaban_konsul.NOMOR' => $this->entity->get('NOMOR')))
		);
	}
}