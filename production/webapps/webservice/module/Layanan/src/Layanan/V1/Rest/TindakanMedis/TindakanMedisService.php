<?php
namespace Layanan\V1\Rest\TindakanMedis;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use DBService\System;
use DBService\generator\Generator;
use Layanan\V1\Rest\PetugasTindakanMedis\PetugasTindakanMedisService;
use Pendaftaran\V1\Rest\Kunjungan\KunjunganService;

class TindakanMedisService extends Service
{
	private $petugas;
	private $kunjungan;
	
	protected $references = array(
		'Kunjungan' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("tindakan_medis", "layanan"));
		$this->entity = new TindakanMedisEntity();
		$this->petugas = new PetugasTindakanMedisService();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			if($this->references['Kunjungan']) $this->kunjungan = new KunjunganService();
		}
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {		
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Kunjungan']) {
					if(is_object($this->references['Kunjungan'])) {
						$references = isset($this->references['Kunjungan']->REFERENSI) ? (array) $this->references['Kunjungan']->REFERENSI : array();
						$this->kunjungan->setReferences($references, true);
						if(isset($this->references['Kunjungan']->COLUMNS)) $this->kunjungan->setColumns((array) $this->references['Kunjungan']->COLUMNS);
					}
					$kunjungan = $this->kunjungan->load(array('kunjungan.NOMOR' => $entity['KUNJUNGAN']));
					if(count($kunjungan) > 0) $entity['REFERENSI']['KUNJUNGAN'] = $kunjungan[0];					
				}
			}
		}
		
		return $data;
	}
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : false;
		
		if($id) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("ID" => $id));
		} else {
			$id = Generator::generateIdTindakanMedis();
			
			$this->entity->set('ID', $id);
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
		}
		
		$this->simpanPetugas($data, $id);
		
		return array(
			'success' => true,
			'data' => $this->load(array('tindakan_medis.ID' => $id))
		);
	}
	
	private function simpanPetugas($data, $id) {
		if(isset($data['PETUGAS_MEDIS'])) {
			foreach($data['PETUGAS_MEDIS'] as $tgs) {
				$tgs['TINDAKAN_MEDIS'] = $id;
				$this->petugas->simpan($tgs);
			}
		}
	}
	
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) {
				$select->columns($columns);
			}
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				if(!$isCount) $select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(isset($params["ID"])) {
				$params["tindakan_medis.ID"] = $params["ID"];
				unset($params["ID"]);
			}
			
			if(!System::isNull($params, 'STATUS')) {
				$status = $params['STATUS'];
				$params['tindakan_medis.STATUS'] = $status;
				unset($params['STATUS']);
			}
			
			$select->join(
				array('t' => new TableIdentifier('tindakan', 'master')),
				't.ID = TINDAKAN',
				array('TINDAKAN_DESKRIPSI' => 'NAMA')
			);
			
			$select->join(
				array('u' => new TableIdentifier('pengguna', 'aplikasi')),
				'u.ID = OLEH',
				array()
			);
			
			$select->join(
				array('p' => new TableIdentifier('pegawai', 'master')),
				'p.NIP = u.NIP',
				array('NAMA_PENGGUNA' => 'NAMA'),
				Select::JOIN_LEFT
			);
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}