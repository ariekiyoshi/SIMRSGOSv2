<?php
namespace Layanan\V1\Rest\HasilPa;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use DBService\System;

use General\V1\Rest\Dokter\DokterService;
use General\V1\Rest\Referensi\ReferensiService;

class HasilPaService extends Service
{
	private $dokter;
	private $referensi;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("hasil_pa", "layanan"));
		$this->entity = new HasilPaEntity();
		
		$this->dokter = new DokterService();
		$this->referensi = new ReferensiService();
    }
        
 	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$this->entity->set('TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));
		/*$cek = $this->load(array('KUNJUNGAN'=>$this->entity->get('KUNJUNGAN'),'JENIS_PEMERIKSAAN'=>$this->entity->get('JENIS_PEMERIKSAAN')));
		$data = $this->entity->getArrayCopy();
		if(count($cek) > 0) {
			$this->table->update($data, array('KUNJUNGAN'=>$this->entity->get('KUNJUNGAN'),'JENIS_PEMERIKSAAN'=>$this->entity->get('JENIS_PEMERIKSAAN')));
		} else {
			$this->table->insert($data);
		}*/
		
		$id = (int) $this->entity->get('ID');
		$data = $this->entity->getArrayCopy();
		if($id > 0) {
			$this->table->update($data, array('ID'=>$id));
		} else {
			$this->table->insert($data);
		}
		
		return $data;
	}

	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		foreach($data as &$entity) {
			
			$dokter = $this->dokter->load(array('ID' => $entity['DOKTER']));
			if(count($dokter) > 0) $entity['REFERENSI']['DOKTER'] = $dokter[0];
			
			$referensi = $this->referensi->load(array('JENIS' => $entity['JENIS_PEMERIKSAAN'], 'ID'=>66));
			if(count($referensi) > 0) $entity['REFERENSI']['JENIS_PEMERIKSAAN'] = $referensi[0];
			
		}
		
		return $data;
	}
}