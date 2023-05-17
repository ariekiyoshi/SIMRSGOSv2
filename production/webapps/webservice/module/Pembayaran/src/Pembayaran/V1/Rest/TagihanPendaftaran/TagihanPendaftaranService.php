<?php
namespace Pembayaran\V1\Rest\TagihanPendaftaran;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Pendaftaran\V1\Rest\Pendaftaran\PendaftaranService;
use Pembayaran\V1\Rest\Tagihan\TagihanService;

class TagihanPendaftaranService extends Service
{	
	private $pendaftaran;
	private $tagihan;
	
	protected $references = array(
		'Pendaftaran' => true,
		'Tagihan' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("tagihan_pendaftaran", "pembayaran"));
		$this->entity = new TagihanPendaftaranEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['Pendaftaran']) $this->pendaftaran = new PendaftaranService();
			if($this->references['Tagihan']) $this->tagihan = new TagihanService();
		}
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);

		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Pendaftaran']) {
					if(is_object($this->references['Pendaftaran'])) {
						$references = isset($this->references['Pendaftaran']->REFERENSI) ? (array) $this->references['Pendaftaran']->REFERENSI : array();
						$this->pendaftaran->setReferences($references, true);
						if(isset($this->references['Pendaftaran']->COLUMNS)) $this->pendaftaran->setColumns((array) $this->references['Pendaftaran']->COLUMNS);
					}
					$pendaftaran = $this->pendaftaran->load(array('NOMOR' => $entity['PENDAFTARAN']));
					if(count($pendaftaran) > 0) $entity['REFERENSI']['PENDAFTARAN'] = $pendaftaran[0];
				}
				
				if($this->references['Tagihan']) {
					if(is_object($this->references['Tagihan'])) {
						$references = isset($this->references['Tagihan']->REFERENSI) ? (array) $this->references['Tagihan']->REFERENSI : array();
						$this->tagihan->setReferences($references, true);
						if(isset($this->references['Tagihan']->COLUMNS)) $this->tagihan->setColumns((array) $this->references['Tagihan']->COLUMNS);						
					}
					$tagihan = $this->tagihan->load(array('ID' => $entity['TAGIHAN']));
					if(count($tagihan) > 0) $entity['REFERENSI']['TAGIHAN'] = $tagihan[0];
				}
			}
		}
		
		return $data;
	}
}
