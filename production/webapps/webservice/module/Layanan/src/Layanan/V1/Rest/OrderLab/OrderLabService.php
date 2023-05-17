<?php
namespace Layanan\V1\Rest\OrderLab;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use DBService\System;
use DBService\generator\Generator;
use General\V1\Rest\Ruangan\RuanganService;
use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\Dokter\DokterService;
use Layanan\V1\Rest\OrderDetilLab\OrderDetilLabService;
use Pendaftaran\V1\Rest\Kunjungan\KunjunganService;

class OrderLabService extends Service
{
	private $ruangan;
	private $referensi;
	private $detillab;
	private $dokter;
	private $kunjungan;
	
	protected $references = array(
		'Ruangan' => true,
		'Referensi' => true,
		'Dokter' => true,
		'OrderDetil' => true,
		'Kunjungan' => true
	);
	
    public function __construct($includeReferences = true, $references = array())  {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("order_lab", "layanan"));
		$this->entity = new OrderLabEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			if($this->references['Ruangan']) $this->ruangan = new RuanganService();
			if($this->references['Referensi']) $this->referensi = new ReferensiService();
			if($this->references['Dokter']) $this->dokter = new DokterService();
			if($this->references['OrderDetil']) $this->detillab = new OrderDetilLabService();
			if($this->references['Kunjungan']) $this->kunjungan = new KunjunganService();
		}
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$nomor = is_numeric($this->entity->get('NOMOR')) ? $this->entity->get('NOMOR') : false;
		if($nomor) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("NOMOR" => $nomor));
		} else {
			$kunjungan = $this->kunjungan->load(array('kunjungan.NOMOR' => $this->entity->get('KUNJUNGAN')));
			if(count($kunjungan) > 0) {
				$nomor = Generator::generateNoOrderLab($kunjungan[0]['RUANGAN']);
				$this->entity->set('NOMOR', $nomor);
			
				$_data = $this->entity->getArrayCopy();
				$this->table->insert($_data);
			}
		}
		
		$this->SimpanDetilLab($data, $nomor);
		
		return $this->load(array('order_lab.NOMOR' => $nomor));
	}
	
	private function SimpanDetilLab($data, $id) {
		if(isset($data['ORDER_DETIL'])) {
			foreach($data['ORDER_DETIL'] as $tgs) {
				$tgs['ORDER_ID'] = $id;
				$this->detillab->simpan($tgs);
			}
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
				if($this->references['Dokter']) {
					//if(is_object($this->references['Dokter'])) {
					//	$references = isset($this->references['Dokter']->REFERENSI) ? (array) $this->references['Dokter']->REFERENSI : array();
					//	$this->dokter->setReferences($references, true);
					//	if(isset($this->references['Dokter']->COLUMNS)) $this->dokter->setColumns((array) $this->references['Dokter']->COLUMNS);
					//}
					$dokter = $this->dokter->load(array('ID' => $entity['DOKTER_ASAL']));
					if(count($dokter) > 0) $entity['REFERENSI']['DOKTER_ASAL'] = $dokter[0];
				}
				if($this->references['Referensi']) {
					$referensi = $this->referensi->load(array('JENIS' => 31,'ID' => $entity['STATUS']));
					if(count($referensi) > 0) $entity['REFERENSI']['STATUS'] = $referensi[0];
				}
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
			
			if(!System::isNull($params, 'STATUS')) {
				$status = $params['STATUS'];
				$params['order_lab.STATUS'] = $status;
				unset($params['STATUS']);
			}
			
			if(!System::isNull($params, 'NOMOR')) {
				$params['order_lab.NOMOR'] = $params['NOMOR'];
				unset($params['NOMOR']);
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
				if(!is_array($params["TANGGAL"])) {
					$tanggal = substr($params['TANGGAL'], 0, 10);
					$select->where("order_lab.TANGGAL BETWEEN '".$tanggal." 00:00:00' AND '".$tanggal." 23:59:59'");					
				} else {
					$tanggal = $params["TANGGAL"]["VALUE"];
					$params["order_lab.TANGGAL"] = $tanggal;
				}
				unset($params['TANGGAL']);
			}
			
			if($this->user && $this->privilage) {
				$usr = $this->user;
				$join = "par.RUANGAN = order_lab.TUJUAN";
				if(!System::isNull($params, 'HISTORY')) {
					$join = "(par.RUANGAN = order_lab.TUJUAN OR par.RUANGAN = kjgn.RUANGAN)";
					unset($params['HISTORY']);
				}
				$select->join(
					array('par' => new TableIdentifier('pengguna_akses_ruangan', 'aplikasi')),
					$join,
					array()
				);
				$select->where('par.STATUS = 1');
				$select->where('par.PENGGUNA = '.$usr);
				//$select->where("EXISTS(SELECT 1 FROM aplikasi.pengguna_akses_ruangan par WHERE par.RUANGAN = kjgn.RUANGAN AND par.PENGGUNA = ".$usr." AND par.STATUS = 1)");
			}
			
			$select->where($params);
			$select->order($orders);
			//var_dump($select->getSqlString());
		})->toArray();
	}
}