<?php
/**
 * INACBGService
 * @author hariansyah
 * 
 */
 
namespace INACBGService;
	
use Zend\Json\Json;
use Zend\Db\Adapter\Adapter;
use INACBGService\V1\Rest\TipeINACBG\Service as TipeINACBGService;

class Service {
	private $config;
	private $tipe;	
	
	function __construct($config) {
		$this->config = $config;
		$this->tipe = new TipeINACBGService();
	}
	
	public function getKodeRS() {
		return $this->config["koders"];
	}
	
	/*$user_nm,		$user_pw,			$norm,				$nm_pasien, 
	$jns_kelamin, 	$tgl_lahir,			$jns_pbyrn,			$no_peserta,
	$no_sep,		$jns_perawatan,		$kls_perawatan,		$tgl_masuk,
	$tgl_keluar,	$cara_keluar,		$dpjp,				$berat_lahir,
	$tarif_rs,		$srt_rujukan,		$bhp,				$severity3,
	$diag1,			$diag2,				$diag3,				$diag4,
	$diag5,			$diag6,				$diag7,				$diag8,
	$diag9,			$diag10,			$diag11,			$diag12,
	$diag13,		$diag14,			$diag15,			$diag16,
	$diag17,		$diag18,			$diag19,			$diag20,
	$diag21,		$diag22,			$diag23,			$diag24,
	$diag25,		$diag26,			$diag27,			$diag28,
	$diag29,		$diag30,		    $proc1,			    $proc2,	
	$proc3,			$proc4,				$proc5,		    	$proc6,				
	$proc7,			$proc8,				$proc9,		    	$proc10,
	$proc11,		$proc12,			$proc13,		    $proc14,	
	$proc15,		$proc16,			$proc17,		    $proc18,
	$proc19,		$proc20,			$proc21,		    $proc22,
	$proc23,		$proc24,			$proc25,		    $proc26,		
	$proc27,		$proc28,			$proc29,		    $proc30,	
	$adl,			$spec_proc,			$spec_dr,			$spec_inv,
	$spec_prosth,	$nopen,             $user				$status
	$tipe*/

	public function grouper(array $data = null) {
		if($this->createService($data)) {			
			return $this->service->grouper($data);
		}
		return null;
	}
	
	public function kirimKlaimIndividual(array $data = null) {
		if($this->createService($data)) {			
			return $this->service->kirimKlaimIndividual($data);
		}
		return null;
	}
	
	public function kirimKlaim(array $data = null) {
		if($this->createService($data)) {			
			return $this->service->kirimKlaim($data);
		}
		return null;
	}
	
	public function batalKlaim(array $data = null) {
		if($this->createService($data)) {			
			return $this->service->batalKlaim($data);
		}
		return null;
	}
	
	private function createService(array $data = null) {
		if($data) {
			$tipes = $this->tipe->load(array("ID" => $data["tipe"]));
			if(count($tipes) == 0) return null;
			$version = $tipes[0]["VERSION"];
			$nama = $tipes[0]["NAMA"];
			
			$class = "\\INACBGService\\V".$version."\\Service";
			
			$this->service = new $class($this->config[$version][$nama], $this->tipe->getAdapter());
			//$this->service = new \INACBGService\V4\Service($this->config[$version][$nama], $this->tipe->getAdapter());
			return $this->service;
		}
		return null;
	}
}