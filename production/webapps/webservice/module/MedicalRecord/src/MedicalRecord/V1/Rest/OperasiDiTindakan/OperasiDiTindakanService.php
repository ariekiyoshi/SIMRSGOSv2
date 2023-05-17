<?php
namespace MedicalRecord\V1\Rest\OperasiDiTindakan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use DBService\System;


class OperasiDitindakanService extends Service
{
	
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("operasi_di_tindakan", "medicalrecord"));
		$this->entity = new OperasiDiTindakanEntity();
    }
        
		
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('ID');
		$tindakan_medis = $this->entity->get('TINDAKAN_MEDIS');
		$cek = $this->load(array('ID'=>$id, "TINDAKAN_MEDIS" => $tindakan_medis));
		if(count($cek) == 1) {
			$data = $this->entity->getArrayCopy();
			$this->table->update($data, array('ID'=>$id, "TINDAKAN_MEDIS" => $tindakan_medis));
			
		} else {
			$data = $this->entity->getArrayCopy();
			$this->table->insert($data);
		}
		
		return array(
			'success' => true,
			'data' => $data
		);
	}

}