<?php
namespace MedicalRecord\V1\Rest\Operasi;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use DBService\System;

use MedicalRecord\V1\Rest\OperasiDiTindakan\OperasiDiTindakanService;
use General\V1\Rest\Dokter\DokterService;

use Pendaftaran\V1\Rest\Kunjungan\KunjunganService;

class OperasiService extends Service
{
	private $tindakan;
	private $dokter;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("operasi", "medicalrecord"));
		$this->entity = new OperasiEntity();
		
		$this->tindakan = new OperasiDiTindakanService();
		$this->dokter = new DokterService();
		$this->kunjungan = new KunjunganService();
    }
        
		
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$this->entity->set('DIBUAT_TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));
		$id = $this->entity->get('ID');
		$cek = $this->load(array('ID'=>$this->entity->get('ID')));
		
		if(count($cek) > 0) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("ID" => $this->entity->get('ID')));
		} else {
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
			$id = $this->table->getLastInsertValue();
		}
		
		$this->simpanTindakan($data, $id);
		
		return array(
			'success' => true,
			'data' => $this->load(array('ID' => $id))
		);
	}
	
	private function simpanTindakan($data, $id) {
	
		if(isset($data['TINDAKAN_MEDIS'])) {
			$i = 0;
			foreach($data['TINDAKAN_MEDIS'] as $td) {
				$td[$i]['ID'] = $id;
				$td[$i]['TINDAKAN_MEDIS'] = $td['ID'];
				$td[$i]['STATUS'] = $td['STATUS'];
				$this->tindakan->simpan($td[$i]); 
				$i++;
			}
		}
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
			foreach($data as &$entity) {
				$dokter = $this->dokter->load(array('ID' => $entity['DOKTER']));
				if(count($dokter) > 0) $entity['REFERENSI']['DOKTER'] = $dokter[0];
				$kunjungan = $this->kunjungan->load(array('NOMOR' => $entity['KUNJUNGAN']));
				if(count($kunjungan) > 0) $entity['REFERENSI']['KUNJUNGAN'] = $kunjungan[0];
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
			
			if(!System::isNull($params, 'TANGGAL')) {
				$tanggal = $params['TANGGAL'];
				$params['operasi.TANGGAL'] = $tanggal;
				unset($params['TANGGAL']);
			}
			
			if(!System::isNull($params, 'STATUS')) {
				$status = $params['STATUS'];
				$params['operasi.STATUS'] = $status;
				unset($params['STATUS']);
			}
			
			$select->join(
				array('kjgn' => new TableIdentifier('kunjungan', 'pendaftaran')),
				'kjgn.NOMOR = operasi.KUNJUNGAN',
				array('NOPEN')
			);
			
			$select->join(
				array('pdftrn' => new TableIdentifier('pendaftaran', 'pendaftaran')),
				'pdftrn.NOMOR = kjgn.NOPEN',
				array()
			);
			
			if(isset($params['NAMA_OPERASI'])) {
				$select->where("(operasi.NAMA_OPERASI LIKE '%".$params['NAMA_OPERASI']."%')");
				unset($params['NAMA_OPERASI']);
			}
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}