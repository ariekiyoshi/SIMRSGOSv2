<?php
namespace Penjualan\V1\Rest\ReturPenjualan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use DBService\generator\Generator;

class ReturPenjualanservice extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("retur_penjualan", "penjualan"));
		$this->entity = new ReturPenjualanEntity();
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : false;
		if($id) {
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$id = Generator::generateIdReturFarmasi();
			$this->entity->set('ID', $id);
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('ID' => $id))
		);
	}
}