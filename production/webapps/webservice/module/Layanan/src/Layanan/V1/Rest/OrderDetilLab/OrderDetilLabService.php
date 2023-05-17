<?php
namespace Layanan\V1\Rest\OrderDetilLab;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use DBService\System;
use DBService\generator\Generator;
use General\V1\Rest\Tindakan\TindakanService;

class OrderDetilLabService extends Service
{
	private $tindakan;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("order_detil_lab", "layanan"));
		$this->entity = new OrderDetilLabEntity();
		
		$this->tindakan = new TindakanService();
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$order_id = $this->entity->get('ORDER_ID');
		$tindakan = $this->entity->get('TINDAKAN');
		
		$cek = $this->table->select(array("ORDER_ID" => $order_id, "TINDAKAN" => $tindakan))->toArray();
		if(count($cek) > 0) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("ORDER_ID" => $order_id, "TINDAKAN" => $tindakan));
		} else {
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
		}
		
		return $this->load(array('order_detil_lab.ORDER_ID' => $order_id));
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			$tindakan = $this->tindakan->load(array('ID' => $entity['TINDAKAN']));
			if(count($tindakan) > 0) $entity['REFERENSI']['TINDAKAN'] = $tindakan[0];
			
		}
		
		return $data;
	}
}