<?php
namespace General\V1\Rest\GroupLab;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

class GroupLabService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("group_lab", "master"));
		$this->entity = new GroupLabEntity();
		$this->limit = 1000;
    }
   
	public function loadTree($params = array()) {
		$params['JENIS'] = 1;
		$data =  $this->queryRekursif($params);
		return array(
			'total' =>count($data),
			'data'=>$data
		);
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		
		$id = (int) $this->entity->get('ID');
		if($id == 0) {
			if(isset($data['PARENT'])) {
				$data['PARENT']['JENIS']++;
				$data['PARENT']['start'] = 0;
				$data['PARENT']['limit'] = 1;
				$cari = $this->query(array('*'), $data['PARENT'], false, array("ID DESC"));
				
				if(count($cari) > 0){
					$this->entity->set('ID', $data['PARENT']['ID'].str_pad((intval(substr($cari[0]['ID'],-2))+1), 2, 0, STR_PAD_LEFT));
				} else {
					$this->entity->set('ID', $data['PARENT']['ID'].'01');
				}
				$data = $this->entity->getArrayCopy();	
				$this->table->insert($data);
			}
		} else {			 
			$this->table->update($this->entity->getArrayCopy(), array('ID' => $id));
		}
		
		return array(
			'success' => true,
			'data' => $data
		);
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
	
	private function queryRekursif($params = array()){
		$row = $this->query(array('*'), $params, true);		
		$row = (int) $row[0]['rows'];
		$data = $this->query(array('*'), $params);
		$a=0;
		foreach($data as $dt){
			$params['ID'] = $dt['ID'];
			$params['JENIS'] = $dt['JENIS']+1;
			$childs = $this->queryRekursif($params);
			if(count($childs) > 0){
				$data[$a]['children'] = $childs;
				$data[$a]['leaf'] = false;
				$data[$a]['cls'] = 'folder';
			}else{
				$data[$a]['leaf'] = true;
				$data[$a]['cls'] = 'file';
			}
			$a++;
		}
		
		return $data;
	}
}
