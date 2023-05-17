<?php
namespace Layanan\V1\Rest\Farmasi;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use DBService\System;
use DBService\generator\Generator;
use Inventory\V1\Rest\Barang\BarangService;
use General\V1\Rest\Referensi\ReferensiService;
use Inventory\V1\Rest\HargaBarang\HargaBarangService;
use Layanan\V1\Rest\ReturFarmasi\ReturFarmasiService;

class FarmasiService extends Service
{
	private $barang;
	private $referensi;
	private $hargabarang;
	private $returfarmasi;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("farmasi", "layanan"));
		$this->entity = new FarmasiEntity();
		
		$this->barang = new BarangService();
		$this->referensi = new ReferensiService();
		$this->hargabarang = new HargaBarangService(false);
		$this->returfarmasi = new ReturFarmasiService();
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : false;
		if(!System::isNull($data, 'ATURAN_PAKAI')){
			$aturan = is_numeric($this->entity->get('ATURAN_PAKAI')) ? $this->entity->get('ATURAN_PAKAI') : false;
		
			if(!$aturan){
				$ref = $this->referensi->simpan(array("JENIS" => 41, "DESKRIPSI" => $this->entity->get('ATURAN_PAKAI')));
				$this->entity->set('ATURAN_PAKAI', $ref['data']['ID']);
			}
		}
		if($id) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("ID" => $id));
		}else {
			$id = Generator::generateIdFarmasi();
			
			$this->entity->set('ID', $id);
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
		}
		
		return $this->load(array('farmasi.ID' => $id));
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			$barang = $this->barang->load(array('ID' => $entity['FARMASI']));
			if(count($barang) > 0) $entity['REFERENSI']['FARMASI'] = $barang[0];
			
			$status = $this->referensi->load(array('ID' => $entity['STATUS'], 'JENIS'=>40));
			if(count($status) > 0) $entity['REFERENSI']['STATUS'] = $status[0];
			
			$isId = is_numeric($entity['ATURAN_PAKAI']) ? $entity['ATURAN_PAKAI'] : false;
			if($isId) {
				$aturan = $this->referensi->load(array('ID' => $entity['ATURAN_PAKAI'], 'JENIS'=>41));
				if(count($aturan) > 0) $entity['REFERENSI']['ATURAN_PAKAI'] = $aturan[0];
			}
			
			$hargabarang = $this->hargabarang->load(array('BARANG' => $entity['FARMASI'], "STATUS" => 1));
			if(count($hargabarang) > 0) $entity['REFERENSI']['HARGA_BARANG'] = $hargabarang[0];
			/*$farmasi = $this->barang->load(array('ID' => $entity['FARMASI']));
			if(count($farmasi) > 0) $entity['REFERENSI']['FARMASI'] = $farmasi[0];*/
			
			$returfarmasi = $this->returfarmasi->load(array('ID_FARMASI' => $entity['ID']));
			if(count($returfarmasi) > 0) $entity['REFERENSI']['RETUR'] = $returfarmasi;
		}
		
		return $data;
	}
	
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				if(!$isCount) $select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			$norm = isset($params['NORM']) ? $params['NORM'] : '';
			
			if(!System::isNull($params, 'STATUS')) {
				$status = $params['STATUS'];
				$params['farmasi.STATUS'] = $status;
				unset($params['STATUS']);
			}
			
			if($norm != ''){
				$select->join(
					array('k' => new TableIdentifier('kunjungan', 'pendaftaran')),
					'k.NOMOR = farmasi.KUNJUNGAN',
					array('NOPEN')
				);
				
				$select->join(
					array('p' => new TableIdentifier('pendaftaran', 'pendaftaran')),
					'p.NOMOR = k.NOPEN',
					array('NORM')
				);
			//unset($params['NORM']);
				
			}
			
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}