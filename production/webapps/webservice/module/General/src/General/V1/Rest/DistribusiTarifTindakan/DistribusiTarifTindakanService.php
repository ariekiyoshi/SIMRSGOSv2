<?php
namespace General\V1\Rest\DistribusiTarifTindakan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use DBService\System;

use General\V1\Rest\Referensi\ReferensiService;

class DistribusiTarifTindakanService extends Service
{
	private $referensi;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("distribusi_tarif_tindakan", "master"));
		$this->entity = new DistribusiTarifTindakanEntity();
		
		$this->referensi = new ReferensiService();

   }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);		
		foreach($data as &$entity) {
			$distribusi = $this->referensi->load(array('JENIS' => 46, 'ID' => $entity['JENIS']));
			if(count($distribusi) > 0) $entity['REFERENSI']['JENIS'] = $distribusi[0];
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
