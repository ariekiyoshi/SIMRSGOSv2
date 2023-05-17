<?php
namespace Plugins\V2\Rpc\Inasis;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Json\Json;
use ZF\ApiProblem\ApiProblem;

class InasisController extends AbstractRestfulController
{
	private $config;
	
	public function __construct($controller) {
	    $this->config = $controller->get('Config');
		$this->config = $this->config['services']['SIMpelService'];
		$this->config = $this->config['plugins']['INASIS'];
	}
	
	private function sendRequest($action = "", $method = "GET", $data = "", $contenType = "application/json", $url = "") {
		$curl = curl_init();
				
		$url = ($url == '' ? $this->config["url"] : $url);
		$headers = array();
		curl_setopt($curl, CURLOPT_URL, $url."/".$action);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$headers[count($headers)] = "Content-type: ".$contenType;
		$headers[count($headers)] = "Content-length: ".strlen($data);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		
		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;
	}
	
	private function validateRequest($result) {
		try {
			$result = (array) Json::decode($result);
			$data = $result['data'];
			$api = null;
			
			if($data) {
				if($data->metadata) {
					if($data->metadata->code == 200 && (trim($data->metadata->message) == '200' || trim($data->metadata->message) == 'OK')) {
						$data = (array) $data->response;
					} else {
						$api = new ApiProblem(412, $data->metadata->message.'('.$data->metadata->code.')');
						$data = $api->toArray();
					}
				} else {
					$api = new ApiProblem(503, 'Metadata is null');
					$data = $api->toArray();
				}
			} else { // if data is null
				$api = new ApiProblem(503, 'Data is null');
				$data = $api->toArray();
			}
			
			if($api) $this->response->setStatusCode($api->__get('status'));
		} catch(\Exception $e) {
			$api = new ApiProblem(500, $result);
			$data = $api->toArray();
			$this->response->setStatusCode($api->__get('status'));
		}
		
		return $data;
	}
	
    public function getList()
    {
		return $this->validateRequest($this->sendRequest('peserta'));
    }
	
	/* Kepesertaan */
	/* cari peserta */
	public function get($id) 
	{
		$data = $this->validateRequest($this->sendRequest('peserta/'.$id));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data['peserta']
		);
	}
	
	public function nikAction() 
	{
		$id = $this->params()->fromRoute('id', 0);
		$data = $this->validateRequest($this->sendRequest('peserta/nik/'.$id));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	/* Kunjungan */
	/* buat SEP */
	public function create($data)
    {        
		$data = $this->validateRequest($this->sendRequest('kunjungan', 'POST', Json::encode($data)));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function mappingDataTransaksiAction()
    {       
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('kunjungan/mappingDataTransaksi', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function updateTanggalPulangAction()
    {       
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('kunjungan/updateTanggalPulang', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function cekSEPAction()
    {    
		$id = $this->params()->fromRoute('id', 0);
		$data = Json::encode(array("noSEP"=>$id));
		$data = $this->validateRequest($this->sendRequest('kunjungan/cekSEP', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function batalkanSEPAction()
    {       
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('kunjungan/batalkanSEP', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
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
