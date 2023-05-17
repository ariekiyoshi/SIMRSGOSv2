<?php
namespace Pusdatin\V1\Rpc\Adminduk\rest;

use DBService\RPCResource;

class Service extends RPCResource
{
	private $url;
	private $username;
	private $token;
	
	public function __construct($config) {
	    $config = $config["rest"];
        $this->url = $config["url"];
        $this->username = $config["username"];
		$this->token = $config["token"];
		
		$this->jenisBridge = 4;
	}
	
	function getPenduduk($nik) {	    
	    $response = $this->sendRequestData([
	        "url" => $this->url,
	        "action" => "get_nik",
	        "method" => "GET",
	        "header" => [
	            "token: ".$this->token,
	            "nik: ".$nik
	        ]
	    ]);
	    $response = $this->getResultRequest($response, "GetPendudukResponse");	    
	    $response = isset($response) ? $response["GetPendudukResponse"] : null;
	    $result = [
	        "status" => 404,
	        "detail" => "Data Penduduk tidak ditemukan / tidak ada respon dari pusdatin",
	        "data" => null
	    ];
	    if(isset($response)) {
	        $result = [
	            "status" => 200,
	            "detail" => "Data Penduduk ditemukan",
	            "data" => $response["penduduk"]
	        ];
	        
	        if(isset($response["penduduk"]["RESPON"])) {
	            $result["status"] = 404;
	            $result["detail"] = $response["penduduk"]["RESPON"];
	            $result["data"] = null;
	        }
	    }
	    
	    file_put_contents("logs/pusdatin-rest.txt", json_encode($response));
	    
	    
	    return $result;
	}
}
