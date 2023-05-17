<?php
namespace Layanan\V1\Rest\CatatanHasilLab;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use DBService\System;

use General\V1\Rest\Dokter\DokterService;

class CatatanHasilLabService extends Service
{
	private $dokter;
	
    public function __construct() {
		$this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("catatan_hasil_lab", "layanan"));
        $this->entity = new CatatanHasilLabEntity();
		
		$this->dokter = new DokterService();
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$this->entity->set('TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));
		$cek = $this->load(array('KUNJUNGAN'=>$this->entity->get('KUNJUNGAN')));
		
		$data = $this->entity->getArrayCopy();
		if(count($cek) > 0) {
			$this->table->update($data, array("KUNJUNGAN" => $this->entity->get('KUNJUNGAN')));
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
			
		}
		
		return $data;
	}
}