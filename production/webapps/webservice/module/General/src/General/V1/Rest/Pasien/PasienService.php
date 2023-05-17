<?php
namespace General\V1\Rest\Pasien;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use General\V1\Rest\KeluargaPasien\KeluargaPasienService;
use General\V1\Rest\KIP\KIPService;
use General\V1\Rest\KAP\KAPService;
use General\V1\Rest\KontakPasien\KontakPasienService;
use General\V1\Rest\TempatLahir\TempatLahirService;
use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\Wilayah\WilayahService;

class PasienService extends Service
{   
	private $keluarga;
	private $kip;
	private $kap;
	private $kontakpasien;
	private $tempatlahir;
	private $referensi;
	private $wilayah;
	
	protected $references = array(
		'KeluargaPasien' => true,
		'KIP' => true,
		'KAP' => true,
		'KontakPasien' => true,
		'TempatLahir' => true,
		'Referensi' => true,
		'Wilayah' => true
	);
    
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pasien", "master"));
		$this->entity = new PasienEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['KeluargaPasien']) $this->keluarga = new KeluargaPasienService();
			if($this->references['KIP']) $this->kip = new KIPService();
			if($this->references['KAP']) $this->kap = new KAPService();
			if($this->references['KontakPasien']) $this->kontakpasien = new KontakPasienService();
			if($this->references['TempatLahir']) $this->tempatlahir = new TempatLahirService();
			if($this->references['Referensi']) $this->referensi = new ReferensiService();
			if($this->references['Wilayah']) $this->wilayah = new WilayahService();
		}
    }
    
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);

		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['KeluargaPasien']) {
					$kels = $this->keluarga->load(array('NORM' => $entity['NORM']));
					if(count($kels) > 0) $entity['KELUARGA'] = $kels;
				}
				
				if($this->references['KIP']) {
					$kips = $this->kip->load(array('NORM' => $entity['NORM']));
					if(count($kips) > 0) $entity['KARTUIDENTITAS'] = $kips;
				}
				
				if($this->references['KAP']) {
					$kaps = $this->kap->load(array('NORM' => $entity['NORM']));
					if(count($kaps) > 0) $entity['KARTUASURANSI'] = $kaps;
				}

				if($this->references['KontakPasien']) {
					$kontaks = $this->kontakpasien->load(array('NORM' => $entity['NORM']));
					if(count($kontaks) > 0) $entity['KONTAK'] = $kontaks;
				}

				if($this->references['TempatLahir']) {	
					if(is_numeric($entity['TEMPAT_LAHIR'])) {
						$tempatlahir = $this->tempatlahir->load(array('ID' => $entity['TEMPAT_LAHIR']));
						if($tempatlahir['total'] > 0) $entity['REFERENSI']['TEMPATLAHIR'] = $tempatlahir['data'][0];
					}
				}
				
				if($this->references['Referensi']) {
					$jeniskelamin = $this->referensi->load(array('JENIS' => 2, 'ID' => $entity['JENIS_KELAMIN']));
					if(count($jeniskelamin) > 0) $entity['REFERENSI']['JENISKELAMIN'] = $jeniskelamin[0];
					if(isset($entity['STATUS'])) {
						$statusPasien = $this->referensi->load(array('JENIS' => 13, 'ID' => $entity['STATUS']));
						if(count($statusPasien) > 0) $entity['REFERENSI']['STATUS'] = $statusPasien[0];
					}
				}
				
				if($this->references['Wilayah']) {	
					if(isset($entity['WILAYAH'])) {
						$wilayah = $this->wilayah->load(array('ID' => $entity['WILAYAH']));
						if(count($wilayah) > 0) $entity['REFERENSI']['WILAYAH'] = $wilayah[0];
					}
				}
			}
		}
		
		return $data;
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		
		$norm = (int) $this->entity->getNorm();
		
		if($norm == 0) {
			if(isset($data['NORM_MANUAL'])) {
				$norm = is_numeric($data['NORM_MANUAL']) ? $data['NORM_MANUAL'] : 0;
				if($norm > 0) $this->entity->set('NORM', $data['NORM_MANUAL']);
			}
			$this->entity->set('TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));
			$this->table->insert($this->entity->getArrayCopy());
			$norm = $this->table->getLastInsertValue();
		} else {
			$this->table->update($this->entity->getArrayCopy(), array('NORM' => $norm));
		}
		
		$this->simpanKeluarga($data, $norm);
		$this->simpanKIP($data, $norm);
		$this->simpanKontakPasien($data, $norm);			
		
		if($norm > 0) {
			$results = $this->load(array('NORM'=>$norm));
			if(count($results) > 0) $data = $results[0];
			return array(
				'success' => true,
				'data' => $data
			);
		} else {
			return array(
				'success' => false
			);
		}
	}
	
	private function simpanKeluarga($data, $norm) {
		if(isset($data['KELUARGA'])) {
			foreach($data['KELUARGA'] as $kel) {
				$kel['NORM'] = $norm;
				$this->keluarga->simpan($kel); 
			}
		}
	}
	
	private function simpanKIP($data, $norm) {
		if(isset($data['KARTUIDENTITAS'])) {
			foreach($data['KARTUIDENTITAS'] as $kartu) {
				$kartu['NORM'] = $norm;				
				$this->kip->simpan($kartu); 
			}
		}
	}
	
	private function simpanKontakPasien($data, $norm) {
		if(isset($data['KONTAK'])) {
			foreach($data['KONTAK'] as $kontak) {
				$kontak['NORM'] = $norm;
				$this->kontakpasien->simpan($kontak); 
			}
		}
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
			
			if(!System::isNull($params, 'NAMA')) {
				$select->where->like('NAMA', '%'.$params['NAMA'].'%');
				unset($params['NAMA']);
			}
			if(!System::isNull($params, 'ALAMAT')) {
				if(trim($params['ALAMAT']) != '') $select->where->like('ALAMAT', $params['ALAMAT'].'%');
				unset($params['ALAMAT']);
			}
			if(!System::isNull($params, 'TANGGAL_LAHIR')) {
				if(trim($params['TANGGAL_LAHIR']) != '') $select->where->equalTo('TANGGAL_LAHIR', substr($params['TANGGAL_LAHIR'],0,10));
				unset($params['TANGGAL_LAHIR']);
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}
