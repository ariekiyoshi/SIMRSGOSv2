<?php
namespace INASIService\V1\Rpc\Peserta;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class PesertaController extends AbstractRestfulController
{	
	private $service;
	private $config;
	
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
					'requestId' => $this->config['koders'],
				)
			)
		));
    }
	
	public function get($id) 
	{
		$data = $this->service->cariPesertaDgnNoKartuBPJS($id);

		return new JsonModel(array(
            'data' => $data,
        ));
	}
	
	public function nikAction() 
	{
		$id = $this->params()->fromRoute('id', 0);
		$data = $this->service->cariPesertaDgnNIK($id);
		
		return new JsonModel(array(
            'data' => $data,
        ));
    }
}
