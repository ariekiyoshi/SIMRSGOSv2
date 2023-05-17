<?php
namespace LISService\V1\Rpc\Service;

use Zend\Mvc\Controller\AbstractActionController;
use LISService\lis\Transport;

class ServiceController extends AbstractActionController
{
	private $transport;
	
    public function runAction()
    {
		$result = array();
		$this->transport = new Transport($this->params()->fromQuery("driverName"));
		$this->transport->order();
		$result["Order"] = "Done";
		$this->transport->getResult();
		$result["Get Result"] = "Done";
		/*$class = $this->params()->fromQuery("driverName");
		
		$obj = new $class();
		
		$result = $obj->getResult();*/
		
		return $result;
    }
}
