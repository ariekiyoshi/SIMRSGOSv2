<?php
namespace DBService;

use Zend\Mvc\Controller\AbstractRestfulController;

use SimpleXMLElement;
use Zend\Json\Json;

use Zend\Http\Request as HttpRequest;
use Zend\Mvc\Exception;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;

use Zend\Authentication\AuthenticationService;
use DBService\DatabaseService;
use Aplikasi\Signature;

use Aplikasi\db\bridge_log\Service as BridgeLogService;
use DBService\generator\Generator;

use ZF\ApiProblem\ApiProblem;
use Zend\View\Model\JsonModel;

class RPCResource extends AbstractRestfulController
{
	const AUTH_TYPE_LOGIN = 0;
	const AUTH_TYPE_SIGNATURE = 1;
	const AUTH_TYPE_NOT_SECURE = 2;
	const AUTH_TYPE_SIGNATURE_OR_LOGIN = 3;
	const AUTH_TYPE_IP = 4;
	const AUTH_TYPE_IP_OR_LOGIN = 5;
	const AUTH_TYPE_IP_AND_LOGIN = 6;
	const AUTH_TYPE_SIGNATURE_OR_IP = 7;
	const AUTH_TYPE_SIGNATURE_AND_IP = 8;
	
	protected $auth;
	protected $services;
	protected $privilages = array();
	protected $user;
	protected $dataAkses;
	protected $bridgeLog;
	protected $jenisBridge = 0;
	protected $writeBridgeLog = true;
	
	private $signature = false;
	
	protected $authType = self::AUTH_TYPE_IP;
	
	protected $config = array();
	protected $headers = array();
	
	protected $integrasi = array();
	
	public function getSignature() {
		return $this->signature;
	}
	
    protected function sendRequest($action = "", $method = "GET", $data = "", $contenType = "application/json", $url = "") {
		$curl = curl_init();
				
		$url = ($url == '' ? $this->config["url"] : $url);

		$id = $this->writeBridgeLog([
			"URL" => $url."/".$action,
			"REQUEST" => $data,
			"ACCESS_FROM_IP" => $_SERVER['REMOTE_ADDR']
		]);

		curl_setopt($curl, CURLOPT_URL, $url."/".$action);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$this->headers[count($this->headers)] = "Content-type: ".$contenType;
		$this->headers[count($this->headers)] = "Content-length: ".strlen($data);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
		
		$result = curl_exec($curl);
		curl_close($curl);

		$this->writeBridgeLog([
			"ID" => $id,
			"RESPONSE" => $result
		]);

		file_put_contents("logs/log.txt", $result);
		if($data !== "") file_put_contents("logs/post_data_log.txt", $data);
		file_put_contents("logs/url.txt", $url."/".$action);
		file_put_contents("logs/headers.txt", json_encode($this->headers));
		
		return $result;
	}
	
	/*
	 * array(
	 *		url => "Uniform Resource Locator",
	 *		action => "Web Service Name",
	 *		method => "{GET|POST|PUT|DELETE}",
	 *		data => "JSON String",
	 *		header => []
	 * )
	 */
	protected function sendRequestData($options = []) {
	    $url = isset($options["url"]) ? $options["url"] : $this->config["url"];
	    $protocol = explode("://", $url);
	    $protocol = $protocol[0];
	    $action = isset($options["action"]) ? (trim($options["action"]) != "" ? "/".$options["action"] : "") : "";
	    $headers = array(
	        "Content-type: application/json"
	    );
	    
	    $data = isset($options["data"]) ? json_encode($options["data"]) : "";
	    
	    $headers[] = "Content-length: ".strlen($data);
	    
	    if(isset($options["header"])) $headers = array_merge($headers, $options["header"]);
	    if(isset($this->headers)) $headers = array_merge($headers, $this->headers);
	    
	    $opts = [
	        $protocol => [
	            "method" => isset($options["method"]) ? $options["method"] : "GET",
	            "header" => implode("\r\n", $headers),
	            "content" => $data //,
	            //"timeout" => 60
	        ]
	    ];
	    
	    if($data !== "") file_put_contents("logs/post_data_log.txt", $data);
	    file_put_contents("logs/url.txt", $url.$action);
		file_put_contents("logs/headers.txt", json_encode($opts));
		$id = $this->writeBridgeLog([
			"URL" => $url.$action,
			"REQUEST" => $data,
			"ACCESS_FROM_IP" => $_SERVER['REMOTE_ADDR']
		]);
	    try {
	        $context  = stream_context_create($opts);
			$result = @file_get_contents($url.$action, false, $context);
			$this->writeBridgeLog([
				"ID" => $id,
				"RESPONSE" => $result
			]);
	    } catch(\Exception $e) {
	        //var_dump($e->getMessage());
			$result = null;
			$this->writeBridgeLog([
				"ID" => $id,
				"RESPONSE" => $e->getMessage()
			]);
	    }
	    //$result = file_get_contents($url."/".$action, false, $context, -1, 40000);
	    file_put_contents("logs/log.txt", $result);
	    return $result;
	}
	
	protected function getPostData() {
		$request = $this->getRequest();
		if ($this->requestHasContentType($request, self::CONTENT_TYPE_JSON)) {
			$data = Json::decode($request->getContent(), $this->jsonDecodeType);
        } else {
            $data = $request->getPost()->toArray();
        }
		
		return $data;
	}

	protected function writeBridgeLog($data=[]) {
		if(!$this->writeBridgeLog) return false;
		if(!isset($this->bridgeLog)) $this->bridgeLog = new BridgeLogService();
		$isCreate = isset($data["ID"]) ? false : true;
		if($isCreate) $data["ID"] = Generator::generateIdBridgeLog();
		$data["JENIS"] = $this->jenisBridge;
		$this->bridgeLog->simpanData($data, $isCreate);
		return $data["ID"];
	}

	public function setIsWriteBridgeLog($val = true) {
		$this->writeBridgeLog = $val;
	}
	
	/**
     * Handle the request
     *
     * @todo   try-catch in "patch" for patchList should be removed in the future
     * @param  MvcEvent $e
     * @return mixed
     * @throws Exception\DomainException if no route matches in event or invalid HTTP method
     */
	public function onDispatch(MvcEvent $e) {
		if(!isset($this->auth)) $this->auth = new AuthenticationService();	
		$result = null;
		if($this->authType == self::AUTH_TYPE_LOGIN) {
			$result = $this->doAuthLogin();			
			if(is_array($result)) {
				$this->response->setStatusCode(405);
				$e->setResult($result);
				return $result;
			}
		} else if($this->authType == self::AUTH_TYPE_SIGNATURE) {
			$result = $this->doAuthSignature();
		} else if($this->authType == self::AUTH_TYPE_SIGNATURE_OR_LOGIN) {
			$result = $this->doAuthLogin();
			if(is_array($result)) {
				$result = $this->doAuthSignature();
			}
		} else if($this->authType == self::AUTH_TYPE_IP) {
			$result = $this->doAuthIP();
		} else if($this->authType == self::AUTH_TYPE_IP_OR_LOGIN) {
		    $result = $this->doAuthIP();
		    if($result instanceof ApiProblem) {
		        $result = $this->doAuthLogin();
		        if(is_array($result)) {
		            $this->response->setStatusCode(405);
		            $e->setResult($result);
		            return $result;
		        }
		    }
		} else if($this->authType == self::AUTH_TYPE_IP_AND_LOGIN) {
		    $result = $this->doAuthIP();
		    if(!($result instanceof ApiProblem)) {
		        $result = $this->doAuthLogin();
		        if(is_array($result)) {
		            $this->response->setStatusCode(405);
		            $e->setResult($result);
		            return $result;
		        }
		    }
		} else if($this->authType == self::AUTH_TYPE_SIGNATURE_OR_IP) {
		    $headers = $this->request->getHeaders();
		    if($headers->get("X-Signature")) {
		        $result = $this->doAuthSignature();
		    } else {
		        $result = $this->doAuthIP();
		    }
		} else if($this->authType == self::AUTH_TYPE_SIGNATURE_AND_IP) {
		    $result = $this->doAuthSignature();
		    if(!($result instanceof ApiProblem)) {
		        $result = $this->doAuthIP();
		    }
		}
		
		if($result instanceof ApiProblem) {
			$errors = $result->toArray();	
			$this->response->setStatusCode($errors["status"]);
			$e->setResult($errors);
			return $errors;
		}
		
		return parent::onDispatch($e);
	}
	
	private function doAuthLogin() {
		if(!$this->auth->hasIdentity()) {
			return array(
				'success' => false,
				'message' => 'not login',
				'data' => null
			);
		} else {
			$data = $this->auth->getIdentity();
			$this->privilages = (array) $data->XPRIV;
			if(isset($data->XITR)) $this->integrasi = $data->XITR;
			$this->user = $data->ID;
			$this->dataAkses = $data;
		}
	}
	
	private function doAuthSignature() {
		if(!isset($this->request)) {
			return new ApiProblem(500, 'Error Script: request is null');
		}
		
		$headers = $this->request->getHeaders();					
		$this->signature = new Signature(
			$headers->get("X-Id"), 
			$headers->get("X-Timestamp"),
			$headers->get("X-Signature")
		);
		
		try {
			$this->signature->isValidSignature();
		} catch(\Exception $e) {
			return new ApiProblem($e->getCode(), $e->getMessage());
		}
	}
	
	private function doAuthIP() {
		if(!isset($this->request)) {
			return new ApiProblem(500, 'Error Script: request is null');
		}
		
		$ip = $this->request->getServer('REMOTE_ADDR');
		
		$db = DatabaseService::get("SIMpel");
		$adapter = $db->getAdapter();
		
		$stmt = $adapter->query('
			SELECT *
			  FROM aplikasi.allow_ip_authentication
			 WHERE NOMOR = ?');
		$results = $stmt->execute(array($ip));
		$result = $results->current();
		$allow = false;
		if($result) {
			$allow = $result["STATUS"] == 1;
		}
		
		if(!$allow) {
			return new ApiProblem(401, "IP $ip is not allowed / registered");
		}
	}
	
	/*
		Examples:
		array(
			'123' => '123'
		);
	*/
	public function isAllowPrivilage($id) {
		$allow = false;
		foreach($this->privilages as $key=>$val) {
			if($key == $id) {
				$allow = true;
				break;
			}
		}
		return $allow;
	}
	
	/*
		Examples:
		array(
			'0' => array(
				'ID' => '1'
			)
		);
	*/
	public function isIntegrasi($field, $val) {
		$allow = false;
		foreach($this->integrasi as $data) {
			$data = (array) $data;
			if($data[$field] == $val) {
				$allow = true;
				break;
			}
		}
		return $allow;
	}
	
	public function toResponse($data = array()) {
		if($this->getRequest()->getHeaders()->get("accept")->getFieldValue() == "application/json") return $this->toJsonResponse($data);
		return $this->toXMLResponse($data);
	}
	
	public function toJsonResponse($data = array()) {
		$json = json_encode($data);
		//$json = str_replace(array("[", "]"), "", $json);
		$this->getResponse()->setContent($json);
		$headers = $this->getResponse()->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'application/json')
			->addHeaderLine('Content-Length', strlen($json));
        
		return  $this->getResponse();
	}
	
	public function toXMLResponse($data = array()) {	
		$xmlString = $this->toFormatXML($data);
		$this->getResponse()->setContent($xmlString);
		$headers = $this->getResponse()->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'application/xml')
			->addHeaderLine('Content-Length', strlen($xmlString));
        
		return  $this->getResponse();
	}
	
	public function toFormatXML($data = array()) {
		$doc = new SimpleXMLElement("<xml version='1.0'></xml>");
		foreach($data as $entity) {
			$dt = $doc->addChild("data");
			foreach($entity as $key => $val) {
				$dt->addChild($key, htmlspecialchars($val));
			}
		}
		$xmlString = $doc->asXML();
		$xmlString = str_replace(array('<?xml version="1.0"?>', "\n", "\r"), "", $xmlString);
		
		return $xmlString;
	}
	
	protected function getXMLPostData() {					
        return new SimpleXMLElement($this->getRequest()->getContent());
	}
	
	protected function getResultRequest($val, $field = "status") {
	    try {
	        $result = Json::decode($val, Json::TYPE_ARRAY);
	        $status = isset($result[$field]) ? (is_numeric($result[$field]) ? $result[$field] : 200) : 200;
	    } catch(\Exception $e) {
	        $status = 404;
	        $result = [
	            "status" => 404,
	            "detail" => "Page not found",
	            "data" => null
	        ];
	    }
	    
	    if($this->response) $this->response->setStatusCode($status);
	    
	    return $result;
	}
}
