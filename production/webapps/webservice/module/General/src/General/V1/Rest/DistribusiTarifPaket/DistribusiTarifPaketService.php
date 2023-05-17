<?php
namespace General\V1\Rest\DistribusiTarifPaket;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use DBService\System;

use General\V1\Rest\Paket\PaketService;

class DistribusiTarifPaketService extends Service
{
	private $paket;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("distribusi_tarif_paket", "master"));
		$this->entity = new DistribusiTarifPaketEntity();
		
		$this->paket = new PaketService();

   }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);		
		foreach($data as &$entity) {
			$paket = $this->paket->load(array('ID' => $entity['PAKET']));
			if(count($paket) > 0) $entity['REFERENSI']['PAKET'] = $paket[0];
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
