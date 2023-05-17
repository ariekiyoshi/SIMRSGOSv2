<?php
namespace Layanan\V1\Rest\OrderDetilResep;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use DBService\System;
use DBService\generator\Generator;
use General\V1\Rest\Referensi\ReferensiService;
use Inventory\V1\Rest\Barang\BarangService;
use Inventory\V1\Rest\HargaBarang\HargaBarangService;

class OrderDetilResepService extends Service
{
	private $tindakan;
	private $referensi;
	private $barang;
	private $hargabarang;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("order_detil_resep", "layanan"));
		$this->entity = new OrderDetilResepEntity();
		
		$this->referensi = new ReferensiService();
		$this->barang = new BarangService();
		$this->hargabarang = new HargaBarangService();
	
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$order_id = $this->entity->get('ORDER_ID');
		$farmasi = $this->entity->get('FARMASI');
		$aturan = is_numeric($this->entity->get('ATURAN_PAKAI')) ? $this->entity->get('ATURAN_PAKAI') : false;
		if(!$aturan){
			if(str_replace(" ","",$this->entity->get('ATURAN_PAKAI')) != '') { 
				$ref = $this->referensi->simpan(array("JENIS" => 41, "DESKRIPSI" => $this->entity->get('ATURAN_PAKAI')));
				$this->entity->set('ATURAN_PAKAI', $ref['data']['ID']);
			}
		}
		$cek = $this->table->select(array("ORDER_ID" => $order_id, "FARMASI" => $farmasi))->toArray();
		if(count($cek) > 0) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("ORDER_ID" => $order_id, "FARMASI" => $farmasi));
		} else {
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
		}
		
		return $this->load(array('order_detil_resep.ORDER_ID' => $order_id));
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			$isId = is_numeric($entity['ATURAN_PAKAI']) ? $entity['ATURAN_PAKAI'] : false;
			if($isId) {
				$aturan = $this->referensi->load(array('ID' => $entity['ATURAN_PAKAI'], 'JENIS'=>41));
				if(count($aturan) > 0) $entity['REFERENSI']['ATURAN_PAKAI'] = $aturan[0];
			}
			
			$jenisresep = $this->referensi->load(array('ID' => $entity['RACIKAN'], 'JENIS'=>47));
			if(count($jenisresep) > 0) $entity['REFERENSI']['RACIKAN'] = $jenisresep[0];
			
			$farmasi = $this->barang->load(array('ID' => $entity['FARMASI']));
			if(count($farmasi) > 0) $entity['REFERENSI']['FARMASI'] = $farmasi[0];
			
			$hargabarang = $this->hargabarang->load(array('BARANG' => $entity['FARMASI'], "STATUS" => 1));
			if(count($hargabarang) > 0) $entity['REFERENSI']['HARGA_BARANG'] = $hargabarang[0];
			
		}
		
		return $data;
	}
}