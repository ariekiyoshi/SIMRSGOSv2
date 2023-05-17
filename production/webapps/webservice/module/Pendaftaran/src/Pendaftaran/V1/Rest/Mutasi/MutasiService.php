<?php
namespace Pendaftaran\V1\Rest\Mutasi;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\generator\Generator;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Expression;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

use General\V1\Rest\Ruangan\RuanganService;
use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\Dokter\DokterService;
use Pendaftaran\V1\Rest\Pendaftaran\PendaftaranService;
use Pendaftaran\V1\Rest\Kunjungan\KunjunganService;
use Pendaftaran\V1\Rest\Reservasi\ReservasiService;

class MutasiService extends Service
{   
	protected $references = array(
		'Ruangan' => true,
		'Referensi' => true,
		'Kunjungan' => true,
		'Reservasi' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("mutasi", "pendaftaran"));
		$this->entity = new MutasiEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			if($this->references['Ruangan']) $this->ruangan = new RuanganService();
			if($this->references['Referensi']) $this->referensi = new ReferensiService();
			if($this->references['Kunjungan']) $this->kunjungan = new KunjunganService();
			if($this->references['Reservasi']) $this->reservasi = new ReservasiService();
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
				if($this->references['Ruangan']) {
					if(is_object($this->references['Ruangan'])) {
						$references = isset($this->references['Ruangan']->REFERENSI) ? (array) $this->references['Ruangan']->REFERENSI : array();
						$this->ruangan->setReferences($references, true);
						if(isset($this->references['Ruangan']->COLUMNS)) $this->ruangan->setColumns((array) $this->references['Ruangan']->COLUMNS);
					}
					$ruangan = $this->ruangan->load(array('ID' => $entity['TUJUAN']));
					if(count($ruangan) > 0) $entity['REFERENSI']['TUJUAN'] = $ruangan[0];
				}
				if($this->references['Reservasi']) {
					$reservasi = $this->reservasi->load(array('NOMOR' => $entity['RESERVASI']));
					if(count($reservasi) > 0) $entity['REFERENSI']['RESERVASI'] = $reservasi[0];
				}
				if($this->references['Referensi']) {
					$referensi = $this->referensi->load(array('JENIS' => 31,'ID' => $entity['STATUS']));
					if(count($referensi) > 0) $entity['REFERENSI']['STATUS'] = $referensi[0];
				}
			}
		}
		
		return $data;
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);		
		$nomor = is_numeric($this->entity->get('NOMOR')) ? $this->entity->get('NOMOR') : false;
		$success = true;
		
		if($nomor) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("NOMOR" => $nomor));
		} else {
			$kjgn = $this->kunjungan->load(array('kunjungan.NOMOR' => $this->entity->get('KUNJUNGAN')));
			if(count($kjgn) > 0) {
				$nomor = Generator::generateNoMutasi($kjgn[0]['RUANGAN']);
				
				$this->entity->set('NOMOR', $nomor);
				$this->entity->set('OLEH', 1);
				$_data = $this->entity->getArrayCopy();
				$this->table->insert($_data);
			} else $success = false;
		}
				
		return array(
			'success' => $success,
			'data' => $success ? $this->load(array('mutasi.NOMOR' => $nomor)) : null
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
			
			if(!System::isNull($params, 'NOMOR')) {
				$nomor = $params['NOMOR'];
				$params['mutasi.NOMOR'] = $nomor;
				unset($params['NOMOR']);
			}
			
			if(!System::isNull($params, 'STATUS')) {
				$status = $params['STATUS'];
				$params['mutasi.STATUS'] = $status;
				unset($params['STATUS']);
			}
			
			if(!System::isNull($params, 'ASAL')) {
				$asal = $params['ASAL'];
				$params['kjgn.RUANGAN'] = $asal;
				unset($params['ASAL']);
			}
			
			$select->join(
				array('kjgn' => new TableIdentifier('kunjungan', 'pendaftaran')),
				'kjgn.NOMOR = KUNJUNGAN',
				array()
			);
			
			$select->join(
				array('p' => new TableIdentifier('pendaftaran', 'pendaftaran')),
				'p.NOMOR = kjgn.NOPEN',
				array()
			);
			
			if(!System::isNull($params, 'TANGGAL')) {
				$tanggal = substr($params['TANGGAL'], 0, 10);
				$select->where("mutasi.TANGGAL BETWEEN '".$tanggal." 00:00:00' AND '".$tanggal." 23:59:59'");
				unset($params['TANGGAL']);
			}
			
			if($this->user && $this->privilage) {
				$usr = $this->user;
				//$select->where("EXISTS(SELECT 1 FROM aplikasi.pengguna_akses_ruangan par WHERE par.RUANGAN IN (mutasi.TUJUAN, kjgn.RUANGAN) AND par.STATUS = 1 AND par.PENGGUNA = ".$usr.")");	
				$join = "par.RUANGAN = mutasi.TUJUAN";
				if(!System::isNull($params, 'HISTORY')) {
					$join = "(par.RUANGAN = mutasi.TUJUAN OR par.RUANGAN = kjgn.RUANGAN)";
					unset($params['HISTORY']);
				}
				$select->join(
					array('par' => new TableIdentifier('pengguna_akses_ruangan', 'aplikasi')),
					$join,
					array()
				);
				
				$select->where('par.STATUS = 1');
				$select->where('par.PENGGUNA = '.$usr);
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}