<?php
namespace Pendaftaran\V1\Rest\TujuanPasien;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use DBService\System;
use Pendaftaran\V1\Rest\Pendaftaran\PendaftaranService;
use General\V1\Rest\Ruangan\RuanganService;
use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\Dokter\DokterService;
use Pendaftaran\V1\Rest\AntrianRuangan\AntrianRuanganService;

class TujuanPasienService extends Service
{
	private $pendaftaran;
	private $ruangan;
	private $referensi;
	private $dokter;
	private $antrianRuangan;
	
	protected $references = array(
		'Pendaftaran' => true,
		'Ruangan' => true,
		'Referensi' => true,
		'Dokter' => true,
		'AntrianRuangan' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("tujuan_pasien", "pendaftaran"));
		$this->entity = new TujuanPasienEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['Pendaftaran']) $this->pendaftaran = new PendaftaranService(true, 
				array(
					'Pasien' => true,
					'PenanggungJawabPasien' => false,
					'TujuanPasien' => false,
					'SuratRujukanPasien' => false,
					'Penjamin' => true,
					'DiagnosaMasuk' => false,
					'Referensi' => false
				)
			);
			if($this->references['Ruangan']) $this->ruangan = new RuanganService();
			if($this->references['Referensi']) $this->referensi = new ReferensiService();
			if($this->references['Dokter']) $this->dokter = new DokterService();
			if($this->references['AntrianRuangan']) $this->antrianRuangan = new AntrianRuanganService(false);
		}
    }
		
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Pendaftaran']) {
					if(is_object($this->references['Pendaftaran'])) {
						$references = isset($this->references['Pendaftaran']->REFERENSI) ? (array) $this->references['Pendaftaran']->REFERENSI : array();
						$this->pendaftaran->setReferences($references, true);
						if(isset($this->references['Pendaftaran']->COLUMNS)) $this->pendaftaran->setColumns((array) $this->references['Pendaftaran']->COLUMNS);
					}
					$pendaftaran = $this->pendaftaran->load(array('NOMOR' => $entity['NOPEN']));
					if(count($pendaftaran) > 0) $entity['REFERENSI']['PENDAFTARAN'] = $pendaftaran[0];
				}
				if($this->references['Ruangan']) {
					if(is_object($this->references['Ruangan'])) {
						$references = isset($this->references['Ruangan']->REFERENSI) ? (array) $this->references['Ruangan']->REFERENSI : array();
						$this->ruangan->setReferences($references, true);
						if(isset($this->references['Ruangan']->COLUMNS)) $this->ruangan->setColumns((array) $this->references['Ruangan']->COLUMNS);
					}
					$ruangan = $this->ruangan->load(array('ID' => $entity['RUANGAN']));
					if(count($ruangan) > 0) $entity['REFERENSI']['RUANGAN'] = $ruangan[0];
				}
				if($this->references['Referensi']) {
					$referensi = $this->referensi->load(array('JENIS' => 24,'ID' => $entity['STATUS']));
					if(count($referensi) > 0) $entity['REFERENSI']['STATUS'] = $referensi[0];
					
					$referensi = $this->referensi->load(array('JENIS' => 26,'ID' => $entity['SMF']));
					if(count($referensi) > 0) $entity['REFERENSI']['SMF'] = $referensi[0];
				}
				if($this->references['Dokter']) {
					$dokter = $this->dokter->load(array('ID' => $entity['DOKTER']));
					if(count($dokter) > 0) $entity['REFERENSI']['DOKTER'] = $dokter[0];
				}
				if($this->references['AntrianRuangan']) {
					if(is_object($this->references['AntrianRuangan'])) {
						$references = isset($this->references['AntrianRuangan']->REFERENSI) ? (array) $this->references['AntrianRuangan']->REFERENSI : array();
						$this->antrianRuangan->setReferences($references, true);
						if(isset($this->references['AntrianRuangan']->COLUMNS)) $this->antrianRuangan->setColumns((array) $this->references['AntrianRuangan']->COLUMNS);
					}
					$antrianRuangan = $this->antrianRuangan->load(array('RUANGAN' => $entity['RUANGAN'], 'JENIS' => 1, 'REF' => $entity['NOPEN']));
					if(count($antrianRuangan) > 0) $entity['REFERENSI']['ANTRIAN'] = $antrianRuangan[0];
				}
			}
		}
		
		return $data;
	}
    
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		$cek = $this->load(array('NOPEN'=>$this->entity->get('NOPEN')));
		if(count($cek) > 0) {
			$this->table->update($this->entity->getArrayCopy(), array("NOPEN" => $this->entity->get('NOPEN')));
		} else {	
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return $this->load(array('NOPEN'=>$this->entity->get('NOPEN')));
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
			
			if(!System::isNull($params, 'STATUS')) {
				$status = $params['STATUS'];
				$params['tujuan_pasien.STATUS'] = $status;
				unset($params['STATUS']);
			}
			
			if(!System::isNull($params, 'RUANGAN')) {
				$asal = $params['RUANGAN'];
				$params['tujuan_pasien.RUANGAN'] = $asal;
				unset($params['RUANGAN']);
			}			
			$select->join(
				array('p' => new TableIdentifier('pendaftaran', 'pendaftaran')),
				'p.NOMOR = tujuan_pasien.NOPEN',
				array()
			);
			
			if(!System::isNull($params, 'TANGGAL')) {				
				$tanggal = substr($params['TANGGAL'], 0, 10);			
				$select->where("p.TANGGAL BETWEEN '".$tanggal." 00:00:00' AND '".$tanggal." 23:59:59'");
				unset($params['TANGGAL']);
			}
			
			if($this->user && $this->privilage) {
				$usr = $this->user;
				$select->join(
					array('par' => new TableIdentifier('pengguna_akses_ruangan', 'aplikasi')),
					'par.RUANGAN = tujuan_pasien.RUANGAN',
					array()
				);
				$select->where('par.STATUS = 1');
				$select->where('par.PENGGUNA = '.$usr);
				//$select->where("EXISTS(SELECT 1 FROM aplikasi.pengguna_akses_ruangan par WHERE par.RUANGAN = kjgn.RUANGAN AND par.PENGGUNA = ".$usr." AND par.STATUS = 1)");
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}