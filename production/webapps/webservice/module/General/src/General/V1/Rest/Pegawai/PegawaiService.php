<?php
namespace General\V1\Rest\Pegawai;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use General\V1\Rest\Referensi\ReferensiService;
use Pegawai\V1\Rest\KartuIdentitas\Service as KartuIdentitasService;

class PegawaiService extends Service
{
	private $referensi;
	private $kartuidentitas;
	
	public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pegawai", "master"));
		$this->entity = new PegawaiEntity();
		
		$this->referensi = new ReferensiService();
		$this->kartuidentitas = new KartuIdentitasService();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		
		$nip = $this->entity->get('NIP');				
		$cek = $this->table->select(array("NIP" => $nip))->toArray();
		if(isset($data['NIP_BARU'])) {
			$this->entity->set('NIP', $data['NIP_BARU']);
		}
		if(count($cek) > 0) {
			//$data = $this->entity->getArrayCopy();
			$this->table->update($this->entity->getArrayCopy(), array("NIP" => $nip));
		} else {
			//$data = $this->entity->getArrayCopy();
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		$this->simpanKIP($data, $nip);
		
		return array(
			'success' => true,
			'data' => $data
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			if(isset($entity['PROFESI'])) {
				$profesi = $this->referensi->load(array('JENIS' => 36,'ID' => $entity['PROFESI']));
				if(count($profesi) > 0) $entity['REFERENSI']['PROFESI'] = $profesi[0];
			}
			if(isset($entity['AGAMA'])) {
				$agama = $this->referensi->load(array('JENIS' => 1,'ID' => $entity['AGAMA']));
				if(count($agama) > 0) $entity['REFERENSI']['AGAMA'] = $agama[0];
			}
			if(isset($entity['NIP'])) {
				$kartuidentitas = $this->kartuidentitas->load(array('NIP' => $entity['NIP']));
				if(count($kartuidentitas) > 0) $entity['KARTU_IDENTITAS'] = $kartuidentitas;
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
				if(!$isCount) $select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
				
			} else $select->offset(0)->limit($this->limit);
			
			if(isset($params['NAMA'])) {
				if(!System::isNull($params, 'NAMA')) {
					$select->where->like('NAMA', '%'.$params['NAMA'].'%');
					unset($params['NAMA']);
				}
			}
			if(isset($params['ALAMAT'])) {
				if(!System::isNull($params, 'ALAMAT')) {
					$select->where->like('ALAMAT', $params['ALAMAT'].'%');
					unset($params['ALAMAT']);
				}
			}
			if(isset($params['TANGGAL_LAHIR'])) {
				if(!System::isNull($params, 'TANGGAL_LAHIR')) {
					$select->where->equalTo('TANGGAL_LAHIR', substr($params['TANGGAL_LAHIR'],0,10));
					unset($params['TANGGAL_LAHIR']);
				}
			}
			if(isset($params['QUERY'])) if(!System::isNull($params, 'QUERY')){
				$select->where("(NIP LIKE '%".$params['QUERY']."%' OR NAMA LIKE '%".$params['QUERY']."%')");
				unset($params['QUERY']);
			}
			$select->where($params);
			
			$select->order($orders);
		})->toArray();
	}
	
	private function simpanKIP($data, $nip) {
		if(isset($data['KARTU_IDENTITAS'])) {
			foreach($data['KARTU_IDENTITAS'] as $kartu) {
				//$kartu['NIP'] = $nip;				
				$cek = $this->kartuidentitas->load(array('NIP' => $data['NIP']));
				if(count($cek) > 0){
					foreach($cek as $dt){
						$kartu['ID'] = $dt['ID'];
					}
					$this->kartuidentitas->simpan($kartu, false); 	
				} else {
					$this->kartuidentitas->simpan($kartu, true); 
				}
			}
		}
	}
}