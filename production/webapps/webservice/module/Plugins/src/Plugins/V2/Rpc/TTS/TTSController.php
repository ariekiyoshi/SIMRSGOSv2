<?php
namespace Plugins\V2\Rpc\TTS;

use DBService\RPCResource;
use Zend\Json\Json;
use ZF\ApiProblem\ApiProblem;

class TTSController extends RPCResource
{
    protected $authType = self::AUTH_TYPE_LOGIN;
    
    public function __construct($controller) {
        $this->config = $controller->get('Config');
        $this->config = $this->config['services']['SIMpelService']['plugins']['TTS'];        
    }
    
    public function getList() {
        $params = (array) $this->getRequest()->getQuery();
        
        $url = $this->config["url"]."?".http_build_query($params);        
        $result = $this->sendRequestData([
            "url" => $url
        ]);
        $this->response->setContent($result);
        $headers = $this->response->getHeaders();
        $headers->clearHeaders()
        ->addHeaderLine('Content-Type', 'audio/mpeg')
        ->addHeaderLine('Content-Length', strlen($result));
        
        return  $this->response;
    }
}
