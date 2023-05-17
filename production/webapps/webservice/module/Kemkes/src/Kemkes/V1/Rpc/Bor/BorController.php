<?php
namespace Kemkes\V1\Rpc\Bor;

use DBService\ResponseFormat;

class BorController extends ResponseFormat
{
    private $service;
	
	public function __construct() {
		$this->service = new Service();
	}
	
	public function borAction()
    {
		$request = $this->getRequest();
		return $this->toResponse($this->service->get($request->getQuery()));
    }
}
