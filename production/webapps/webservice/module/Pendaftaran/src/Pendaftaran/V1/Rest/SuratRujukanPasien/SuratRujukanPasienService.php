<?php
namespace Pendaftaran\V1\Rest\SuratRujukanPasien;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use General\V1\Rest\PPK\PPKService;
use General\V1\Rest\DiagnosaMasuk\DiagnosaMasukService;
use Pendaftaran\V1\Rest\Pendaftaran\PendaftaranService;

class SuratRujukanPasienService extends Service
{	
	private $ppk;
	private $diagnosaMasuk;
	
	protected $references = array(
		'PPK' => true,
		'DiagnosaMasuk' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("surat_rujukan_pasien", "pendaftaran"));
		$this->entity = new SuratRujukanPasienEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['PPK']) $this->ppk = new PPKService(false);
			if($this->references['DiagnosaMasuk']) $this->diagnosaMasuk = new DiagnosaMasukService();
		}	
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['PPK']) {
					$ppk = $this->ppk->load(array('ID' => $entity['PPK']));
					if(count($ppk) > 0) $entity['REFERENSI']['PPK'] = $ppk[0];
				}
				if($this->references['DiagnosaMasuk']) {
					$diagnosaMasuk = $this->diagnosaMasuk->load(array('ID' => $entity['DIAGNOSA_MASUK']));
					if(count($diagnosaMasuk) > 0) $entity['REFERENSI']['DIAGNOSA_MASUK'] = $diagnosaMasuk[0];
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
				$select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
						
			if(isset($params['TANGGAL'])) {
				if(!System::isNull($params, 'TANGGAL')) {
					$select->where("DATEDIFF('".$params['TANGGAL']."', TANGGAL) BETWEEN 0 AND 30");
					$select->order('TANGGAL DESC');
					unset($params['TANGGAL']);
				}
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
	
	public function simpan($data) {		
		$create = false;
		$data = is_array($data) ? $data : (array) $data;
		$id = (int) (isset($data["ID"]) ? $data["ID"] : "0");
		$this->entity->exchangeArray($data);
		$nomor = !isset($data["NOMOR"]) || $data["NOMOR"] == "" ? $data["NOPEN"] : $data["NOMOR"];
		$params = array(
			'PPK'=>$this->entity->get('PPK'), 
			'NORM'=>$this->entity->get('NORM'),		
		    'NOMOR'=>$nomor
		);
		
		if($id == 0) {
		    $found = $this->load($params);
		    if(count($found) > 0) $id = $found[0]["ID"];
		    else {
		        $this->entity->set('NOMOR', $nomor);
		        $create = true;
		    }
		}
				
		$dm = $this->simpanDiagnosaMasuk($data);		
		if($dm) $this->entity->set("DIAGNOSA_MASUK", $dm);
		
		$datas = $this->entity->getArrayCopy();			
		
		if(!$create) {
			$this->table->update($datas, array("ID" => $id));
		} else {
			$this->table->insert($datas);
			$id = $this->table->getLastInsertValue();
		}
		
		$this->updateDiagnosaDanRujukanPendaftaran($data, $dm, $id);
		
		return array(
			'success' => true,
			'data' => $this->load(array("ID" => $id))
		);
	}
	
	private function simpanDiagnosaMasuk(&$data) {
		$diagnosa = array();
		$id = null;
		if(isset($data["REFERENSI"])) {
			$ref = $data["REFERENSI"];
			if(isset($ref["DIAGNOSA_MASUK"])) {
				$dm = $ref["DIAGNOSA_MASUK"];
				if(isset($dm["DIAGNOSA"])) {
					$diagnosa["DIAGNOSA"] = $dm["DIAGNOSA"];
				}
				if(isset($dm["ICD"])) $diagnosa["ICD"] = $dm["ICD"];
			}
		}
		
		
		if(count($diagnosa) > 0) {
			$result = $this->diagnosaMasuk->simpan($diagnosa);			
			if($result['success']) {
				if(isset($result['data'])) $id = $result['data']['ID'];
			}
		}
		
		return $id;
	}
	
	private function updateDiagnosaDanRujukanPendaftaran(&$data, $dm, $rujukan) {
		if(isset($data["NOPEN"])){
			$pdftrn = new PendaftaranService(false);
			$params = array(
				"NOMOR" => $data["NOPEN"],
				"RUJUKAN" => $rujukan
			);
			
			if($dm) $params["DIAGNOSA_MASUK"] = $dm;
			$pdftrn->simpan($params);
		}
	}
}