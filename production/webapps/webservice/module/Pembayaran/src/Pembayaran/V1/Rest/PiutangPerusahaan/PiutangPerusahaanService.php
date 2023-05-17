<?php
namespace Pembayaran\V1\Rest\PiutangPerusahaan;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Zend\Db\Sql\TableIdentifier;

use General\V1\Rest\Referensi\ReferensiService;

class PiutangPerusahaanService extends Service
{
	private $referensi;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("piutang_perusahaan", "pembayaran"));
		$this->entity = new PiutangPerusahaanEntity();
		
		$this->referensi = new ReferensiService();
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);		
		foreach($data as &$entity) {
			$penjamin = $this->referensi->load(array('JENIS' => 10, 'ID' => $entity['PENJAMIN']));
			if(count($penjamin) > 0) $entity['REFERENSI']['PENJAMIN'] = $penjamin[0];
		}
		
		return $data;
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$tagihan = $this->entity->get('TAGIHAN');
		$penjamin = $this->entity->get('PENJAMIN');
		$cek = $this->load(array('TAGIHAN' => $tagihan, 'PENJAMIN' => $penjamin ));
		
		if(count($cek) > 0) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array('TAGIHAN' => $tagihan, 'PENJAMIN' => $penjamin ));
		} else {
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
			
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('TAGIHAN' => $tagihan, 'PENJAMIN' => $penjamin ))
		);
	}
}
