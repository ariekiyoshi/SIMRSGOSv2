<?php
namespace General\V1\Rest\KeluargaPasien;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use DBService\generator\Generator;
use General\V1\Rest\KontakKeluargaPasien\KontakKeluargaPasienService;

class KeluargaPasienService extends Service
{
    private $kontakKeluargaPasien;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("keluarga_pasien", "master"));
		$this->entity = new KeluargaPasienEntity();	
		$this->kontakKeluargaPasien = new KontakKeluargaPasienService();
    }
    
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		foreach($data as &$entity) {
			$kontaks = $this->kontakKeluargaPasien->load(array('NORM' => $entity['NORM'], 'SHDK' => $entity['SHDK']));
			if(count($kontaks) > 0) $entity['KONTAK'] = $kontaks;
		}
		
		return $data;
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = $this->entity->get('ID');
		$shdk = $this->entity->get('SHDK');
		$norm = $this->entity->get('NORM');
		$nama = $this->entity->get('NAMA');
		$jenis_kelamin = $this->entity->get('JENIS_KELAMIN');
		
		$finds = $this->load(["NORM" => $norm, "JENIS_KELAMIN" => $jenis_kelamin, "SHDK" => $shdk]);
		if(count($finds) == 0) {
		    if($id > 0) {
		        $this->table->delete(["NORM" => $norm, "ID" => $id, "NAMA" => $nama]);
		        $id = 0;
		    }
		}
		
		if($id > 0){		    
			$this->table->update($this->entity->getArrayCopy(), array("NORM" => $norm, "JENIS_KELAMIN" => $jenis_kelamin, "SHDK" => $shdk, "ID" => $id));
		} else {
			$id = Generator::generateIdKeluargaPasien(
				$shdk, $norm, $jenis_kelamin
			);
			
			$this->entity->set('ID', $id);
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		$this->simpanKontakKeluarga($data, $norm, $shdk);
		
		return array(
			'success' => true
		);
	}
	
	private function simpanKontakKeluarga($data, $norm, $shdk) {
		if(isset($data['KONTAK'])) {
			foreach($data['KONTAK'] as $kon) {				
				$kon['SHDK'] = $shdk;
				$kon['NORM'] = $norm;
				
				$this->kontakKeluargaPasien->simpan($kon); 
			}
		}
	}
}
