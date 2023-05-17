<?php
namespace Penjualan\V1\Rest\PenjualanDetil;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\generator\Generator;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

use Inventory\V1\Rest\Barang\BarangService;
use Inventory\V1\Rest\HargaBarang\HargaBarangService;
use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\MarginPenjaminFarmasi\Service as MarginService;
use Penjualan\V1\Rest\ReturPenjualan\ReturPenjualanService;

class PenjualanDetilService extends Service
{
    private $barang;
	private $hargabarang;
	private $referensi;
	private $marginpenjaminfarmasi;
	private $retur;
	
	public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("penjualan_detil", "penjualan"));
		$this->entity = new PenjualanDetilEntity();
		
		$this->barang = new BarangService();
		$this->hargabarang = new HargaBarangService();
		$this->referensi = new ReferensiService();
		$this->marginpenjaminfarmasi = new MarginService();
		$this->retur = new ReturPenjualanService();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('PENJUALAN_ID');
		$barang = $this->entity->get('BARANG');
		
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : 0;
		
		if($id == 0) {
			$this->table->insert($this->entity->getArrayCopy());
		} else {
			$this->table->update($this->entity->getArrayCopy(), array('ID'=>$id));
		}
		
		return array(
			'success' => true
		);
	}
	
	public function load($params = array(), $columns = array('*'), $penjualans = array()) {
		$data = parent::load($params, $columns, $penjualans);
		
		foreach($data as &$entity) {
			$aturan = $this->referensi->load(array('ID' => $entity['ATURAN_PAKAI'], 'JENIS'=>41));
			if(count($aturan) > 0) $entity['REFERENSI']['ATURAN_PAKAI'] = $aturan[0];
			
			$barang = $this->barang->load(array('ID' => $entity['BARANG']));
			if(count($barang) > 0) $entity['REFERENSI']['FARMASI'] = $barang[0];
			
			$hargabarang = $this->hargabarang->load(array('ID' => $entity['HARGA_BARANG']));
			if(count($hargabarang) > 0) $entity['REFERENSI']['HARGA_BARANG'] = $hargabarang[0];
			
			$marginpenjaminfarmasi = $this->marginpenjaminfarmasi->load(array('ID' => $entity['MARGIN']));
			if(count($marginpenjaminfarmasi) > 0) $entity['REFERENSI']['MARGIN_PENJAMIN'] = $marginpenjaminfarmasi[0];
			
			$retur = $this->retur->load(array('PENJUALAN_DETIL_ID' => $entity['ID'], 'STATUS' => 1));
			if(count($retur) > 0) $entity['REFERENSI']['RETUR'] = $retur;
			
		}
		
		return $data;
	}
}