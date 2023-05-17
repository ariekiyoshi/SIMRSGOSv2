<?php
namespace General\V1\Rest\DokterSMF;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

class DokterSMFService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("dokter_smf", "master"));
		$this->entity = new DokterSMFEntity();
    }
    
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$dokter = $this->entity->get('DOKTER');
		$smf = $this->entity->get('SMF');
		$rows = $this->table->select(array("DOKTER" => $dokter, "SMF" => $smf))->toArray();
		if(count($rows) > 0){
			$this->table->update($this->entity->getArrayCopy(), array('DOKTER' => $dokter, "SMF" => $smf));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}
}