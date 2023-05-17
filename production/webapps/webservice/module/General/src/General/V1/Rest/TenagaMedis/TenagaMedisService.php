<?php
namespace General\V1\Rest\TenagaMedis;

use General\V1\Rest\Dokter\DokterService;
use General\V1\Rest\Perawat\PerawatService;
use General\V1\Rest\Pegawai\PegawaiService;

class TenagaMedisService
{
	private $tenagamedis = array();
    
    public function __construct() {
		$this->tenagamedis[1] = new DokterService();	
		$this->tenagamedis[2] = new DokterService();
		$this->tenagamedis[3] = new PerawatService();
		$this->tenagamedis[6] = new PegawaiService();
		$this->tenagamedis[7] = new PegawaiService();
    }

	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$jenis = $params['JENIS'];
		if(isset($jenis) && isset($this->tenagamedis[$jenis])) {
			unset($params['JENIS']);
			if($jenis > 3) {
				$maProfesi = array(
					6 => 2,
					7 => 10
				);
				$params["PROFESI"] = $maProfesi[$jenis];
							
			}
			
			return $this->tenagamedis[$jenis]->load($params, $columns, $orders);
		}
		
		return array();
	}
}