<?php
namespace Kemkes\V1\Rpc\Kunjungan;

//use ZF\ContentNegotiation\JsonModel;
use DBService\ResponseFormat;

class KunjunganController extends ResponseFormat
{
	private $service;
	
	public function __construct() {
		$this->service = new Service();
	}
	
    public function irjAction()
    {	
		$request = $this->getRequest();
		//return new JsonModel($this->service->getKunjunganRJ());
		return $this->toResponse($this->service->getRJ($request->getQuery()));
    }
	
	public function igdAction()
    {
		$request = $this->getRequest();
		return $this->toResponse($this->service->getRD($request->getQuery()));
    }
	
	public function iriAction()
    {
		$request = $this->getRequest();
		return $this->toResponse($this->service->getRI($request->getQuery()));
    }
}
