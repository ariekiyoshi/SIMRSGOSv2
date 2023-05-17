<?php
namespace General\V1\Rest\GroupTindakanLab;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use General\V1\Rest\Tindakan\TindakanService;

class GroupTindakanLabService extends Service
{
	private $tindakan;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("group_tindakan_lab", "master"));
		$this->entity = new GroupTindakanLabEntity();
		
		$this->tindakan = new TindakanService();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		
		$group_lab = $this->entity->get('GROUP_LAB');
		$tindakan = $this->entity->get('TINDAKAN');
		
		$cek = $this->table->select(array("GROUP_LAB" => $group_lab, "TINDAKAN" => $tindakan))->toArray();
		if(count($cek) > 0) {
			$data = $this->entity->getArrayCopy();
			$this->table->update($data, array("GROUP_LAB" => $group_lab, "TINDAKAN" => $tindakan));
		} else {
			$data = $this->entity->getArrayCopy();
			$this->table->insert($data);
		}
		
		return array(
			'success' => true,
			'data' => $data
		);
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