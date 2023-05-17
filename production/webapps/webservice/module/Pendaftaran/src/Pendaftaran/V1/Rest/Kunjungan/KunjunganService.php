<?php
namespace Pendaftaran\V1\Rest\Kunjungan;

use DBService\DatabaseService;
use DBService\Service;
use DBService\System;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\TableIdentifier;
use DBService\generator\Generator;
use General\V1\Rest\Ruangan\RuanganService;
use General\V1\Rest\Referensi\ReferensiService;
use Pendaftaran\V1\Rest\Pendaftaran\PendaftaranService;
use General\V1\Rest\RuangKamarTidur\RuangKamarTidurService;
use Layanan\V1\Rest\PasienPulang\PasienPulangService;
use Pembatalan\V1\Rest\PembatalanKunjungan\PembatalanKunjunganService;
use Layanan\V1\Rest\OrderLab\OrderLabService;

use Pendaftaran\V1\Rest\Mutasi\MutasiService;

class KunjunganService extends Service
{
	private $ruangan;
	private $referensi;
	private $pendaftaran;
	private $ruangKamarTidur;
	private $konsul;
	private $lab;
	private $rad;
	private $farmasi;
	private $pulang;
	private $pembatalan;
	private $orderlab;
	private $orderrad;
	private $mutasi;
	private $resep;
	
	protected $references = array(
		'Ruangan' => true,
		'Referensi' => true,
		'Pendaftaran' => true,
		'RuangKamarTidur' => true,
		'PasienPulang' => true,
		'Pembatalan' => true,
	    'Perujuk' => true,
	    'Mutasi' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("kunjungan", "pendaftaran"));
		$this->entity = new KunjunganEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
				
		if($includeReferences) {
			if($this->references['Ruangan']) $this->ruangan = new RuanganService();
			if($this->references['Referensi']) $this->referensi = new ReferensiService();
			if($this->references['Pendaftaran']) $this->pendaftaran = new PendaftaranService();
			if($this->references['RuangKamarTidur']) $this->ruangKamarTidur = new RuangKamarTidurService();
			if($this->references['PasienPulang']) $this->pulang = new PasienPulangService();
			if($this->references['Pembatalan']) $this->pembatalan = new PembatalanKunjunganService(false);
			if($this->references['Perujuk']) {
				$this->orderlab = new OrderLabService(true, array(
					'Ruangan' => false,
					'Referensi' => false,
					'Dokter' => true,
					'OrderDetil' => false,
					'Kunjungan' => false
				));
			}
			if($this->references['Mutasi']) $this->mutasi = new MutasiService(false);
		}
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		$nomor = is_numeric($this->entity->get('NOMOR')) ? $this->entity->get('NOMOR') : 0;
		
		if($nomor == 0) {
			$ruangan = $this->entity->get('RUANGAN');
			$nomor = Generator::generateNoKunjungan($ruangan);
			$this->entity->set('NOMOR', $nomor);
			$this->entity->set('MASUK', System::getSysDate($this->table->getAdapter()));
			$this->entity->set('DITERIMA_OLEH', 1);
			$this->table->insert($this->entity->getArrayCopy());
		} else {			
			$this->table->update($this->entity->getArrayCopy(), array('NOMOR' => $nomor));
		}
		
		return $this->load(array('kunjungan.NOMOR' => $nomor));
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
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
					$referensi = $this->referensi->load(array('JENIS' => 31,'ID' => $entity['STATUS']));
					if(count($referensi) > 0) $entity['REFERENSI']['STATUS'] = $referensi[0];
					
					if($entity['TITIPAN'] == 1 && isset($entity['TITIPAN_KELAS'])) {
						$referensi = $this->referensi->load(array('JENIS' => 19,'ID' => $entity['TITIPAN_KELAS']));
						if(count($referensi) > 0) $entity['REFERENSI']['TITIPAN_KELAS'] = $referensi[0];
					}
				}
				if($this->references['Pendaftaran']) {
					if(is_object($this->references['Pendaftaran'])) {
						$references = isset($this->references['Pendaftaran']->REFERENSI) ? (array) $this->references['Pendaftaran']->REFERENSI : array();
						$this->pendaftaran->setReferences($references, true);
						if(isset($this->references['Pendaftaran']->COLUMNS)) $this->pendaftaran->setColumns((array) $this->references['Pendaftaran']->COLUMNS);
					}
					$pendaftaran = $this->pendaftaran->load(array('NOMOR' => $entity['NOPEN']));
					if(count($pendaftaran) > 0) $entity['REFERENSI']['PENDAFTARAN'] = $pendaftaran[0];
				}
				if($this->references['RuangKamarTidur'] && $entity['JENIS_KUNJUNGAN'] == 3) {
					if(is_object($this->references['RuangKamarTidur'])) {
						$references = isset($this->references['RuangKamarTidur']->REFERENSI) ? (array) $this->references['RuangKamarTidur']->REFERENSI : array();
						$this->ruangKamarTidur->setReferences($references, true);
						if(isset($this->references['RuangKamarTidur']->COLUMNS)) $this->ruangKamarTidur->setColumns((array) $this->references['RuangKamarTidur']->COLUMNS);
					}
					$ruangKamarTidur = $this->ruangKamarTidur->load(array('ID' => $entity['RUANG_KAMAR_TIDUR']));
					if(count($ruangKamarTidur) > 0) $entity['REFERENSI']['RUANG_KAMAR_TIDUR'] = $ruangKamarTidur[0];
				}
				if($this->references['PasienPulang'] && ($entity['JENIS_KUNJUNGAN'] == 2 || $entity['JENIS_KUNJUNGAN'] == 3) && $entity['STATUS'] != 0) {
					if(is_object($this->references['PasienPulang'])) {
						$references = isset($this->references['PasienPulang']->REFERENSI) ? (array) $this->references['PasienPulang']->REFERENSI : array();
						$this->pulang->setReferences($references, true);
						if(isset($this->references['PasienPulang']->COLUMNS)) $this->pulang->setColumns((array) $this->references['PasienPulang']->COLUMNS);
					}
					$pulang = $this->pulang->load(array('KUNJUNGAN' => $entity['NOMOR']));
					if(count($pulang) > 0) $entity['REFERENSI']['PASIEN_KELUAR'] = $pulang[0];
				}
				if($this->references['Pembatalan']) {
					$pembatalan = $this->pembatalan->load(array('KUNJUNGAN' => $entity['NOMOR'], 'start'=>0, 'limit'=>1), array('*'), array('TANGGAL DESC'));
					if(count($pembatalan) > 0) $entity['REFERENSI']['PEMBATALAN'] = $pembatalan[0];
				}
				if($this->references['Perujuk']) {
					$id = substr($entity['REF'], 0, 2);
					if($id == 12) { // Order Lab						
						$orderlab = $this->orderlab->load(array('NOMOR' => $entity['REF']));
						if(count($orderlab) > 0) $entity['REFERENSI']['PERUJUK'] = $orderlab[0];
					}
					// add condition for other order
				}
				if($this->references['Mutasi'] && $entity['JENIS_KUNJUNGAN'] == 3) {
				    $mutasi = $this->mutasi->load(array('KUNJUNGAN' => $entity['NOMOR'], "STATUS" => ["1", "2"]));
				    if(count($mutasi) > 0) $entity['REFERENSI']['MUTASI'] = $mutasi[0];
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
			
			if(!System::isNull($params, 'NOMOR')) {
				$nomor = $params['NOMOR'];
				$params['kunjungan.NOMOR'] = $nomor;
				unset($params['NOMOR']);
			}
			
			if(!System::isNull($params, 'STATUS')) {
				$status = $params['STATUS'];
				$params['kunjungan.STATUS'] = strpos($status, "]") > 0 ? (array) json_decode($status) : $status;
				unset($params['STATUS']);
			}
			$select->join(array('p'=>new TableIdentifier('pendaftaran', 'pendaftaran')), 'p.NOMOR = NOPEN', array());
			
			$select->join(
				array('r' => new TableIdentifier('ruangan', 'master')),
				'r.ID = kunjungan.RUANGAN',
				array('JENIS_KUNJUNGAN')
			);
			
			if(!System::isNull($params, 'HISTORY')) {
				unset($params['HISTORY']);
			} else if($this->user && $this->privilage) {				
				$usr = $this->user;
				$select->join(
					array('par' => new TableIdentifier('pengguna_akses_ruangan', 'aplikasi')),
					'par.RUANGAN = kunjungan.RUANGAN',
					array()
				);
				$select->where('par.STATUS = 1');
				$select->where('par.PENGGUNA = '.$usr); 
			}
			
			if(isset($params['QUERY'])) if(!System::isNull($params, 'QUERY')){
				$select->where("(kunjungan.NOMOR LIKE '%".$params['QUERY']."%')");
				unset($params['QUERY']);
			}
			if(isset($params["JENIS_KUNJUNGAN"])) {
				$firstChar = $params["JENIS_KUNJUNGAN"][0];
				$not = $firstChar == "!" ? " NOT " : "";
				$jenis = $not != "" ? substr($params["JENIS_KUNJUNGAN"], 1) : $params["JENIS_KUNJUNGAN"];
				$sym = strpos($params["JENIS_KUNJUNGAN"], ",") > 0 ? " IN (".$jenis.")" : " = ".$jenis;
				$select->where($not."JENIS_KUNJUNGAN".$sym);
				
				unset($params["JENIS_KUNJUNGAN"]);
			}
			
			if(isset($params["JENIS_KELAMIN"]) || isset($params["NAMA"])) {
			    $select->join(
			        array('psn' => new TableIdentifier('pasien', 'master')),
			        'psn.NORM = p.NORM',
			        array()
			        );
			    
			    if(isset($params["JENIS_KELAMIN"])) {
			        $select->where('psn.JENIS_KELAMIN = '.$params["JENIS_KELAMIN"]);
			        unset($params["JENIS_KELAMIN"]);
			    } else {
			        $select->where("psn.NAMA LIKE '%".$params["NAMA"]."%'");
			        unset($params["NAMA"]);
			    }
			}
			if(isset($params['NORM'])) {
			    $params['p.NORM'] = $params['NORM'];
			    unset($params['NORM']);
			}
			if(isset($params["TAGIHAN"])) {
				$select->join(
					array('tp' => new TableIdentifier('tagihan_pendaftaran', 'pembayaran')),
					'tp.PENDAFTARAN = kunjungan.NOPEN',
					array()
				);
				$select->where('tp.STATUS = 1');
				$select->where('tp.TAGIHAN = '.$params["TAGIHAN"]);
				unset($params["TAGIHAN"]);
			}
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}