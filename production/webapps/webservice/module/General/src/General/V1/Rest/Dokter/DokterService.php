<?php
namespace General\V1\Rest\Dokter;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use General\V1\Rest\DokterRuangan\DokterRuanganService;
use General\V1\Rest\DokterSMF\DokterSMFService;
use General\V1\Rest\Pegawai\PegawaiService;
use Pegawai\V1\Rest\KartuIdentitas\Service as KIP;

class DokterService extends Service
{
    private $dokterRuangan;
	private $dokterSMF;
	private $pegawai;
	private $kip;
    
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("dokter", "master"));
		$this->entity = new DokterEntity();
		$this->dokterRuangan = new DokterRuanganService(true, array('Dokter' => false));	
		$this->dokterSMF = new DokterSMFService();
		$this->pegawai = new PegawaiService();
		$this->kip = new KIP();
        
        $this->limit = 1000;
    }

	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		$dokters = array(); 
		$i = 0;
		foreach($data as $entity) {
			$dokters[$i]['ID'] = $entity['ID'];
			$dokters[$i]['NAMA'] = $this->namaPegawai($entity['NIP']);
			$dokters[$i]['NIP'] = $entity['NIP'];
			$kips = $this->kip->load(array("NIP" => $entity["NIP"]));
			if(count($kips) > 0) $dokters[$i]["REFERENSI"]["KARTU_INDETITAS"] = $kips;
			$i++;
		}
		
		return $dokters;
	}      
	
	public function cariDokter($params = array()) {
		$params = is_array($params) ? $params : (array) $params;					
		$dokters = array();
		$i = 0;
		
		if(isset($params['RUANGAN']) && isset($params['SMF'])) {
			$druangans = $this->dokterRuangan->load(array('RUANGAN'=>$params['RUANGAN'], 'STATUS'=>1));
			foreach ($druangans as $dr) {
				$dsmfs = $this->dokterSMF->load(array('SMF'=>$params['SMF']));
				foreach ($dsmfs as $dsmf) {
					if($dsmf['DOKTER'] == $dr['DOKTER']) {
						$doks = $this->load(array('ID'=>$dsmf['DOKTER']));						
						if(count($doks) > 0) {
							$dokters[$i] = $doks[0];
							$i++;
						}
					}
				}
			}
		} else {
			if(isset($params['RUANGAN'])) 
				$data = $this->dokterRuangan->load(array('RUANGAN'=>$params['RUANGAN'], 'STATUS'=>1));
			else if(isset($params['SMF']))
				$data = $this->dokterSMF->load(array('SMF'=>$params['SMF']));
							
			foreach ($data as $d){
				$search = array('ID'=>$d['DOKTER']);
				if(isset($params['QUERY'])) $search['QUERY'] = $params['QUERY'];
				$doks = $this->load($search);
				if(count($doks) > 0) {
					$dokters[$i] = $doks[0];
					$i++;
				}
			}	
		}
		
		return $dokters;
    }	
		
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				$select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(!System::isNull($params, 'ID')) {				
				$params["dokter.ID"] = $params["ID"];
				unset($params["ID"]);
			}
			
			if(!System::isNull($params, 'NAMA')) { 
				$select->join(array('p' => new TableIdentifier("pegawai", "master")), "dokter.NIP = p.NIP", array("NIP", "NAMA", "GELAR_DEPAN", "GELAR_BELAKANG"));
				$select->where("p.NAMA LIKE '%".$params['NAMA']."%'");
				unset($params['NAMA']);
			}
			
			if(!System::isNull($params, 'STATUS')) {
				$status = $params['STATUS'];
				$params['dokter.STATUS'] = $status;
				unset($params['STATUS']);
			}
			
			if(isset($params['QUERY'])) {
				if(!System::isNull($params, 'QUERY')) {
					$select->join(array('p' => new TableIdentifier("pegawai", "master")), "dokter.NIP = p.NIP", array("NIP", "NAMA", "GELAR_DEPAN", "GELAR_BELAKANG"));
					$select->where("(p.NAMA LIKE '%".$params['QUERY']."%' OR p.NIP LIKE '".$params['QUERY']."%')");
					unset($params['QUERY']);
				}
			}
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
	
	private function namaPegawai($nip){
		$peg = $this->pegawai->load(array('NIP'=>$nip), array("NIP", "NAMA", "GELAR_DEPAN", "GELAR_BELAKANG"));
		
		if(count($peg) == 0) return '';
		
		if(str_replace(' ','', $peg[0]['GELAR_DEPAN']) != ''){
			$titik = '. ';
		}else{
			$titik = '';
		}
		if(str_replace(' ','', $peg[0]['GELAR_BELAKANG']) != ''){
			$koma = ', ';
		}else{
			$koma = '';
		}
		return $peg[0]['GELAR_DEPAN'].$titik.$peg[0]['NAMA'].$koma.$peg[0]['GELAR_BELAKANG'];
	}
}