<?php
namespace General\V1\Rest\DistribusiTarifPaketDetil;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service as DBService;
use Zend\Db\Sql\Select;
use DBService\System;

use General\V1\Rest\PaketDetil\PaketDetilService;

class Service extends DBService
{
	private $paketDetil;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("distribusi_tarif_paket_detil", "master"));
		$this->entity = new DistribusiTarifPaketDetilEntity();
		
		$this->paketDetil = new PaketDetilService();

   }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);		
		foreach($data as &$entity) {
			$paketDetil = $this->paketDetil->load(array('ID' => $entity['PAKET_DETIL']));
			if(count($paketDetil) > 0) $entity['REFERENSI']['PAKET_DETIL'] = $paketDetil[0];
		}
		
		return $data;
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('ID');
		$cek = $this->table->select(array("ID" => $id))->toArray();
		if(count($cek) > 0) {
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array("ID" => $id))
		);
	}
}
