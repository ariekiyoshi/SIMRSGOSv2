<?php
namespace Cetakan\V1\Rest\KarcisPasien;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\generator\Generator;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

class KarcisPasienService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("karcis_pasien", "cetakan"));
		$this->entity = new KarcisPasienEntity();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : false;
		$this->entity->set('TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));
		
		if($id) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("ID" => $id));
		} else {
			$id = Generator::generateIdKarcis();
			$this->entity->set('ID', $id);
		
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
		}
		return $this->load(array('karcis_pasien.ID' => $id));
	}
}