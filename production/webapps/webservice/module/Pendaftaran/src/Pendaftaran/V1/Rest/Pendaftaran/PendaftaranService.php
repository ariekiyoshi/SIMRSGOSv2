<?php
namespace Pendaftaran\V1\Rest\Pendaftaran;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\generator\Generator;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

use General\V1\Rest\Pasien\PasienService;
use General\V1\Rest\KAP\KAPService;
use Pendaftaran\V1\Rest\PenanggungJawabPasien\PenanggungJawabPasienService;
use Pendaftaran\V1\Rest\TujuanPasien\TujuanPasienService;
use Pendaftaran\V1\Rest\SuratRujukanPasien\SuratRujukanPasienService;
use Pendaftaran\V1\Rest\Penjamin\PenjaminService;
use General\V1\Rest\DiagnosaMasuk\DiagnosaMasukService;
use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\Paket\PaketService;
use Layanan\V1\Rest\PasienPulang\PasienPulangService;
use Layanan\V1\Rest\PasienMeninggal\PasienMeninggalService;

use Pendaftaran\V1\Rest\Kecelakaan\Service as KecelakaanService;

class PendaftaranService extends Service
{
	private $pasien;
    private $penanggungjawab;
	private $tujuanpasien;
	private $suratrujukanpasien;
	private $penjamin;
	private $diagnosa;
	private $referensi;
	private $kap;
	private $paket;
	private $pasienPulang;
	private $pasienmeninggal;
	private $kecelakaan;
	
	protected $references = array(
		'Pasien' => true,		
		'TujuanPasien' => true,
		'SuratRujukanPasien' => true,
		'Penjamin' => true,
		'DiagnosaMasuk' => true,
		'Referensi' => true,
		'KAP' => true,
		'PasienPulang' => true,
		'PasienMeninggal' => true,
		'Kecelakaan' => true,
		'PenanggungJawabPasien' => true
	);
    
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pendaftaran", "pendaftaran"));
		$this->entity = new PendaftaranEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['Pasien']) $this->pasien = new PasienService(true, array(
				'KeluargaPasien' => false,
				'KIP' => true,
				'KAP' => true,
				'KontakPasien' => true,
				'TempatLahir' => true,
				'Referensi' => true
			)); 			
			if($this->references['TujuanPasien']) $this->tujuanpasien = new TujuanPasienService(true, array('Pendaftaran' => false));
			if($this->references['SuratRujukanPasien']) $this->suratrujukanpasien = new SuratRujukanPasienService(true, array("DiagnosaMasuk" => true));
			if($this->references['Penjamin']) $this->penjamin = new PenjaminService();
			if($this->references['DiagnosaMasuk']) $this->diagnosa = new DiagnosaMasukService();		
			if($this->references['PasienPulang']) $this->pasienPulang = new PasienPulangService();
			if($this->references['PasienMeninggal']) $this->pasienmeninggal = new PasienMeninggalService();
			if($this->references['Kecelakaan']) $this->kecelakaan = new KecelakaanService();
			if($this->references['Referensi']) {
				$this->referensi = new ReferensiService();
				$this->paket = new PaketService();
			}
			if($this->references['PenanggungJawabPasien']) $this->penanggungjawab = new PenanggungJawabPasienService(); 
			if($this->references['KAP']) $this->kap = new KAPService();
		}			
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Pasien']) {
					if(is_object($this->references['Pasien'])) {
						$references = isset($this->references['Pasien']->REFERENSI) ? (array) $this->references['Pasien']->REFERENSI : array();
						$this->pasien->setReferences($references, true);
						if(isset($this->references['Pasien']->COLUMNS)) $this->pasien->setColumns((array) $this->references['Pasien']->COLUMNS);
					}
					$pasien = $this->pasien->load(array('NORM'=>$entity['NORM']));
					if(count($pasien) > 0) $entity['REFERENSI']['PASIEN'] = $pasien[0];
				}
				if($this->references['DiagnosaMasuk']) {
					$diagnosa = $this->diagnosa->load(array('ID'=>$entity['DIAGNOSA_MASUK']));
					if(count($diagnosa) > 0) $entity['DIAGNOSAMASUK'] = $diagnosa[0];
				}						

				if($this->references['TujuanPasien']) {
					if(is_object($this->references['TujuanPasien'])) {
						$references = isset($this->references['TujuanPasien']->REFERENSI) ? (array) $this->references['TujuanPasien']->REFERENSI : array();
						$this->tujuanpasien->setReferences($references, true);
						if(isset($this->references['TujuanPasien']->COLUMNS)) $this->tujuanpasien->setColumns((array) $this->references['TujuanPasien']->COLUMNS);
					}
					
					$tujuan = $this->tujuanpasien->load(array('NOPEN'=>$entity['NOMOR']));
					if(count($tujuan) > 0) $entity['TUJUAN'] = $tujuan[0];
				}

				if($this->references['SuratRujukanPasien']) {
					$rujukan = $this->suratrujukanpasien->load(array('ID'=>$entity['RUJUKAN']));
					if(count($rujukan) > 0) $entity['RUJUKAN'] = $rujukan[0];
				}
				
				if($this->references['Penjamin']) {
					if(is_object($this->references['Penjamin'])) {
						$references = isset($this->references['Penjamin']->REFERENSI) ? (array) $this->references['Penjamin']->REFERENSI : array();
						$this->penjamin->setReferences($references, true);
						if(isset($this->references['Penjamin']->COLUMNS)) $this->penjamin->setColumns((array) $this->references['Penjamin']->COLUMNS);
					}
					$penjamin = $this->penjamin->load(array('NOPEN'=>$entity['NOMOR'], 'NORM' => $entity['NORM']));
					if(count($penjamin) > 0) $entity['PENJAMIN'] = $penjamin[0];
				}
				
				if($this->references['PasienPulang']) {
					$pasienPulang = $this->pasienPulang->load(array('NOPEN'=>$entity['NOMOR']), array("*"), array("ID DESC"));
					if(count($pasienPulang) > 0) $entity['REFERENSI']['PASIEN_PULANG'] = $pasienPulang[0];
				}
				
				if($this->references['PasienMeninggal']) {
					$pasienmeninggal = $this->pasienmeninggal->load(array('NOPEN'=>$entity['NOMOR']));
					if(count($pasienmeninggal) > 0) $entity['REFERENSI']['PASIENMENINGGAL'] = $pasienmeninggal[0];
				}
				
				if($this->references['Kecelakaan']) {
					$kecelakaan = $this->kecelakaan->load(array('NOPEN'=>$entity['NOMOR']));
					if(count($kecelakaan) > 0) $entity['REFERENSI']['KECELAKAAN'] = $kecelakaan[0];
				}
				
				if($this->references['Referensi']) {
					$referensi = $this->referensi->load(array('JENIS' => 25,'ID' => $entity['STATUS']));
					if(count($referensi) > 0) $entity['REFERENSI']['STATUS'] = $referensi[0];
					
					if(isset($entity['PAKET'])) {
						$paket = $this->paket->load(array('ID' => $entity['PAKET']));
						if(count($paket) > 0) $entity['REFERENSI']['PAKET'] = $paket[0];
					}
				}
				//var_dump($this->references['PenanggungJawabPasien']);
				if($this->references['PenanggungJawabPasien']) {
					$penanggung = $this->penanggungjawab->load(array('NOPEN'=>$entity['NOMOR']));
					if(count($penanggung) > 0) $entity['REFERENSI']['PENANGGUNG_PASIEN'] = $penanggung[0];					
				}
				
				$entity['REFERENSI']['SYSDATE'] = System::getSysDate($this->table->getAdapter());
			}					
		}
		
		return $data;
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		$nopen = is_numeric($this->entity->get('NOMOR')) ? $this->entity->get('NOMOR') : 0;
		$diagnosa = is_numeric($this->entity->get('DIAGNOSA_MASUK')) ? $this->entity->get('DIAGNOSA_MASUK') : 0;		
		
		$dm = $this->simpanDiagnosa($data);		
		if($dm) $this->entity->set('DIAGNOSA_MASUK', $dm);
		
		$create = false;
		if($nopen == 0) {
		    $nopen = Generator::generateNoPendaftaran();
		    $create = true;
		}
		
		$rujukan = $this->simpanRujukan($data, $dm, $nopen);
		
		if(!$create){
			$this->table->update($this->entity->getArrayCopy(), array("NOMOR" => $nopen));
		} else {
			//$this->entity->set('TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));
			if($rujukan) $this->entity->set('RUJUKAN', $rujukan["ID"]);
			$this->entity->set('NOMOR', $nopen);
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		$data['NOMOR'] = $nopen;
		
		$this->simpanTujuan($data, $nopen);
		$this->simpanPenjamin($data, $nopen);
		$this->simpanPenanggungJawab($data, $nopen);		
		$this->simpanKecelakaan($data, $nopen);
		
		if($nopen > 0) {
			$results = $this->load(array('NOMOR'=>$nopen));
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
	
	private function simpanDiagnosa(&$data) {
		if(isset($data['DIAGNOSAMASUK'])) {
			$result = $this->diagnosa->simpan($data['DIAGNOSAMASUK']);
			
			if($result['success']) {
				return $result['data'] ? $result['data']['ID'] : null;
			}				
		}
		
		return null;
	}
	
	private function simpanTujuan(&$data, $nopen) {
		if(isset($data['TUJUAN'])) {
			$data['TUJUAN']['NOPEN'] = $nopen;
			$this->tujuanpasien->simpan($data['TUJUAN']); 
		}
	}
	
	private function simpanPenanggungJawab(&$data, $nopen) {
		if(isset($data['PENANGGUNGJAWAB'])) {
			$data['PENANGGUNGJAWAB']['NOPEN'] = $nopen;
			$this->penanggungjawab->simpan($data['PENANGGUNGJAWAB']); 
		}
	}
	
	private function simpanPenjamin($data, $nopen) {
		if(isset($data['PENJAMIN'])) {
			$this->simpanKAP($data['PENJAMIN']);
			$this->penjamin->simpan(array(
				"JENIS" => $data['PENJAMIN']['JENIS'],
				"NOPEN" => $nopen,
				"NOMOR" => $data['PENJAMIN']['SEP'],
				"KELAS" => $data['PENJAMIN']['KELAS'],
				"COB" => $data['PENJAMIN']['COB'],
			    "KATARAK" => $data['PENJAMIN']['KATARAK'],
			    "NO_SURAT" => $data['PENJAMIN']['NO_SURAT'],
			    "DPJP" => $data['PENJAMIN']['DPJP']
			));
		}
	}
	
	private function simpanKAP($data) {
		if(isset($data)) {
			$params = array(
				'JENIS' => $data['JENIS'],
				'NORM' => $data['NORM'],
				'NOMOR' => $data['NOMOR']
			);
			$this->kap->simpan($params); 
		}
	}
	
	private function simpanRujukan(&$data, $dianosaMasuk, $nopen) {
		if(isset($data['SURAT_RUJUKAN'])) {
			$data['SURAT_RUJUKAN']['NORM'] = $data['NORM'];
			$data['SURAT_RUJUKAN']['NOPEN'] = $nopen;
			$data['SURAT_RUJUKAN']['DIAGNOSA_MASUK'] = $dianosaMasuk;			
			$result = $this->suratrujukanpasien->simpan($data['SURAT_RUJUKAN']);
			return count($result["data"]) > 0 ? $result["data"][0] : null;
		}
		return null;
	}
	
	private function simpanKecelakaan(&$data, $nopen) {
		if(isset($data["REFERENSI"])) {
			$ref = $data["REFERENSI"];
			if(isset($ref['KECELAKAAN'])) {
				$ref['KECELAKAAN']['NOPEN'] = $nopen;
				$this->kecelakaan->simpan($ref['KECELAKAAN'], true); 
			}
		}
	}
}