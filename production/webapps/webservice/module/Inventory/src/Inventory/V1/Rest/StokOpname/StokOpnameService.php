<?php
namespace Inventory\V1\Rest\StokOpname;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use DBService\generator\Generator;

use Inventory\V1\Rest\StokOpnameDetil\StokOpnameDetilService;

class StokOpnameService extends Service
{
	private $stokopnamedetil;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("stok_opname", "inventory"));
		$this->entity = new StokOpnameEntity();
		
		$this->stokopnamedetil = new StokOpnameDetilService();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$cek = $this->table->select(array('RUANGAN'=> $this->entity->get('RUANGAN'), 'STATUS'=>'Proses'));
		if(count($cek) > 0){
			return array(
				'success' => false
			);
		}else{
			$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : 0;
			if($id == 0) {
				$this->table->insert($this->entity->getArrayCopy());
				$id = $this->table->getLastInsertValue();
			} else {
				$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
			}			
			return array(
				'success' => true,
				'data' => $this->load(array('ID'=>$id))
			);			
		}		
	}	
}
