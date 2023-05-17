<?php
namespace General\V1\Rest\TempatLahir;

use General\V1\Rest\Negara\NegaraService;
use General\V1\Rest\Wilayah\WilayahService;
use DBService\Service;

class TempatLahirService
{    
	private $negara;
	private $wilayah;
    
    public function __construct() {
        $this->wilayah = new WilayahService(); 
		$this->negara = new NegaraService();
    }
  	
	public function load($params = array()) {
		$params = is_array($params) ? $params : (array) $params;
		/* Penggabungan antara wilayah khusus kabupaten dan negara*/
		$kabs = $this->wilayah->load(array_merge(array('JENIS' => 2), $params), array('ID', 'DESKRIPSI'));		
		$negaras = $this->negara->load(array_merge(array(), $params), array('ID', 'DESKRIPSI'));
		
		$tempatlahir = array_merge($kabs, $negaras);
		return array(
			'total'=>count($tempatlahir),
			'data'=>$tempatlahir
		);
    }
}
