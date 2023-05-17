<?php
namespace Layanan\V1\Rest\PasienMeninggal;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use DBService\System;

use General\V1\Rest\Dokter\DokterService;

class PasienMeninggalService extends Service
{
	private $dokter;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pasien_meninggal", "layanan"));
		$this->entity = new PasienMeninggalEntity();
		
		$this->dokter = new DokterService();
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$cek = $this->load(array('KUNJUNGAN'=>$this->entity->get('KUNJUNGAN')));
		if(count($cek) > 0) {
			$this->table->update($this->entity->getArrayCopy(), array("KUNJUNGAN" => $this->entity->get('KUNJUNGAN')));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('KUNJUNGAN'=>$this->entity->get('KUNJUNGAN')))
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
	
		foreach($data as &$entity) {
			$dokter = $this->dokter->load(array('ID' => $entity['DOKTER']));
			if(count($dokter) > 0) $entity['REFERENSI']['DOKTER'] = $dokter[0];
		}
		
		
		return $data;
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
			
			$select->join(
				array('kjgn' => new TableIdentifier('kunjungan', 'pendaftaran')),
				'kjgn.NOMOR = KUNJUNGAN',
				array()
			);
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}