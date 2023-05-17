<?php
namespace Pendaftaran\V1\Rest\Penjamin;

use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\KAP\KAPService;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use General\V1\Rest\TarifFarmasiPerKelas\TarifFarmasiPerKelasService;

class PenjaminService extends Service
{
	private $referensi;
	private $kap;
	private $tariffarmasiperkelas;
	
	protected $references = array(
		'Kelas' => true,
		'Pembayaran' => true,
		'TarifFarmasi' => true,
		'KAP' => true
	);

    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("penjamin", "pendaftaran"));
		$this->entity = new PenjaminEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			$this->referensi = new ReferensiService();
			if($this->references['KAP']) $this->kap = new KAPService();
			if($this->references['TarifFarmasi']) $this->tariffarmasiperkelas = new TarifFarmasiPerKelasService();		
		}	
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$norm = 0;
		$cek = $this->table->select(array("NOPEN" => $data['NOPEN']))->toArray(); 
		if(count($cek) > 0) {
			if(!isset($data['JENIS'])) $data['JENIS'] = $cek[0]['JENIS'];
		}
		if(isset($data['NORM'])) {
			$norm = $data['NORM'];
			$this->simpanKAP($data);
			unset($data["NOMOR"]);
		}	
		$this->entity->exchangeArray($data);
		if(isset($data['SEP'])) {
			$this->entity->set('NOMOR', $data['SEP']);
		}
		$nopen = $this->entity->get('NOPEN');
		
		if(count($cek) > 0 ) {
			$this->table->update($this->entity->getArrayCopy(), array("NOPEN" => $nopen));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		$data = $norm == 0 ? $this->load(array('NOPEN' => $nopen)) : $this->load(array('NOPEN' => $nopen, 'NORM' => $norm));
		
		return $data;
	}
	
	public function simpanKAP($data) {
	    if(isset($data['NOMOR'])) {
	        $params = array(
	            'JENIS'=>$data['JENIS'],
	            'NORM'=>$data['NORM'],
	            'NOMOR'=>$data['NOMOR']
	        );
	        
	        $this->kap->simpan($params);
	    }
	}
	
	public function getRowCount($params = array()) {
		$norm = isset($params['NORM']) ? $params['NORM'] : null;
		if($norm || empty($norm)) unset($params['NORM']);
		
		return parent::getRowCount($params);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$norm = isset($params['NORM']) ? $params['NORM'] : null;
		if(isset($norm) || empty($norm)) unset($params['NORM']);
		
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Kelas']) {
					$kelas = $this->referensi->load(array('JENIS' => 19, 'ID' => $entity['KELAS']));
					if(count($kelas) > 0) $entity['REFERENSI']['KELAS'] = $kelas[0];
				}
				
				if($this->references['Pembayaran']) {
					$pembayaran = $this->referensi->load(array('JENIS' => 34, 'ID' => $entity['JENIS']));
					if(count($pembayaran) > 0) $entity['REFERENSI']['PEMBAYARAN'] = $pembayaran[0];
				}
				
				if($this->references['TarifFarmasi']) {
					$tariffarmasiperkelas = $this->tariffarmasiperkelas->load(array('KELAS' => $entity['KELAS'], 'STATUS' => 1));
					if(count($tariffarmasiperkelas) > 0) $entity['REFERENSI']['TARIF_FARMASI'] = $tariffarmasiperkelas[0];
				}
				
				if($this->references['KAP']) {
					if(isset($norm)) {
						$kap = $this->kap->load(array('JENIS' => $entity['JENIS'], 'NORM' => $norm));
						if(count($kap) > 0) $entity['REFERENSI']['KAP'] = $kap[0];
					}
				}
			}
		}
		
		return $data;
	}
}