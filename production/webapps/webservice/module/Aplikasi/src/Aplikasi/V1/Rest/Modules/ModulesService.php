<?php
namespace Aplikasi\V1\Rest\Modules;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

class ModulesService extends Service
{
	private $referensi;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("modules", "aplikasi"));
		$this->entity = new ModulesEntity();
		$this->limit = 1000;		
    }
   
	public function loadTree($params = array(), $columns = array('*')) {
		$params['LEVEL'] = 1;
		$data =  $this->queryRekursif($params, $columns);
		return array(
			'total' =>count($data),
			'data'=>$data
		);
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
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
			
			if(isset($params['ID'])) {
				$select->where->like('modules.ID', $params['ID'].'%');
				unset($params['ID']);
			}
			
			if(isset($params['GROUP_PENGGUNA'])) {
				$select->join(
					array('gpam' => new TableIdentifier('group_pengguna_akses_module', 'aplikasi')),
					'gpam.MODUL = modules.ID',
					array('GPAM' => 'ID')
				);
				$select->where(array('modules.STATUS' => 1));
				$select->where(array('gpam.STATUS' => 1));
			}	
			
			/*if(isset($params['PENGGUNA'])) {
				$select->join(
					array('pakses' => new TableIdentifier('pengguna_akses', 'aplikasi')),
					'pakses.PENGGUNA = pengguna.ID',
					array()
				);
				$select->where(array('pengguna.STATUS' => 1));
				$select->where(array('pakses.STATUS' => 1));
				unset($params['PENGGUNA']);
			}*/
			
			if(isset($params['GROUP_PENGGUNA_AKSES_MODULE'])) {
				$select->join(
					array('pakses' => new TableIdentifier('pengguna_akses', 'aplikasi')),
					'pakses.GROUP_PENGGUNA_AKSES_MODULE = group_pengguna_akses_module.ID',
					array()
				);
				$select->where(array('group_pengguna_akses_module.STATUS' => 1));
				$select->where(array('pakses.STATUS' => 1));
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
	
	private function queryRekursif($params = array(), $columns = array('*')){
		$row = $this->query($columns, $params, true);		
		$row = (int) $row[0]['rows'];
		$data = $this->query($columns, $params, false, array('ID ASC'));			
		
		if(count($data) == 0 && $params['LEVEL'] < 5) {
			$params['LEVEL'] = $params['LEVEL'] + 1;
			return $this->queryRekursif($params, $columns);
		}
			
		$a=0;
		foreach($data as $dt){
			$params['ID'] = $dt['ID'];
			$params['LEVEL'] = $dt['LEVEL']+1;					
			
			$childs = $this->queryRekursif($params, $columns);
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
