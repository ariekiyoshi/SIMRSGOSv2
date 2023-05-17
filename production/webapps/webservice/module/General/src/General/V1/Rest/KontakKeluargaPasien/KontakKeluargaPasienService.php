<?php
namespace General\V1\Rest\KontakKeluargaPasien;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use DBService\generator\Generator;

class KontakKeluargaPasienService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("kontak_keluarga_pasien", "master"));
		$this->entity = new KontakKeluargaPasienEntity();	
    }
    
    public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$entity = $this->entity->getArrayCopy();		
		$cek = $this->table->select($entity)->toArray();
		if(count($cek) > 0){
			$this->table->update($entity, $entity);
		} else {
			$this->table->insert($entity);
		}
		
		return array(
			'success' => true
		);
	}
	
}
