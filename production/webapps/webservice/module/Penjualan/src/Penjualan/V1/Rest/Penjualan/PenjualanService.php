<?php
namespace Penjualan\V1\Rest\Penjualan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\generator\Generator;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

use Penjualan\V1\Rest\PenjualanDetil\PenjualanDetilService;
use Pembayaran\V1\Rest\Tagihan\TagihanService;

class PenjualanService extends Service
{
	private $penjualandetil;
	private $totaltagihan;
	
	protected $references = array(
		'PenjualanDetil' => true,
		'TotalTagihan' => true
	);
	
     public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("penjualan", "penjualan"));
		$this->entity = new PenjualanEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		if($includeReferences) {			
			if($this->references['PenjualanDetil']) $this->penjualandetil = new PenjualanDetilService();
			if($this->references['TotalTagihan']) $this->totaltagihan = new TagihanService();
		}
		
		
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$nomor = is_numeric($this->entity->get('NOMOR')) ? $this->entity->get('NOMOR') : false;
		$this->entity->set('TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));
		
		if($nomor) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("NOMOR" => $nomor));
		} else {
			$nomor = Generator::generateNoTagihan();
			$this->entity->set('NOMOR', $nomor);
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
		}
		
		$this->SimpanDetilPenjualan($data, $nomor);
		$this->SimpanTagihanPenjualan($data, $nomor);
		
		return $this->load(array('penjualan.NOMOR' => $nomor));
	}
	
	private function SimpanDetilPenjualan($data, $id) {
		if(isset($data['PENJUALAN_DETIL'])) {
			
			foreach($data['PENJUALAN_DETIL'] as $dtl) {
				$dtl['PENJUALAN_ID'] = $id;
				$this->penjualandetil->simpan($dtl);
			}
		}
	}
	
	private function SimpanTagihanPenjualan($data, $id) {
		if(isset($data['TOTAL_PENJUALAN'])) {
			foreach($data['TOTAL_PENJUALAN'] as $dtl) {
				$dtl['ID'] = $id;
				$this->totaltagihan->simpan($dtl);
			}
		}
	}
}