<?php
namespace Aplikasi\V1\Rest\PenggunaAkses;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use Aplikasi\V1\Rest\GroupPenggunaAksesModule\GroupPenggunaAksesModuleService;

class PenggunaAksesService extends Service
{
	private $penggunaaksesmodul;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pengguna_akses", "aplikasi"));
		$this->entity = new PenggunaAksesEntity();
		$this->limit = 1000;
		$this->penggunaaksesmodul = new GroupPenggunaAksesModuleService();
	}
	
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				$select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
								
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : 0;
		
		if($id == 0) {
			$founds = $this->load(array('PENGGUNA'=>$this->entity->get('PENGGUNA'), 'GROUP_PENGGUNA_AKSES_MODULE'=>$this->entity->get('GROUP_PENGGUNA_AKSES_MODULE')));
			if(count($founds) > 0) {
				$id = $founds[0]['ID'];
				$this->entity->set('ID', $id);
			}
		}
		
		if($id > 0){
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
			$id = $this->table->getLastInsertValue();
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('ID' => $id))
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);

			foreach($data as &$entity) {
				$grouppengguna = $this->penggunaaksesmodul->load(array('ID' => $entity['GROUP_PENGGUNA_AKSES_MODULE']));
				if(count($grouppengguna) > 0) $entity['REFERENSI']['GROUP_PENGGUNA'] = $grouppengguna[0];
				
			}
		
		
		return $data;
	}
	/*public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('ID');
		
		if($id > 0){
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
			$id = $this->table->getLastInsertValue();
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('ID' => $id))
		);
	}*/
}
