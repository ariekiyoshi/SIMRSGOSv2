<?php
namespace BPJService\V1\Rpc\Referensi;

use DBService\RPCResource;

use Zend\View\Model\JsonModel;
use Zend\Json\Json;

class ReferensiController extends RPCResource
{
	private $service;
	
	public function __construct($service) 
	{
		$this->service = $service;
		$this->config = $this->service->getConfig();
	}
	
	public function poliAction() {
		$params = (array) $this->getRequest()->getQuery();
		if(count($params) > 0) {
			$data = $this->service->poli($params);
		} else {
			$data = $this->service->poli();
		}
		
		return new JsonModel(array(
            'data' => $data
        ));
	}
	
	public function diagnosaAction() {
		$params = (array) $this->getRequest()->getQuery();
		$data = $this->service->diagnosa($params);
		
		return new JsonModel(array(
            'data' => $data
        ));
	}
	
	public function procedureAction() {
		$params = (array) $this->getRequest()->getQuery();
		$data = $this->service->procedure($params);
		
		return new JsonModel(array(
            'data' => $data
        ));
	}
	
	public function kelasRawatAction() {
		$data = $this->service->kelasRawat();
		
		return new JsonModel(array(
            'data' => $data
        ));
	}
	
	public function dokterAction() {
		$params = (array) $this->getRequest()->getQuery();
		$data = $this->service->dokter($params);
		
		return new JsonModel(array(
            'data' => $data
        ));
	}
	
	public function spesialistikAction() {
		$data = $this->service->spesialistik();
		
		return new JsonModel(array(
            'data' => $data
        ));
	}
	
	public function ruangRawatAction() {
		$data = $this->service->ruangRawat();
		
		return new JsonModel(array(
            'data' => $data
        ));
	}
	
	public function caraKeluarAction() {
		$data = $this->service->caraKeluar();
		
		return new JsonModel(array(
            'data' => $data
        ));
	}
	
	public function pascaPulangAction() {
		$data = $this->service->pascaPulang();
		
		return new JsonModel(array(
            'data' => $data
        ));
	}
	
	public function faskesAction() {
		$params = (array) $this->getRequest()->getQuery();
		
		$data = $this->service->faskes($params);
		
		return new JsonModel(array(
            'data' => $data
        ));
	}
	
	public function dpjpAction() {
	    $params = (array) $this->getRequest()->getQuery();
	    
	    $data = $this->service->dpjp($params);
	    
	    return new JsonModel(array(
	        'data' => $data
	    ));
	}
	
	public function propinsiAction() {
	    $data = $this->service->propinsi();
	    
	    return new JsonModel(array(
	        'data' => $data
	    ));
	}
	
	public function kabupatenAction() {
	    $params = (array) $this->getRequest()->getQuery();
	    $data = $this->service->kabupaten($params);
	    
	    return new JsonModel(array(
	        'data' => $data
	    ));
	}
	
	public function kecamatanAction() {
	    $params = (array) $this->getRequest()->getQuery();
	    $data = $this->service->kecamatan($params);
	    
	    return new JsonModel(array(
	        'data' => $data
	    ));
	}
}
