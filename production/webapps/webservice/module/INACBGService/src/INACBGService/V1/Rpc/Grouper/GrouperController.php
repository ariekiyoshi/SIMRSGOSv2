<?php
namespace INACBGService\V1\Rpc\Grouper;

use Zend\Mvc\Controller\AbstractRestfulController;

use Zend\View\Model\JsonModel;
use Zend\Json\Json;

class GrouperController extends AbstractRestfulController
{
    private $service;
	
	public function __construct($service) 
	{
		$this->service = $service;
	}

	public function getList()
    {
		$this->response->setStatusCode(405); 
		return new JsonModel(array(
			'data' => array(
				'metaData' => array(
					'message' => 'Method Not Allowed',
					'code' => 405,
					'requestId' => $this->service->getKodeRS(),
				)
			)
		));
    }
	
	public function create($data)
    {        
		$data = (array) $this->service->grouper($data);
		
		return new JsonModel(array(
            'data' => $data,
        ));
    }
	
	public function kirimKlaimIndividualAction()
    {        
		$data = $this->getPostData($this->getRequest());	
		$data = (array) $this->service->kirimKlaimIndividual($data);
		return new JsonModel(array(
            'data' => $data,
        ));
    }
	
	public function kirimKlaimAction()
    {        
		$data = $this->getPostData($this->getRequest());	
		$data = (array) $this->service->kirimKlaim($data);
		return new JsonModel(array(
            'data' => $data,
        ));
    }
	
	public function batalKlaimAction()
    {
		$data = $this->getPostData($this->getRequest());
		$data = (array) $this->service->batalKlaim($data);
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
