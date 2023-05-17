<?php
namespace Plugins\V2\Rpc\Inacbg;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Json\Json;
use ZF\ApiProblem\ApiProblem;

class InacbgController extends AbstractRestfulController
{
	private $config;
	
	public function __construct($controller) {
	    $this->config = $controller->get('Config');
		$this->config = $this->config['services']['SIMpelService'];
		$this->config = $this->config['plugins']['INACBG'];
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
				if(isset($data->metaData)) {
					if($data->metaData->code != 200) {
						$api = new ApiProblem(412, $data->metaData->message.'('.$data->metaData->code.')');
						$data = $api->toArray();
					}
				} else if(isset($data->metadata)) {
				    if($data->metadata->code != 200) {
				        $api = new ApiProblem(412, $data->metadata->message.'('.$data->metadata->code.')');
				        $data = $api->toArray();
				    }
				} else {
				    if(isset($data->status)) {
    				    if($data->status != 0){
        					$api = new ApiProblem(412, $data->cbg_code.'-'.$data->status);
        					$data = $api->toArray();
    				    }
				    }
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
		return $api ? $data : (array) $data;
	}
	
    public function getList()
    {
		return $this->validateRequest($this->sendRequest('grouper'));
    }
	
	/* Grouper */
	public function create($data)
    {        
		$data = (array) $this->validateRequest($this->sendRequest('grouper', 'POST', Json::encode($data)));		
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	/* kirimKlaimIndividual */
	public function kirimKlaimIndividualAction()
    {       
		$data = $this->getPostData($this->getRequest());
		$data = (array) $this->validateRequest($this->sendRequest('grouper/kirimKlaimIndividual', 'POST', Json::encode($data)));		
		
		if(isset($data['status'])) return $data;		
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	/* kirimKlaimIndividual */
	public function kirimKlaimAction()
    {       
		$data = $this->getPostData($this->getRequest());
		$data = (array) $this->validateRequest($this->sendRequest('grouper/kirimKlaim', 'POST', Json::encode($data)));		
		
		if(isset($data['status'])) return $data;		
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	/* batalKlaim */
	public function batalKlaimAction()
    {       
		$data = $this->getPostData($this->getRequest());
		$data = (array) $this->validateRequest($this->sendRequest('grouper/batalKlaim', 'POST', Json::encode($data)));		
		
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
