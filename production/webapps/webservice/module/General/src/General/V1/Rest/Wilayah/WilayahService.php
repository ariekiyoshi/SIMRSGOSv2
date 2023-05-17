<?php
namespace General\V1\Rest\Wilayah;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use General\V1\Rest\JenisWilayah\JenisWilayahService;

class WilayahService extends Service
{
	private $jeniswilayah;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("wilayah", "master"));
		$this->entity = new WilayahEntity();
		$this->limit = 1000;
		
		$this->jeniswilayah = new JenisWilayahService();
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			if(isset($entity['JENIS'])) {
				$jeniswilayah = $this->jeniswilayah->load(array('ID' => $entity['JENIS']));
				if(count($jeniswilayah) > 0) $entity['REFERENSI']['JENIS_WILAYAH'] = $jeniswilayah[0];
			}
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
			
			if(isset($params['ID'])) $select->where->like('ID', $params['ID'].'%');
			if(isset($params['JENIS'])) $select->where(array('JENIS' => $params['JENIS']));
			if(isset($params['DESKRIPSI'])) $select->where->like('DESKRIPSI', $params['DESKRIPSI'].'%');
			
			$select->order($orders);
		})->toArray();
	}
	
	public function simpan__($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = $this->entity->get('ID');
        $jenis = $this->entity->get('JENIS');
		if($jenis > 0) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("ID" => $id, "JENIS" => $jenis));
		} else {
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
		}
		
		return array(
			'success' => true
		);
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		//var_dump($data);
		$id = (int) $this->entity->get('ID');
		
		if($id == 0) {
			if(isset($data['PARENT'])) {
				
				if($data['PARENT']['ID'] == 0){
					$var['PARENT']['JENIS'] = 1;
					$var['PARENT']['start'] = 0;
					$var['PARENT']['limit'] = 1;
					$cari = $this->query(array('*'), $var['PARENT'], false, array("ID DESC"));
				}else{
					$var['PARENT']['ID'] = $data['PARENT']['ID'];
					$var['PARENT']['JENIS'] = $data['PARENT']['JENIS'];
					$var['PARENT']['start'] = 0;
					$var['PARENT']['limit'] = 1;
					$cari = $this->query(array('*'), $var['PARENT'], false, array("ID DESC"));
				}
				
				if(count($cari) > 0){
					if($data['PARENT']['ID'] == 0){
						$this->entity->set('ID',(intval($cari[0]['ID'])+1));
						$this->entity->set('JENIS', $data['PARENT']['JENIS']);
					}else{
						$this->entity->set('ID', $data['PARENT']['ID'].str_pad((intval(substr($cari[0]['ID'],-2))+1), 2, 0, STR_PAD_LEFT));
						$this->entity->set('JENIS', $data['PARENT']['JENIS']);
					}				
				} else {
					if($data['PARENT']['JENIS'] == 1){
						$this->entity->set('ID', $data['PARENT']['ID'].'11');
						$this->entity->set('JENIS', $data['PARENT']['JENIS']);
					}else{
						$this->entity->set('ID', $data['PARENT']['ID'].'01');
						$this->entity->set('JENIS', $data['PARENT']['JENIS']);
					}
				}
				$id = $this->entity->get('ID');
				$data = $this->entity->getArrayCopy();	
				$this->table->insert($data);
			}
		} else {
			$this->table->update($this->entity->getArrayCopy(), array('ID' => $id));
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('ID' => $id))
		);
	}
}