<?php
namespace Pusdatin\V1\Rpc\Adminduk\soap;

use \SoapClient;

class Service
{
	private $service;
	private $username;
	private $token;
	
	public function __construct($config) {
	    try {
	        $config = $config["soap"];
	        $this->username = $config["username"];
	        $this->token = $config["token"];
	        $this->service = new SoapClient($config["url"]);
	    } catch(\Exception $e) {
	        file_put_contents("logs/pusdatin.txt", $e->getMessage());
	    }
	}
	
	function getPenduduk($nik) {	
	    $this->nik = $nik;
		$response = $this->service->getPendudukAdminduk(array(
			"GetPendudukRequest" => $this
		));
		
		$response = $response->GetPendudukResponse;
		$result = [
		    "status" => 404,
		    "detail" => "Data Penduduk tidak ditemukan / tidak ada respon dari pusdatin",
		    "data" => null
		];
		if($response->metaData->code == 200) {
		    $result["status"] = 200;
		    $result["detail"] = "Data Penduduk ditemukan";
		    $result["data"] = (array) $response->penduduk->RESPON;
		}
		
		return $result;
	}
}
