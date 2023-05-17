<?php
namespace TTS\V1\Rpc\TTService;

use DBService\RPCResource;
use Zend\Json\Json;
use ZF\ApiProblem\ApiProblem;
use General\V1\Rest\Audios\AudiosService;

class TTServiceController extends RPCResource
{
    private $service;
    
    public function __construct($controller) {
        $this->config = $controller->get('Config');
        $this->config = $this->config['services']['TTS'];
        
        $this->service = new AudiosService();
    }
    
    public function getList() {
        $params = (array) $this->getRequest()->getQuery();
        
        if(count($params) > 0) {
            $result = "";
            if(isset($params["ty"])) {
                if($params["ty"] == "json") {
                    unset($params["ty"]);
                    $txts = (array) json_decode($params["q"]);
                    foreach($txts as $tx) {
                        $params["q"] = $tx;                    
                        $req = $this->getAudio($params);
                        $result .= $req;                        
                    }
                }
            }
            if($result == "") {
                $result = $this->getAudio($params);
            }
                  
            $this->response->setContent($result);
            $headers = $this->response->getHeaders();
            $headers->clearHeaders()
            ->addHeaderLine('Content-Type', 'audio/mpeg')
            ->addHeaderLine('Content-Length', strlen($result));
            
            return  $this->response;
            
            return [];
        } else {
            $result = [
                "status" => 204,
                "detail" => "No Content",
                "data" => null
            ];
            $this->response->setStatusCode(204);
            
            return $result;
        }
    }
    
    private function getAudio($params) {
        $this->config["params"]["q"] = isset($params["q"]) ? $params["q"] : "Tidak ada text";
        $this->config["params"]["textlen"] = strlen($params["q"]);
        $url = $this->config["url"]."?".http_build_query($this->config["params"]);
        $data = [
            "JENIS" => 1,
            "TEKS" => $this->config["params"]["q"]
        ];
        $finds = $this->service->load($data);
        if(count($finds) == 0) {
            $result = $this->sendRequestData([
                "url" => $url
            ]);
            $data["AUDIO"] = $result;
            $this->service->simpanData($data, true);
        } else {
            $result = $finds[0]["AUDIO"];
        }
        
        return $result;
    }
}
