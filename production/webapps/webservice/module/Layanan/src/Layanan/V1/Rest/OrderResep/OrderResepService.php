<?php
namespace Layanan\V1\Rest\OrderResep;

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
use Layanan\V1\Rest\OrderDetilResep\OrderDetilResepService;
use Pendaftaran\V1\Rest\Kunjungan\KunjunganService;

class OrderResepService extends Service
{
	private $ruangan;
	private $referensi;
	private $detilrad;
	private $dokter;
	private $kunjungan;
	
	protected $references = array(
		'Ruangan' => true,
		'Referensi' => true,
		'Dokter' => true,
		'OrderDetil' => true,
		'Kunjungan' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("order_resep", "layanan"));
		$this->entity = new OrderResepEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			if($this->references['Ruangan']) $this->ruangan = new RuanganService();
			if($this->references['Referensi']) $this->referensi = new ReferensiService();
			if($this->references['Dokter']) $this->dokter = new DokterService();
			if($this->references['OrderDetil']) $this->detilresep = new OrderDetilResepService();
			if($this->references['Kunjungan']) $this->kunjungan = new KunjunganService();
		}
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$nomor = is_numeric($this->entity->get('NOMOR')) ? $this->entity->get('NOMOR') : false;
		if($nomor) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("NOMOR" => $nomor, 'STATUS' => 1));
		} else {
			$kunjungan = $this->kunjungan->load(array('kunjungan.NOMOR' => $this->entity->get('KUNJUNGAN')));
			if(count($kunjungan) > 0) {
				$nomor = Generator::generateNoOrderResep($kunjungan[0]['RUANGAN']);
				$this->entity->set('NOMOR', $nomor);
			
				$_data = $this->entity->getArrayCopy();
				$this->table->insert($_data);
			}
		}
		
		$this->SimpanDetilResep($data, $nomor);
		
		return $this->load(array('order_resep.NOMOR' => $nomor));
	}
	
	private function SimpanDetilResep($data, $id) {
		if(isset($data['ORDER_DETIL'])) {
			
			foreach($data['ORDER_DETIL'] as $tgs) {
				$tgs['ORDER_ID'] = $id;
				$this->detilresep->simpan($tgs);
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
					$dokter = $this->dokter->load(array('ID' => $entity['DOKTER_DPJP']));
					if(count($dokter) > 0) $entity['REFERENSI']['DOKTER_DPJP'] = $dokter[0];
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
				$params['order_resep.STATUS'] = $status;
				unset($params['STATUS']);
			}
			
			if(!System::isNull($params, 'NOMOR')) {
				$params['order_resep.NOMOR'] = $params['NOMOR'];
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
					$select->where("order_resep.TANGGAL BETWEEN '".$tanggal." 00:00:00' AND '".$tanggal." 23:59:59'");					
				} else {
					$tanggal = $params["TANGGAL"]["VALUE"];
					$params["order_resep.TANGGAL"] = $tanggal;
				}
				unset($params['TANGGAL']);
			}
			
			if($this->user && $this->privilage) {
				$usr = $this->user;
				$join = "par.RUANGAN = order_resep.TUJUAN";
				if(!System::isNull($params, 'HISTORY')) {
					$join = "(par.RUANGAN = order_resep.TUJUAN OR par.RUANGAN = kjgn.RUANGAN)";
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