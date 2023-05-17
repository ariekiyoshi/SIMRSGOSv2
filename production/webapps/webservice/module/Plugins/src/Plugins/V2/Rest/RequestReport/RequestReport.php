<?php
namespace Plugins\V2\Rest\RequestReport;

use DBService\Crypto;

class RequestReport
{
    private $crypt;
    private $config;
    
    public function __construct($config) {
        $this->config = $config['services']['SIMpelService'];
        
        $key = $this->config['plugins']['ReportService']['key'];
        
        $this->crypt = new Crypto(array(
            "key" => $key
        ));
    }
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $printName = isset($data->PRINT_NAME) ? $data->PRINT_NAME : "";
        $copies = isset($data->COPIES) ? $data->COPIES : 1;
        unset($data->PRINT_NAME);
        unset($data->COPIES);
        unset($data->id);
        
        $content = json_encode($data);
        $encrypt = $this->crypt->encrypt($content);
        $secret = base64_encode($encrypt);
        
        $url = $this->getUrl().$secret;
        $id = $this->config['instansi']['id'];
        
        if($data->REQUEST_FOR_PRINT) {
            $result = array(
                /* Id Instansi#PrinterName#PrinterOfCopies#DocumentType#DocumentURL#MethodRequest# */
                'content'=>base64_encode($id.'#'.$printName.'#'.$copies.'#'.$data->EXT.'#'.$url.'#GET#')
            );
        } else {
            $result = array(
                'url' => $url
            );
        }
        
        return $result;
    }
    
    private function getUrl() {
        $route = $this->config['plugins']['ReportService']['route'];
        $host = $this->config['plugins']['ReportService']['url'];
        $httpHost = explode(":", $_SERVER["HTTP_HOST"]);
        $remoteAddr = (substr($httpHost[0], 0, 1) == ':' || $httpHost[0] == "localhost") ? '127.0.0.1' : $httpHost[0];
        $client = explode(".", $remoteAddr);
        $port = count($httpHost) > 1 ? ":".$httpHost[1] : ($_SERVER["SERVER_PORT"] == "80" ? "" : ":".$_SERVER["SERVER_PORT"]);
        $client = $route[$client[0].".".$client[1].".".$client[2]].($port == ":443" ? "" : $port);
        
        $host = str_replace('[HOST]', $client, $host);
        if($port == ":443") $host = str_replace('http', 'https', $host);
        
        return $host;
    }
}