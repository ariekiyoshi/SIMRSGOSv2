<?php
namespace General\V1\Rest\Prosedur;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

class ProsedurService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("mrconso", "master"));
		$this->entity = new ProsedurEntity();
    }
		
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);					
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				$select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			$select->where(array('SAB' => 'ICD9CM_2005'));
			$select->where("TTY IN ('PX', 'PT')");
			
			if(isset($params['CODE'])) if(!System::isNull($params, 'CODE')) $select->where->like('CODE', $params['CODE']);
			if(isset($params['STR'])) if(!System::isNull($params, 'STR')) $select->where->like('STR', '%'.$params['STR'].'%');
			if(isset($params['QUERY'])) if(!System::isNull($params, 'QUERY')) $select->where("(STR LIKE '%".$params['QUERY']."%' OR CODE LIKE '".$params['QUERY']."%')");
			$select->order($orders);
		})->toArray();
	}
}
