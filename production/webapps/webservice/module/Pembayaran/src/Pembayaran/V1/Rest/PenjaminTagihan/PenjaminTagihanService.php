<?php
namespace Pembayaran\V1\Rest\PenjaminTagihan;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Zend\Db\Sql\TableIdentifier;
use General\V1\Rest\Referensi\ReferensiService;

class PenjaminTagihanService extends Service
{
	private $referensi;

	protected $references = array(
		'Referensi' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("penjamin_tagihan", "pembayaran"));
		$this->entity = new PenjaminTagihanEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['Referensi']) $this->referensi = new ReferensiService();
		}
    }
	
	public function simpan($data, $isCreated = false, $loaded = true) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity = new PenjaminTagihanEntity();
		$this->entity->exchangeArray($data);
		
		$params = [
			"TAGIHAN" => $data["TAGIHAN"],
			"PENJAMIN" => $data["PENJAMIN"]
		];
		
		if($isCreated) {
			$this->table->insert($this->entity->getArrayCopy());
		} else {
			$this->table->update($this->entity->getArrayCopy(), $params);
		}
		
		if($loaded) return $this->load($params);
		return $id;
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);

		if($this->includeReferences) {
			foreach($data as &$entity) {				
				if($this->references['Referensi']) {
					$penjamin = $this->referensi->load(array('JENIS' => 10, 'ID' => $entity['PENJAMIN']));
					if(count($penjamin) > 0) $entity['REFERENSI']['PENJAMIN'] = $penjamin[0];
					
					$kelas = $this->referensi->load(array('JENIS' => 19, 'ID' => $entity['KELAS_KLAIM']));
					if(count($kelas) > 0) $entity['REFERENSI']['KELAS_KLAIM'] = $kelas[0];
				}
			}
		}
		
		return $data;
	}	
}
