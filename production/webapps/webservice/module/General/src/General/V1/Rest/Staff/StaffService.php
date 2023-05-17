<?php
namespace General\V1\Rest\Staff;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use General\V1\Rest\StaffRuangan\StaffRuanganService;
use General\V1\Rest\Pegawai\PegawaiService;

class StaffService extends Service
{
    private $staffRuangan;
	private $pegawai;
    
	protected $references = array(
		'StaffRuangan' => true,
		'Pegawai' => true
	);
	
	public function __construct($includeReferences = true, $references = array()) {
         $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("staff", "master"));
		$this->entity = new StaffEntity();
		
		$this->limit = 3000;
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			if($this->references['Pegawai']) $this->pegawai = new PegawaiService();
			if($this->references['StaffRuangan']) $this->staffRuangan = new StaffRuanganService();			
		}
    }
   
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		$staffs = array(); 
		$i = 0;
		foreach($data as $entity) {
			$staffs[$i]['ID'] = $entity['ID'];
			$staffs[$i]['NAMA'] = $this->namaPegawai($entity['NIP']);
			$staffs[$i]['NIP'] = $entity['NIP'];
			$i++;
		}
		
		return $staffs;
	}
	
	public function cariStaff($params = array()) {
		$params = is_array($params) ? $params : (array) $params;					
		$staffs = array();
		$i = 0;
		
		if(isset($params['RUANGAN'])) 
			$data = $this->staffRuangan->load(array('RUANGAN'=>$params['RUANGAN']));
			
							
		foreach ($data as $d){
			$stfs = $this->load(array('ID'=>$d['STAFF']));						
			if(count($stfs) > 0) {
				$staffs[$i] = $stfs[0];
				$i++;
			}
		}	
		
		return $staffs;
    }	
		
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				$select->offset((int) $parsams['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(!System::isNull($params, 'ID')) {				
				$params["staff.ID"] = $params["ID"];
				unset($params["ID"]);
			}
			
			if(!System::isNull($params, 'NAMA')) { 
				$select->join(array('p' => new TableIdentifier("pegawai", "master")), "staff.NIP = p.NIP", array("NIP", "NAMA", "GELAR_DEPAN", "GELAR_BELAKANG"));
				$select->where("p.NAMA LIKE '%".$params['NAMA']."%'");
				unset($params['NAMA']);
			}
			
			if(isset($params['QUERY'])) if(!System::isNull($params, 'QUERY')) {
				$select->join(array('p' => new TableIdentifier("pegawai", "master")), "staff.NIP = p.NIP", array("NIP", "NAMA", "GELAR_DEPAN", "GELAR_BELAKANG"));
				$select->where("(p.NAMA LIKE '%".$params['QUERY']."%' OR p.NIP LIKE '".$params['QUERY']."%')");
				unset($params['QUERY']);
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
	
	private function namaPegawai($nip){
		$peg = $this->pegawai->load(array('NIP'=>$nip));
		if(str_replace(' ','', $peg[0]['GELAR_DEPAN']) != ''){
			$titik = '. ';
		}else{
			$titik = '';
		}
		if(str_replace(' ','', $peg[0]['GELAR_BELAKANG']) != ''){
			$koma = ', ';
		}else{
			$koma = '';
		}
		return $peg[0]['GELAR_DEPAN'].$titik.$peg[0]['NAMA'].$koma.$peg[0]['GELAR_BELAKANG'];
	}
}