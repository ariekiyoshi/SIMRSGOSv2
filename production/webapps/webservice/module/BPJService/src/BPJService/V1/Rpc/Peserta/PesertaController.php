<?php
namespace BPJService\V1\Rpc\Peserta;

use DBService\RPCResource;
use Zend\View\Model\JsonModel;

class PesertaController extends RPCResource
{	
	private $service;
	
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
					'requestId' => $this->config['koders']
				)
			)
		));
    }
	
	public function get($id) 
	{
		$params = (array) $this->getRequest()->getQuery();
		$params["noKartu"] = $id;
		$data = $this->service->cariPesertaDgnNoKartuBPJS($params);

		return new JsonModel(array(
            'data' => $data
        ));
	}
	
	public function nikAction() 
	{
		$params = (array) $this->getRequest()->getQuery();
		$params["nik"] = $this->params()->fromRoute('id', 0);
		$data = $this->service->cariPesertaDgnNIK($params);
		
		return new JsonModel(array(
            'data' => $data
        ));
    }
}
