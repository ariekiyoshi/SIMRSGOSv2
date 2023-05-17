<?php
namespace General\V1\Rest\JenisKunjunganRuangan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use DBService\generator\Generator;
use General\V1\Rest\Ruangan\RuanganService;
use General\V1\Rest\Referensi\ReferensiService;

class JenisKunjunganRuanganService extends Service
{
    private $ruangan;
	private $referensi;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("jenis_kunjungan_ruangan", "master"));
		$this->entity = new JenisKunjunganRuanganEntity();	
		
		$this->ruangan = new RuanganService();
		$this->referensi = new ReferensiService();
		
    }
    
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
				
			foreach($data as &$entity) {
				$ruangan = $this->ruangan->load(array('ID' => $entity['RUANGAN']));
				if(count($ruangan) > 0) $entity['REFERENSI']['RUANGAN'] = $ruangan[0];
				$jeniskunjungan = $this->referensi->load(array('ID' => $entity['JENIS_KUNJUNGAN'], 'JENIS' => 15));
				if(count($jeniskunjungan) > 0) $entity['REFERENSI']['JENIS_KUNJUNGAN'] = $jeniskunjungan[0];
			}
		
		return $data;
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = (int) $this->entity->get('ID');
		$tindakan = $this->entity->get('TINDAKAN');
		if($id > 0){
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$id = Generator::generateIdParameterTindakanLab($tindakan);
			$this->entity->set('ID', $id);
			$this->table->insert($this->entity->getArrayCopy());
		}
		
				
		return array(
			'success' => true
		);
	}
	
	
}
