<?php
namespace General\V1\Rest\KIP;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;

class KIPService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("kartu_identitas_pasien", "master"));
		$this->entity = new KIPEntity();	
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$norm = $this->entity->get('NORM');
		$jenis = $this->entity->get('JENIS');
		$cek = $this->table->select(array("NORM" => $norm, "JENIS" => $jenis))->toArray();
		if(count($cek) > 0) {
			foreach($cek as $kp) {
				$this->table->update($this->entity->getArrayCopy(), array('NORM' => $norm, "JENIS" => $jenis));
			}
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}
}