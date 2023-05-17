<?php
namespace INASIService\V1\Rpc\Kunjungan;

use Zend\Mvc\Controller\AbstractRestfulController;

use Zend\View\Model\JsonModel;
use Zend\Json\Json;

class KunjunganController extends AbstractRestfulController
{
	private $service;
	private $config;
	
	public function __construct($service) 
	{
		$this->service = $service;
		$this->config = $this->service->getConfig();
	}

	public function getList()
    {
		$this->response->setStatusCode(405); 
		return new JsonModel(array(
			'data' => array(
				'metadata' => array(
					'message' => 'Method Not Allowed',
					'code' => 405,
					'requestId' => $this->config['koders'],
				)
			)
		));
    }
	
	public function create($data)
    {        
		$data = $this->service->generateNoSEP($data["noKartu"], $data["tglSep"], $data["tglRujukan"], $data["noRujukan"], $data["ppkRujukan"], 
			$data["jnsPelayanan"], $data["catatan"], $data["diagAwal"], $data["poliTujuan"], $data["user"], $data["norm"], true, $_SERVER["REMOTE_ADDR"], $data["lakaLantas"]);
		
		return new JsonModel(array(
            'data' => $data,
        ));
    }
	
	public function mappingDataTransaksiAction()
    {       
		$data = $this->getPostData($this->getRequest());
		$data = $this->service->mappingDataTransaksi($data["noSEP"], $data["noTrans"]);
		
		return new JsonModel(array(
            'data' => $data,
        ));
    }
	
	public function updateTanggalPulangAction()
    {        
		$data = $this->getPostData($this->getRequest());
		$data = $this->service->updateTanggalPulang($data["noSEP"], $data["tglPlg"]);
		
		return new JsonModel(array(
            'data' => $data,
        ));
    }
	
	public function cekSEPAction()
    {        
		$data = $this->getPostData($this->getRequest());
		$data = $this->service->cekSEP($data["noSEP"]);
		
		return new JsonModel(array(
            'data' => $data,
        ));
    }
	
	public function batalkanSEPAction()
    {       
		$data = $this->getPostData($this->getRequest());
		$data = $this->service->batalkanSEP($data["noSEP"]);
		
		return new JsonModel(array(
            'data' => $data,
        ));
    }
	
	private function getPostData($request) {
		if ($this->requestHasContentType($request, self::CONTENT_TYPE_JSON)) {
			$data = Json::decode($request->getContent(), $this->jsonDecodeType);
        } else {
            $data = $request->getPost()->toArray();
        }
		
		return $data;
	}
}
