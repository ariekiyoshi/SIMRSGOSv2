<?php
namespace Plugins\V2\Rpc\Dukcapil;

use DBService\RPCResource;
use Zend\Json\Json;
use ZF\ApiProblem\ApiProblem;

class DukcapilController extends RPCResource
{
    protected $authType = self::AUTH_TYPE_LOGIN;
    
    public function __construct($controller) {
        $this->config = $controller->get('Config');
        $this->config = $this->config['services']['SIMpelService'];
        $this->config = $this->config['plugins']['Dukcapil'];

        $this->jenisBridge = 5;
    }
   
    /* NIK
     * @method nik
     */
    public function nikAction() {
        $nik = $this->params()->fromRoute('id', 0);
        $result = $this->sendRequest("nik/".$nik);
        /*$result = $this->sendRequestData([
         "action" => "nik/".$nik,
         "header" => [
         "Accept: application/json"
         ]
         ]);*/
        
        return $this->getResultRequest($result);                  
    }
}
