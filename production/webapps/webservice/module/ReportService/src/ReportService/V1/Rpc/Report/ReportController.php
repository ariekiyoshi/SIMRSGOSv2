<?php
namespace ReportService\V1\Rpc\Report;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;

use DBService\Crypto;

class ReportController extends AbstractActionController
{
	private $rs;
    
    public function __construct($rs) {
        $this->rs = $rs;
    }
	
    public function reportAction()
    {		
		/* get Request Report Parameters 
			$requestReport = array(
				'NAME' => 'pasien.Test',
				'CONNECTION_NUMBER' => 0,
				'TYPE' => 'Html',
				'EXT' => 'html',
				'PARAMETER'=>array(
					'NAMA' => 'Hariansyah',
					'PRINT_HEADER' => false
				),
				'REQUEST_FOR_PRINT' => false
			);
		*/
		
		$action = $this->getRequest()->getQuery('action');	
		
		if($action) {
			if($action == "generatekey") {
				return $this->generateKey32bit($this->getRequest()->getQuery());
			}
		}
		
		$var = $this->getRequest()->getQuery('requestReport');
		$result = $this->rs->generate($this->getResponse(), $var);
		return $result;
    }
	
	public function generateKey32bit($params) {
		$crypto = new Crypto();
		$key = $crypto->generateKey($params["pass"]);
		
		return array(
			"key" => $key
		);
	}
}
