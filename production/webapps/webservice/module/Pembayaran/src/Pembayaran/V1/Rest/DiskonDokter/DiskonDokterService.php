<?php
namespace Pembayaran\V1\Rest\DiskonDokter;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Zend\Db\Sql\TableIdentifier;

use General\V1\Rest\Dokter\DokterService;

class DiskonDokterService extends Service
{
	private $dokter;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("diskon_dokter", "pembayaran"));
		$this->entity = new DiskonDokterEntity();
		
		$this->dokter = new DokterService();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get("ID");
		$cek = $this->load(array("ID" => $id));
		if(count($cek) === 0) {
			$tagihan = $this->entity->get('TAGIHAN');
			$dokter = $this->entity->get('DOKTER');
			$cek = $this->load(array('TAGIHAN'=>$tagihan, 'DOKTER'=>$dokter));
			if(count($cek) > 0) {
				$id = $cek[0]["ID"];
			}
		}		
		
		if(count($cek) > 0) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array('ID'=>$id));
		} else {
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
			$id = $this->table->getLastInsertValue();
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('ID' => $id))
		);
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
