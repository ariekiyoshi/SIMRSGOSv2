<?php
namespace General\V1\Rest\Tindakan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use Zend\json\Json;
use DBService\System;
use DBService\Service;
use General\V1\Rest\TindakanRuangan\TindakanRuanganService;
use General\V1\Rest\TarifTindakan\TarifTindakanService;
use General\V1\Rest\Referensi\ReferensiService;

class TindakanService extends Service
{
	private $tindakanRuangan;
	private $tarif;
	private $referensi;
	
	protected $references = array(
		'TindakanRuangan' => true,
		'TarifTindakan' => true,
		'Referensi' => true,
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("tindakan", "master"));
		$this->entity = new TindakanEntity();
		
		$this->limit = 5000;
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			if($this->references['TindakanRuangan']) $this->tindakanRuangan = new TindakanRuanganService(true, array('Tindakan' => false));
			if($this->references['TarifTindakan']) $this->tarif = new TarifTindakanService();
			if($this->references['Referensi']) $this->referensi = new ReferensiService();
		}	
    }	
		
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('ID');
		$rows = $this->table->select(array("ID" => $id))->toArray();
		if(count($rows) > 0){
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Referensi']) {
					if(isset($entity['JENIS'])) {
						$referensi = $this->referensi->load(array('JENIS' => 74,'ID' => $entity['JENIS']));
						if(count($referensi) > 0) $entity['REFERENSI']['JENIS'] = $referensi[0];
					}
				}
			}
		}
		
		return $data;
	}
	
	public function cariTindakan($params = array()) {
		$params = is_array($params) ? $params : (array) $params;					
		$tindakans = array();
		$i = 0;
		$prms = array();
		if(isset($params['RUANGAN'])) $prms = array('RUANGAN'=>$params['RUANGAN']);			
		if(isset($params['limit'])) {	
			$prms['limit'] = $params['limit'];
			$prms['start'] = 0;
			if(isset($params['start'])) $prms['start'] = $params['start'];
		} else {
			$prms['limit'] = 2000;
			$prms['start'] = 0;
		}
		if(isset($params['STATUS'])) $prms['STATUS'] = $params['STATUS'];
		if(isset($params['t.STATUS'])) $prms['t.STATUS'] = $params['t.STATUS'];
		
		$data = $this->tindakanRuangan->load($prms);
		
		foreach ($data as $d) {
			$search = array('ID'=>$d['TINDAKAN']);
			if(isset($params['QUERY'])) $search['QUERY'] = $params['QUERY'];			
			$tindks = $this->load($search);
			if(count($tindks) > 0) {
				$tindakans[$i] = $tindks[0];
				if(isset($params['KELAS'])) {
					$tarif = $this->tarif->load(array('TINDAKAN' => $d['TINDAKAN'], 'KELAS' => $params['KELAS'], 'STATUS' => 1));
					if(count($tarif) > 0) $tindakans[$i]['REFERENSI']['TARIF'] = $tarif[0];					
				}
				$i++;
			}
		}
			
		return $tindakans;
    }
	
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) {			
				if(isset($params['JENIS_KUNJUNGAN'])) {
					$select->quantifier($select::QUANTIFIER_DISTINCT);
					$select->columns(array('ID', 'NAMA', 'STATUS'));
				} else {
					$select->columns($columns);
				}
			}
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				if(!$isCount) $select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(isset($params['STATUS'])) {
				$status = $params['STATUS'];
				$params['tindakan.STATUS'] = $status;
				unset($params['STATUS']);
			}
			if(isset($params['JENIS_KUNJUNGAN'])) {
				$jnsKunjungan = $params['JENIS_KUNJUNGAN'];
				$select->join(
					array('tr' => new TableIdentifier('tindakan_ruangan', 'master')),
					'tr.TINDAKAN = tindakan.ID',
					array(),
					$select::JOIN_LEFT
				);
				$select->where('tr.STATUS = 1');
				$select->join(
					array('r' => new TableIdentifier('ruangan', 'master')),
					'r.ID = tr.RUANGAN',
					array(),
					$select::JOIN_LEFT
				);
				$select->where('r.STATUS = 1');
				$select->where('r.JENIS_KUNJUNGAN = '.$jnsKunjungan);
				unset($params['JENIS_KUNJUNGAN']);
			}
			
			if(isset($params['QUERY'])) {
				if(!System::isNull($params, 'QUERY')) {
					$select->where("(ID LIKE '%".$params['QUERY']."%' OR NAMA LIKE '%".$params['QUERY']."%')");
					unset($params['QUERY']);
				}
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}