<?php
namespace General\V1\Rest\Paket;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use General\V1\Rest\Referensi\ReferensiService;

class PaketService extends Service
{
	private $referensi;
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("paket", "master"));
		$this->entity = new PaketEntity();
		$this->referensi = new ReferensiService();
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);

		if($this->includeReferences) {
			foreach($data as &$entity) {
				$referensi = $this->referensi->load(array('ID' => $entity['KELAS'], 'JENIS'=>19));
				if(count($referensi) > 0) $entity['REFERENSI']['KELAS'] = $referensi[0];
			}
		}
		return $data;
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		
		$id = $this->entity->get('ID');
		
		$cek = $this->table->select(array("ID" => $id))->toArray();
		if(count($cek) > 0) {
			$data = $this->entity->getArrayCopy();
			$this->table->update($data, array("ID" => $id));
		} else {
			$data = $this->entity->getArrayCopy();
			$this->table->insert($data);
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
				if(!$isCount) $select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(!System::isNull($params, 'UNTUK_KUNJUNGAN')) {
				$select->where('FIND_IN_SET('.$params['UNTUK_KUNJUNGAN'].',UNTUK_KUNJUNGAN) > 0');
				unset($params['UNTUK_KUNJUNGAN']);
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}