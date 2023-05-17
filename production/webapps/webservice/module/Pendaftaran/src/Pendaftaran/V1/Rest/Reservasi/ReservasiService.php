<?php
namespace Pendaftaran\V1\Rest\Reservasi;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\generator\Generator;
use Zend\json\Json;
use DBService\System;
use DBService\Service;
use General\V1\Rest\RuangKamarTidur\RuangKamarTidurService;

class ReservasiService extends Service
{
	private $ruangKamar;
	
	protected $references = array(
		'RuangKamar' => true
	);
	
	public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("reservasi", "pendaftaran"));
		$this->entity = new ReservasiEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
				
		if($includeReferences) {			
			if($this->references['RuangKamar']) $this->ruangKamar = new RuangKamarTidurService();
		}
    }	
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$nomor = $this->entity->get('NOMOR');
		$cek = $this->table->select(array("NOMOR" => $nomor))->toArray();  
		
		if(count($cek) > 0) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("NOMOR" => $nomor, 'STATUS' => 1));
		} else {
			$nomor = Generator::generateNoReservasi();			
			$this->entity->set('NOMOR', $nomor);
			$this->entity->set('TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));			
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('NOMOR' => $nomor))
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['RuangKamar']) {
					$ruangKamar = $this->ruangKamar->load(array('ID' => $entity['RUANG_KAMAR_TIDUR']));
					if(count($ruangKamar) > 0) $entity['REFERENSI']['RUANG_KAMAR_TIDUR'] = $ruangKamar[0];
				}
			}
		}
		
		return $data;
	}
}