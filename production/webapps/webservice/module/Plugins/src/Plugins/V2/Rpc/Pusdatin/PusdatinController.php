<?php
namespace Plugins\V2\Rpc\Pusdatin;

use DBService\RPCResource;
use Zend\Json\Json;
use ZF\ApiProblem\ApiProblem;

class PusdatinController extends RPCResource
{
    protected $authType = self::AUTH_TYPE_LOGIN;
    
    public function __construct($controller) {
        $this->config = $controller->get('Config');
        $this->config = $this->config['services']['SIMpelService'];
        $this->config = $this->config['plugins']['Pusdatin'];

        $this->jenisBridge = 4;
    }
    
    /* NIK
     * @method nik
     */
    public function nikAction() {
        $nik = $this->params()->fromRoute('id', 0);
        $result = $this->sendRequest("getPenduduk/".$nik);
        return $this->getResultRequest($result);
    }
}
