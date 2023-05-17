<?php
namespace Cetakan\V1\Rest\KwitansiPembayaran;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\generator\Generator;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

class KwitansiPembayaranService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("kwitansi_pembayaran", "cetakan"));
		$this->entity = new KwitansiPembayaranEntity();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$this->entity->set('TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));
		
		$_data = $this->entity->getArrayCopy();
		$this->table->insert($_data);
		
		return array(
			'success' => true,
			'data' => $this->load(array('NOMOR'=>$this->entity->get('NOMOR')))
		); 
	}
}