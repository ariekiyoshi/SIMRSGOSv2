<?php
namespace General\V1\Rest\Perawat;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use General\V1\Rest\PerawatRuangan\PerawatRuanganService;
use General\V1\Rest\Pegawai\PegawaiService;

class PerawatService extends Service
{
    private $perawatRuangan;
	private $pegawai;
	
	protected $references = array(
		'Ruangan' => true,
		'Pegawai' => true
	);
    
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("perawat", "master"));
		$this->entity = new PerawatEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		if($includeReferences) {			
			if($this->references['Ruangan']) $this->ruangan = new PerawatRuanganService();
			if($this->references['Pegawai']) $this->pegawai = new PegawaiService();
		}
    }

	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		$perawats = array(); 
		$i = 0;
		
		foreach($data as $entity) {

			$perawats[$i]['ID'] = $entity['ID'];
			$perawats[$i]['NAMA'] = $this->namaPegawai($entity['NIP']);
			$perawats[$i]['NIP'] = $entity['NIP'];
			$i++;
		}
		
		return $perawats;
	}
	
	public function cariPerawat($params = array()) {
		$params = is_array($params) ? $params : (array) $params;					
		$perawats = array();
		$i = 0;
		 
		if(isset($params['RUANGAN']))
			$data = $this->ruangan->load(array('RUANGAN'=>$params['RUANGAN']));
							
		foreach ($data as $d) {
			$search = array('ID'=>$d['PERAWAT']);
			if(isset($params['QUERY'])) $search['QUERY'] = $params['QUERY'];
			$pers = $this->load($search);		
			if(count($pers) > 0) {
				$perawats[$i] = $pers[0];
				$i++;
			}
		}
		
		return $perawats;
    }	
		
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				$select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(!System::isNull($params, 'ID')) {				
				$params["perawat.ID"] = $params["ID"];
				unset($params["ID"]);
			}
			
			if(!System::isNull($params, 'NAMA')) { 
				$select->join(array('p' => new TableIdentifier("pegawai", "master")), "perawat.NIP = p.NIP", array("NIP", "NAMA", "GELAR_DEPAN", "GELAR_BELAKANG"));
				$select->where("p.NAMA LIKE '%".$params['NAMA']."%'");
				unset($params['NAMA']);
			}
			
			if(isset($params['QUERY'])) if(!System::isNull($params, 'QUERY')) {
				$select->join(array('p' => new TableIdentifier("pegawai", "master")), "perawat.NIP = p.NIP", array("NIP", "NAMA", "GELAR_DEPAN", "GELAR_BELAKANG"));
				$select->where("(p.NAMA LIKE '%".$params['QUERY']."%' OR p.NIP LIKE '".$params['QUERY']."%')");
				unset($params['QUERY']);
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
	
	private function namaPegawai($nip){
		$peg = $this->pegawai->load(array('NIP'=>$nip), array("NIP", "NAMA", "GELAR_DEPAN", "GELAR_BELAKANG"));
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