<?php
namespace INACBGService\V1\Rest\HasilGrouping;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use INACBGService\V1\Rest\Inacbg\InacbgService;
use Aplikasi\V1\Rest\Pengguna\PenggunaService;
use INACBGService\V1\Rest\TipeINACBG\Service as TipeINACBGService;

class HasilGroupingService extends Service
{	
	private $inacbg;
	private $pengguna;
	private $tipe;
	
	protected $references = array(
		'Inacbg' => true,
		'Pengguna' => true
	);
	
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get("INACBG")->get('hasil_grouping'); 
		$this->entity = new HasilGroupingEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		$this->tipe = new TipeINACBGService();
		
		if($includeReferences) {
			if($this->references['Inacbg']) $this->inacbg = new InacbgService();
			if($this->references['Pengguna']) $this->pengguna = new PenggunaService();
		}
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$nopen = $this->entity->get('NOPEN');
		
		$found = $this->load(array('NOPEN' => $nopen));
		if(count($found) > 0){
			$this->table->update($this->entity->getArrayCopy(), array("NOPEN" => $nopen));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return $this->load(array('NOPEN' => $nopen));
	}
	
	public function hapus($nopen) {
		$this->table->delete(array("NOPEN" => $nopen));
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);

		if($this->includeReferences) {
			foreach($data as &$entity) {
				$tipeInacbg = $this->tipe->load(array("ID" => $entity['TIPE']));
				$version = "4";
				if(count($tipeInacbg) > 0) $version = $tipeInacbg[0]["VERSION"];
				if($this->references['Inacbg']) {
					$inacbg = $this->inacbg->load(array('JENIS' => 1, 'KODE' => $entity['CODECBG'], 'VERSION' => $version));
					if(count($inacbg) > 0) $entity['REFERENSI']['CODECBG'] = $inacbg[0];
					
					$inacbg = $this->inacbg->load(array('JENIS' => 4, 'KODE' => str_replace("-", "", substr($entity['UNUSR'], 0, 5)), 'VERSION' => $version));
					if(count($inacbg) > 0) $entity['REFERENSI']['UNUSR'] = $inacbg[0];
					
					$inacbg = $this->inacbg->load(array('JENIS' => 5, 'KODE' => str_replace("-", "", substr($entity['UNUSI'], 0, 5)), 'VERSION' => $version));
					if(count($inacbg) > 0) $entity['REFERENSI']['UNUSI'] = $inacbg[0];
					
					$inacbg = $this->inacbg->load(array('JENIS' => 6, 'KODE' => str_replace("-", "", substr($entity['UNUSP'], 0, 5)), 'VERSION' => $version));
					if(count($inacbg) > 0) $entity['REFERENSI']['UNUSP'] = $inacbg[0];
					
					$inacbg = $this->inacbg->load(array('JENIS' => 3, 'KODE' => str_replace("-", "", substr($entity['UNUSD'], 0, 5)), 'VERSION' => $version));
					if(count($inacbg) > 0) $entity['REFERENSI']['UNUSD'] = $inacbg[0];
					
					$inacbg = $this->inacbg->load(array('JENIS' => 7, 'KODE' => str_replace("-", "", substr($entity['UNUSA'], 0, 5)), 'VERSION' => $version));
					if(count($inacbg) > 0) $entity['REFERENSI']['UNUSA'] = $inacbg[0];
					
					$inacbg = $this->inacbg->load(array('JENIS' => 1, 'KODE' => str_replace("-", "", substr($entity['UNUSC'], 0, 5)), 'VERSION' => $version));
					if(count($inacbg) > 0) $entity['REFERENSI']['UNUSC'] = $inacbg[0];
				}
				if($this->references['Pengguna']) {
					$pengguna = $this->pengguna->load(array('ID' => $entity['USER']));
					if(count($pengguna) > 0) $entity['REFERENSI']['USER'] = $pengguna[0];
				}
			}
		}
		
		return $data;
	}
}
