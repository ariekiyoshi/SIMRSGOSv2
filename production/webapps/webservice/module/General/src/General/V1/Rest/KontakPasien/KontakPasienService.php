<?php
namespace General\V1\Rest\KontakPasien;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\System;
use DBService\Service;

class KontakPasienService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("kontak_pasien", "master"));
		$this->entity = new KontakPasienEntity();
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$norm = $this->entity->get('NORM');
		$jenis = $this->entity->get('JENIS');
		$cek = $this->table->select(array("NORM" => $norm, "JENIS" => $jenis))->toArray();
		if(count($cek) > 0) {
			$this->table->update($this->entity->getArrayCopy(), array('NORM' => $norm, "JENIS" => $jenis));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}
}
