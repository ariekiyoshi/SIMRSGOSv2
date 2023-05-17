<?php
namespace General\V1\Rest\ParameterTindakanLab;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use DBService\generator\Generator;
use General\V1\Rest\Tindakan\TindakanService;
use General\V1\Rest\Referensi\ReferensiService;

class ParameterTindakanLabService extends Service
{
    private $tindakan;
	private $referensi;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("parameter_tindakan_lab", "master"));
		$this->entity = new ParameterTindakanLabEntity();	
		
		$this->tindakan = new TindakanService();
		$this->referensi = new ReferensiService();
    }
    
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
				
			foreach($data as &$entity) {
				$tindakan = $this->tindakan->load(array('ID' => $entity['TINDAKAN']));
				if(count($tindakan) > 0) $entity['REFERENSI']['TINDAKAN'] = $tindakan[0];
				$satuan = $this->referensi->load(array('ID' => $entity['SATUAN'], 'JENIS' => 35));
				if(count($satuan) > 0) $entity['REFERENSI']['SATUAN'] = $satuan[0];
			}
		
		return $data;
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : false;
		$tindakan = $this->entity->get('TINDAKAN');
		if($id){
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("ID" => $id));
		} else {
			$id = Generator::generateIdParameterTindakanLab($tindakan);
			
			$this->entity->set('ID', $id);
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
		}
				
		return array(
			'success' => true
		);
	}
}
