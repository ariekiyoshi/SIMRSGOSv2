<?php
namespace Aplikasi\V1\Rest\Pengguna;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Aplikasi\Password;

use General\V1\Rest\Referensi\ReferensiService;

class PenggunaService extends Service
{
	private $referensi;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pengguna", "aplikasi"));
		$this->entity = new PenggunaEntity();
		
		$this->referensi = new ReferensiService();
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			$referensi = $this->referensi->load(array('JENIS' => 69,'ID' => $entity['JENIS']));
			if(count($referensi) > 0) $entity['REFERENSI']['JENIS_PENGGUNA'] = $referensi[0];
		}
		
		return $data;
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
			
			if(isset($params['NAMA'])) {
				if(!System::isNull($params, 'NAMA')) {
					$select->where->like('NAMA', '%'.$params['NAMA'].'%');
					unset($params['NAMA']);
				}
			}
			
			/*if(isset($params['PASSWORD'])) {
				if(!System::isNull($params, 'PASSWORD')) {
					//$select->where->like('PASSWORD', new \Zend\Db\Sql\Expression("PASSWORD('".$params['PASSWORD']."')"));
					$select->where(array('PASSWORD', Password::encrypt($params['PASSWORD'])));
					unset($params['PASSWORD']);
				}
			}*/
						
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('ID');
		if(isset($data["PASSWORD"])) {
    		$pass = Password::encrypt($data['PASSWORD']);
    		$this->entity->set('PASSWORD', $pass);
		}
		//$this->entity->set('PASSWORD', new \Zend\Db\Sql\Expression("PASSWORD('".$data['PASSWORD']."')"));
		if($id > 0){
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}
}
