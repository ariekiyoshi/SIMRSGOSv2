<?php
namespace Inventory\V1\Rest\Barang;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use Inventory\V1\Rest\Kategori\KategoriService;
use Inventory\V1\Rest\HargaBarang\HargaBarangService;
use Inventory\V1\Rest\Penyedia\PenyediaService;
use Inventory\V1\Rest\Satuan\SatuanService;
use General\V1\Rest\Referensi\ReferensiService;

class BarangService extends Service
{
	private $kategori;
    private $penyedia;
	private $satuan;
	private $hargaBarang;
    private $referensi;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("barang", "inventory"));
		$this->entity = new BarangEntity();
		
		$this->kategori = new KategoriService();
        $this->penyedia = new PenyediaService();
		$this->satuan = new SatuanService();
		$this->hargaBarang = new HargaBarangService(false);
        $this->referensi = new ReferensiService();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = (int) $this->entity->get('ID');
		
		if($id > 0){
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
		foreach($data as &$entity) {
			$kategori = $this->kategori->load(array('ID' => $entity['KATEGORI']));
			if(count($kategori) > 0) $entity['REFERENSI']['KATEGORI'] = $kategori[0];
            $penyedia = $this->penyedia->load(array('ID' => $entity['PENYEDIA']));
			if(count($penyedia) > 0) $entity['REFERENSI']['PENYEDIA'] = $penyedia[0];
			$satuan = $this->satuan->load(array('ID' => $entity['SATUAN']));
			if(count($satuan) > 0) $entity['REFERENSI']['SATUAN'] = $satuan[0];
			$hargaBarang = $this->hargaBarang->load(array('BARANG' => $entity['ID'], 'STATUS' => 1));
			if(count($hargaBarang) > 0) $entity['REFERENSI']['HARGA'] = $hargaBarang[0];
            $referensi = $this->referensi->load(array('JENIS' => 42, 'ID' => $entity['GENERIK']));
			if(count($referensi) > 0) $entity['REFERENSI']['GENERIK'] = $referensi[0];
            $referensi = $this->referensi->load(array('JENIS' => 39, 'ID' => $entity['MERK']));
			if(count($referensi) > 0) $entity['REFERENSI']['MERK'] = $referensi[0];
		}
				
		return $data;
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
			
			if(!System::isNull($params, 'QUERY')) { 
				$select->where("NAMA LIKE '%".$params['QUERY']."%'");
				unset($params['QUERY']);
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}
