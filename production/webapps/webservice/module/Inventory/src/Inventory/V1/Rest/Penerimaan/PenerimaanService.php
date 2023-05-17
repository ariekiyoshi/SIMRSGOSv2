<?php
namespace Inventory\V1\Rest\Penerimaan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use DBService\generator\Generator;

class PenerimaanService extends Service
{	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("penerimaan", "inventory"));
		$this->entity = new PenerimaanEntity();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$nomor = is_numeric($this->entity->get('NOMOR')) ? $this->entity->get('NOMOR') : 0;
		$ruangan = $this->entity->get('RUANGAN');
		if($nomor == 0) {
			$nomor = Generator::generateNoPenerimaan($ruangan);
			$this->entity->set('NOMOR', $nomor);
			$this->table->insert($this->entity->getArrayCopy());
			
		} else {
			$this->table->update($this->entity->getArrayCopy(), array("NOMOR" => $nomor));
		}
		
		return array(
			'success' => true
		);
	}
}
