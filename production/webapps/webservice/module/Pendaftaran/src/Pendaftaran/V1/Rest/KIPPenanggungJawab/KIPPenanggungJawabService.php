<?php
namespace Pendaftaran\V1\Rest\KIPPenanggungJawab;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;

class KIPPenanggungJawabService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("kartu_identitas_penanggung_jawab", "pendaftaran"));
		$this->entity = new KIPPenanggungJawabEntity();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		$jenis = $this->entity->get('JENIS');
		$id = $this->entity->get('ID');
		$cek = $this->table->select(array("JENIS" => $jenis, "ID" => $id))->toArray(); 
		if(count($cek) > 0) {
			$this->table->update($this->entity->getArrayCopy(), array("JENIS" => $jenis, "ID" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}	
		
		return array(
			'success' => true
				
		);
	}
}