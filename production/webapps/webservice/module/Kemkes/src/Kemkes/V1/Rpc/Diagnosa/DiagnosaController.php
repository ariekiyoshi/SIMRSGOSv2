<?php
namespace Kemkes\V1\Rpc\Diagnosa;

use DBService\ResponseFormat;

class DiagnosaController extends ResponseFormat
{
    private $service;
	
	public function __construct() {
		$this->service = new Service();
	}
	
	public function irjAction()
    {	
		$request = $this->getRequest();
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
