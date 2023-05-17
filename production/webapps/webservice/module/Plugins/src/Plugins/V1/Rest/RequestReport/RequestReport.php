<?php
namespace Plugins\V1\Rest\RequestReport;

use Zend\Crypt\BlockCipher;
use Zend\Crypt\Symmetric\Mcrypt;

class RequestReport
{
	private $crypt;
	private $config;
	
	public function __construct($config) {
		$this->crypt = new BlockCipher(new Mcrypt(array(
			'algo' => 'aes',
			'mode' => 'cfb',
			'hash' => 'sha512'
		)));
		
		$this->config = $config['services']['SIMpelService'];
		$key = $this->config['plugins']['ReportService']['key'];
		
		$this->crypt->setKey($key);
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
		$secret = base64_encode($this->crypt->encrypt($content));
		
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
		$remoteAddr = substr($_SERVER["REMOTE_ADDR"], 0, 1) == ':' ? '127.0.0.1' : $_SERVER["REMOTE_ADDR"];
		$client = explode(".", $remoteAddr);
        $port = $_SERVER["SERVER_PORT"] == "80" ? "" : ":".$_SERVER["SERVER_PORT"];
        $client = $route[$client[0].".".$client[1].".".$client[2]].($port == ":443" ? "" : $port);
		
		$host = str_replace('[HOST]', $client, $host);
		if($port == ":443") $host = str_replace('http', 'https', $host);
		
        return $host;
	}
}