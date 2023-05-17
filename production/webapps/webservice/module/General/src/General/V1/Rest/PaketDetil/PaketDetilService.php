<?php
namespace General\V1\Rest\PaketDetil;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use General\V1\Rest\Tindakan\TindakanService;
use Inventory\V1\Rest\Barang\BarangService;
use General\V1\Rest\Administrasi\AdministrasiService;

class PaketDetilService extends Service
{
	private $tindakan;
	private $barang;
	private $administrasi;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("paket_detil", "master"));
		$this->entity = new PaketDetilEntity();
		
		$this->tindakan = new TindakanService();
		$this->barang = new BarangService();
		$this->administrasi = new AdministrasiService();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		
		$id = $this->entity->get('ID');
		
		$cek = $this->table->select(array("ID" => $id))->toArray();
		if(count($cek) > 0) {
			$data = $this->entity->getArrayCopy();
			$this->table->update($data, array("ID" => $id));
		} else {
			$data = $this->entity->getArrayCopy();
			$this->table->insert($data);
		}
		
		return array(
			'success' => true,
			'data' => $data
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			if($entity['JENIS'] == 1) {
				$namaitem = $this->tindakan->load(array('ID' => $entity['ITEM']));
				if(count($namaitem) > 0) $entity['REFERENSI']['NAMA_ITEM'] = $namaitem[0];
			} else if($entity['JENIS'] == 2) {
				$namaitem = $this->barang->load(array('ID' => $entity['ITEM']));
				if(count($namaitem) > 0) $entity['REFERENSI']['NAMA_ITEM'] = $namaitem[0];
			} elseif($entity['JENIS'] == 3) {
				$namaitem = $this->administrasi->load(array('ID' => $entity['ITEM']));
				if(count($namaitem) > 0) $entity['REFERENSI']['NAMA_ITEM'] = $namaitem[0];
			} else {
				$entity['REFERENSI']['NAMA_ITEM'] = array(
					"ID" => 0,
					"NAMA" => "O2",
					"STATUS" => "1"
				);
			}
		}
		
		return $data;
	}
}