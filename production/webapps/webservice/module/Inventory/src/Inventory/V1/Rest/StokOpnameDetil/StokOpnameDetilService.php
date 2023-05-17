<?php
namespace Inventory\V1\Rest\StokOpnameDetil;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Inventory\V1\Rest\BarangRuangan\BarangRuanganService;

class StokOpnameDetilService extends Service
{
	private $barangruangan;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("stok_opname_detil", "inventory"));
		$this->entity = new StokOpnameDetilEntity();
		
		$this->barangruangan = new BarangRuanganService();		
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$stok_opname = $this->entity->get('STOK_OPNAME');
		$barang_ruangan = $this->entity->get('BARANG_RUANGAN');
		$tanggal = $this->entity->get('TANGGAL');
		
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
			
			$barangruangan = $this->barangruangan->load(array('ID' => $entity['BARANG_RUANGAN']));
			if(count($barangruangan) > 0) $entity['REFERENSI']['BARANG_RUANGAN'] = $barangruangan[0];
			
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
			
			if(!System::isNull($params, 'ID')) {
			    $id = $params['ID'];
			    $params['stok_opname_detil.ID'] = $id;
			    unset($params['ID']);
			}
			
			$select->join(
				array('br' => new TableIdentifier('barang_ruangan', 'inventory')),
				'br.ID = BARANG_RUANGAN',
				array('BARANG' => 'BARANG')
			);
		
			$select->join(
				array('b' => new TableIdentifier('barang', 'inventory')),
				'b.ID = BARANG',
				array('NAMA' => 'NAMA')
			);
			
			if(!System::isNull($params, 'MANUAL')) { 
				if($params['MANUAL'] == "") {
					$select->where("MANUAL IS NULL");
					unset($params['MANUAL']);
				}
			}
				
			if(!System::isNull($params, 'QUERY')) { 
				$select->where("NAMA LIKE '%".$params['QUERY']."%'");
				unset($params['QUERY']);
			}
			
			if(!System::isNull($params, 'KATEGORI')) { 
				$select->where("KATEGORI LIKE '".$params['KATEGORI']."'");
				unset($params['KATEGORI']);
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}
