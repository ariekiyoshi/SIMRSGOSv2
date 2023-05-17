<?php
namespace DBService;

use Zend\Mvc\Controller\AbstractActionController;
use SimpleXMLElement;

class ResponseFormat extends AbstractActionController
{    
	public function toResponse($data = array()) {
		if($this->request->getHeaders()->get("accept")->getFieldValue() == "application/json") return $this->toJson($data);
		return $this->toXML($data);
	}
	
	public function toJson($data = array()) {
		$json = json_encode($data);
		//$json = str_replace(array("[", "]"), "", $json);
		$this->response->setContent($json);
		$headers = $this->response->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'application/json')
			->addHeaderLine('Content-Length', strlen($json));
        
		return  $this->response;
	}
	
	public function toXML($data = array()) {
		$doc = new SimpleXMLElement("<xml version='1.0'></xml>");
		foreach($data as $entity) {
			$dt = $doc->addChild("data");
			foreach($entity as $key => $val) {
				$dt->addChild($key, htmlspecialchars($val));
			}
		}
		$xmlString = $doc->asXML();
		$xmlString = str_replace(array('<?xml version="1.0"?>', "\n", "\r"), "", $xmlString);
		$this->response->setContent($xmlString);
		$headers = $this->response->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'application/xml')
			->addHeaderLine('Content-Length', strlen($xmlString));
        
		return  $this->response;
	}
	
	protected function getXMLPostData() {
		if(!isset($this->request)) {
			return new ApiProblem(500, 'Error Script: request is null');
		}
						
        return new SimpleXMLElement($this->request->getContent());
	}
}
