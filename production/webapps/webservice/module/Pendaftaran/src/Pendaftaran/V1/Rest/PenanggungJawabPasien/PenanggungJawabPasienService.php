<?php
namespace Pendaftaran\V1\Rest\PenanggungJawabPasien;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use Zend\json\Json;
use DBService\System;
use DBService\Service;
use Pendaftaran\V1\Rest\KontakPenanggungJawab\KontakPenanggungJawabService;
use Pendaftaran\V1\Rest\KIPPenanggungJawab\KIPPenanggungJawabService;

class PenanggungJawabPasienService extends Service
{
    private $kontakpenanggungjawab;
	private $kippenanggungjawab;
    
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("penanggung_jawab_pasien", "pendaftaran"));
		$this->entity = new PenanggungJawabPasienEntity();
		
		$this->kontakpenanggungjawab = new KontakPenanggungJawabService(); 
		$this->kippenanggungjawab = new KIPPenanggungJawabService();
    }
    
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		if(count($data) > 0) {
			$kontaks = $this->kontakpenanggungjawab->load(array('ID'=>$data[0]['ID']));
			if(count($kontaks) > 0) $data[0]['KONTAK'] = $kontaks;
			
			$kips = $this->kippenanggungjawab->load(array('ID'=>$data[0]['ID']));
			if(count($kips) > 0) $data[0]['KARTUIDENTITAS'] = $kips;
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
			
			if(isset($params['NAMA'])) {
				if(!System::isNull($params, 'NAMA')) {
					$select->where->like('NAMA', '%'.$params['NAMA'].'%');
					unset($params['NAMA']);
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
		$params = array(
			"NOPEN" => $this->entity->get('NOPEN'),
			"REF" => $this->entity->get('REF'),
			"SHDK" => $this->entity->get('SHDK'),
			"JENIS_KELAMIN" => $this->entity->get('JENIS_KELAMIN')
		);
		
		if($id == 0) {
			$found = $this->table->select($params)->toArray();
			if(count($found) > 0) $id = $found[0]["ID"];
			else $create = true;
		}
		
		if(!$create) {
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
			$id = $this->table->getLastInsertValue();
		}
		
		$this->simpanKontakPenanggung($data, $id);
		
		return $this->load(array("ID" => $id));
	}
	
	private function simpanKontakPenanggung($data, $id) {
		if(isset($data['KONTAK'])) {
			foreach($data['KONTAK'] as $val) {
				$val['ID'] = $id;
				$this->kontakpenanggungjawab->simpan($val); 
			}
		}
	}
	
	private function simpanKIPPenanggung($data, $id) {
		if(isset($data['KARTUIDENTITAS'])) {
			foreach($data['KARTUIDENTITAS'] as $val) {
				$val['ID'] = $id;
				$this->kippenanggungjawab->simpan($val); 
			}
		}
	}
}