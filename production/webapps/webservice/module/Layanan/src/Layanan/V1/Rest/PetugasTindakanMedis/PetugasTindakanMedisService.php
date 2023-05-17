<?php
namespace Layanan\V1\Rest\PetugasTindakanMedis;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\TenagaMedis\TenagaMedisService;
use Zend\Db\Sql\Select;
use DBService\System;

class PetugasTindakanMedisService extends Service
{
	private $referensi;
	private $tenagamedis;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("petugas_tindakan_medis", "layanan"));
		$this->entity = new PetugasTindakanMedisEntity();
		
		$this->referensi = new ReferensiService();
		$this->tenagamedis = new TenagaMedisService();
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		foreach($data as &$entity) {		
			$referensi = $this->referensi->load(array('JENIS' => 32,'ID' => $entity['JENIS']));
			if(count($referensi) > 0) $entity['REFERENSI']['JENIS'] = $referensi[0];
			
			$tenagamedis = $this->tenagamedis->load(array('JENIS' => $entity['JENIS'],'ID' => $entity['MEDIS']));
			if(count($tenagamedis) > 0) $entity['REFERENSI']['MEDIS'] = $tenagamedis[0];
		}
		
		return $data;
	}
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$tindakan_medis = $this->entity->get('TINDAKAN_MEDIS');
		$jenis = $this->entity->get('JENIS');
		$medis = $this->entity->get('MEDIS');
		
		$cek = $this->load(array("TINDAKAN_MEDIS" => $tindakan_medis, "JENIS" => $jenis, "MEDIS" => $medis));
		if(count($cek) > 0) {
			$data = $this->entity->getArrayCopy();
			$this->table->update($data, array("TINDAKAN_MEDIS" => $tindakan_medis, "JENIS" => $jenis, "MEDIS" => $medis));
		} else {
			$data = $this->entity->getArrayCopy();
			$this->table->insert($data);
		}
		
		return array(
			'success' => true,
			'data' => $data
		);
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
			
			/*if(isset($params['ID'])) {
				$select->where->like('ruangan.ID', $params['ID'].'%');
				unset($params['ID']);
			}
			if(isset($params['DESKRIPSI'])) {
				$select->where->like('DESKRIPSI', $params['DESKRIPSI'].'%');
				unset($params['DESKRIPSI']);
			}*/			
			if(isset($params['KUNJUNGAN'])) {
				$select->join(
					array('tm' => new TableIdentifier('tindakan_medis', 'layanan')),
					"tm.ID = petugas_tindakan_medis.TINDAKAN_MEDIS",
					isset($params['SHOW_TINDAKAN_JASA']) ? array("TANGGAL_TINDAKAN" => "TANGGAL") : array()
				);
				$select->join(
					array('t' => new TableIdentifier('tindakan', 'master')),
					"t.ID = tm.TINDAKAN",
					isset($params['SHOW_TINDAKAN_JASA']) ? array("NAMA_TINDAKAN" => "NAMA") : array()
				);
				$select->join(
					array('rt' => new TableIdentifier('rincian_tagihan', 'pembayaran')),
					"rt.REF_ID = tm.ID",
					array()
				);
				$select->join(
					array('tt' => new TableIdentifier('tarif_tindakan', 'master')),
					"tt.ID = rt.TARIF_ID",
					array(new \Zend\Db\Sql\Expression('DOKTER_OPERATOR'))
				);
				$select->where("tm.KUNJUNGAN = '".$params['KUNJUNGAN']."' AND tm.STATUS = 1 AND rt.JENIS = 3");
				unset($params['KUNJUNGAN']);
				unset($params['SHOW_TINDAKAN_JASA']);
			}
			if(isset($params['DOKTER'])) {
				$select->join(
					array('d' => new TableIdentifier('dokter', 'master')),
					"d.ID = petugas_tindakan_medis.MEDIS",
					array()
				);
				$select->join(
					array('p' => new TableIdentifier('pegawai', 'master')),
					"p.NIP = d.NIP",
					array()
				);
				$select->where("petugas_tindakan_medis.JENIS IN (1, 2) AND p.NAMA LIKE '%".$params['DOKTER']."%'");
				unset($params['DOKTER']);
			}
			
			if($this->privilage) {
				//$select->where("EXISTS(SELECT 1 FROM aplikasi.pengguna_akses_ruangan par WHERE par.RUANGAN LIKE CONCAT(ruangan.ID, '%') AND par.STATUS = 1)");
			}
										
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}