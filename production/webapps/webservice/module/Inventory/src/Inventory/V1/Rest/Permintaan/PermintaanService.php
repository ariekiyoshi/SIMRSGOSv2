<?php
namespace Inventory\V1\Rest\Permintaan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use DBService\generator\Generator;

use Inventory\V1\Rest\PermintaanDetil\PermintaanDetilService;
use General\V1\Rest\Ruangan\RuanganService;

class PermintaanService extends Service
{
	private $permintaandetil;
	private $ruangan;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("permintaan", "inventory"));
		$this->entity = new PermintaanEntity();
		
		$this->permintaandetil = new PermintaanDetilService();
		$this->ruangan = new RuanganService();		
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$nomor = is_numeric($this->entity->get('NOMOR')) ? $this->entity->get('NOMOR') : 0;
		$asal = $this->entity->get('ASAL');
		if($nomor == 0) {
			$nomor = Generator::generateNoPermintaan($asal);
			$this->entity->set('NOMOR', $nomor);
			$this->table->insert($this->entity->getArrayCopy());
			
		} else {
			$this->table->update($this->entity->getArrayCopy(), array("NOMOR" => $nomor));
		}
		
		$this->SimpanDetilPermintaan($data, $nomor);
		
		return array(
			'success' => true
		);
	}
	
	private function SimpanDetilPermintaan($data, $id) {
		if(isset($data['PERMINTAAN_DETIL'])) {
			
			foreach($data['PERMINTAAN_DETIL'] as $dtl) {
				$dtl['PERMINTAAN'] = $id;
				$this->permintaandetil->simpan($dtl);
			}
		}
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		foreach($data as &$entity) {	
			$asal = $this->ruangan->load(array('ID' => $entity['ASAL']));
			if(count($asal) > 0) $entity['REFERENSI']['ASAL'] = $asal[0];
			
			$tujuan= $this->ruangan->load(array('ID' => $entity['TUJUAN']));
			if(count($tujuan) > 0) $entity['REFERENSI']['TUJUAN'] = $tujuan[0];
		}	
		return $data;
	}
}
