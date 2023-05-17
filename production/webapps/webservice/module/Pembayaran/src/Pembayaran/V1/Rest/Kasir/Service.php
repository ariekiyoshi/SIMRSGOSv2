<?php
namespace Pembayaran\V1\Rest\Kasir;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService
{	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("kasir", "pembayaran"));
		$this->entity = new KasirEntity();
    }
	
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		

		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				if(!$isCount) $select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(isset($params['QUERY'])) {
				if(!System::isNull($params, 'QUERY')) {
					$select->where("(NIP LIKE '%".$params['QUERY']."%' OR NAMA LIKE '%".$params['QUERY']."%')");
					unset($params['QUERY']);
				}
			}			
			$select->where($params);
			$select->order($orders);			
		})->toArray();
	}
}