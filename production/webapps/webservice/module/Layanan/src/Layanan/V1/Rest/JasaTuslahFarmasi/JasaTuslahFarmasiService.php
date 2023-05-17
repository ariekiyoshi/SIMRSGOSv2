<?php
namespace Layanan\V1\Rest\JasaTuslahFarmasi;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use DBService\System;
use DBService\generator\Generator;
use Inventory\V1\Rest\Barang\BarangService;
use Inventory\V1\Rest\HargaBarang\HargaBarangService;

class JasaTuslahFarmasiService extends Service
{
	private $barang;
	private $hargabarang;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("jasa_tuslah_farmasi", "layanan"));
		$this->entity = new JasaTuslahFarmasiEntity();
		
		$this->barang = new BarangService();
		$this->hargabarang = new HargaBarangService();
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : false;
		if($id) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("ID" => $id));
		}else {
			$id = Generator::generateIdFarmasi();
			
			$this->entity->set('ID', $id);
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
		}
		
		return $this->load(array('jasa_tuslah_farmasi.ID' => $id));
		/*
		return array(
			'success' => true,
			'data' => $this->load(array('ID' => $id))
		);*/
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			$barang = $this->barang->load(array('ID' => $entity['FARMASI']));
			if(count($barang) > 0) $entity['REFERENSI']['FARMASI'] = $barang[0];
			
			$hargabarang = $this->hargabarang->load(array('BARANG' => $entity['FARMASI'], "STATUS" => 1));
			if(count($hargabarang) > 0) $entity['REFERENSI']['HARGA_BARANG'] = $hargabarang[0];
		}
		
		return $data;
	}
}