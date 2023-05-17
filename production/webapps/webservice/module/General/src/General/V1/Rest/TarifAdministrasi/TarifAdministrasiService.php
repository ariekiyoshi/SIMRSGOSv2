<?php
namespace General\V1\Rest\TarifAdministrasi;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

use  General\V1\Rest\Referensi\ReferensiService;
use  General\V1\Rest\Administrasi\AdministrasiService;

class TarifAdministrasiService extends Service
{
	private $j_kunjungan;
	private $administrasi;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("tarif_administrasi", "master"));
		$this->entity = new TarifAdministrasiEntity();
		$this->j_kunjungan = new ReferensiService();
		$this->administrasi = new AdministrasiService();
    }
	
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				$j_kunjungan = $this->j_kunjungan->load(array('ID' => $entity['JENIS_KUNJUNGAN'], 'JENIS' => 15));
				if(count($j_kunjungan) > 0) $entity['REFERENSI']['JENIS_KUNJUNGAN'] = $j_kunjungan[0];
				
				$administrasi = $this->administrasi->load(array('ID' => $entity['ADMINISTRASI']));
				if(count($administrasi) > 0) $entity['REFERENSI']['ADMINISTRASI'] = $administrasi[0];
			}
		}
		
		return $data;
	}

	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('ID');        
		
		$rows = $this->table->select(array("ID" => $id))->toArray();
		if(count($rows) > 0){
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
            $this->entity->set('TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}
}