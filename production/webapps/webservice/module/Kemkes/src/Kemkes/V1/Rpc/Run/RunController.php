<?php
namespace Kemkes\V1\Rpc\Run;

use DBService\RPCResource;
use Kemkes\V1\Rpc\Kunjungan\Service as KunjunganService;
use Kemkes\V1\Rest\BedMonitor\Service as BedMonitorService;
use Kemkes\V1\Rpc\Diagnosa\Service as DiagnosaService;
use Kemkes\V1\Rpc\Bor\Service as BorService;

class RunController extends RPCResource
{
	public function __construct($controller) {
		$this->config = $controller->get('Config');
		$this->config = $this->config['services']['KemkesService'];
		$this->headers = array(
			"X-rs-id:".$this->config["id"],
			"X-pass: ".md5($this->config["key"])
		);
	}
	
	public function kunjunganAction() {
		$kunjungan = new KunjunganService();
		$query = $this->getRequest()->getQuery();
		$id = $this->params()->fromRoute('id', 0);
		$data = array();
		if($id == "irj") $data = $kunjungan->getRJ($query);
		else if($id == "igd") $data = $kunjungan->getRD($query);
		else if($id == "iri") $data = $kunjungan->getRI($query);
		
		$data = $this->toFormatXML($data);
		
		$sysdate = $kunjungan->execute("SELECT DATE_FORMAT(NOW(), '%d-%m-%Y') TANGGAL");
		if(count($sysdate) > 0) $sysdate = $sysdate[0]["TANGGAL"];
		
		$tgl = "/".(isset($query["tanggal"]) ? $query["tanggal"] : $sysdate);
		
		//return array("data" => $data);
		$response = $this->sendRequest($id.$tgl, 'POST', $data, "application/xml");
		
		return array("respon" => isset($response) ? $response : "Ok");
	}
	
	public function bedMonitorAction() {
		$bedmon = new BedMonitorService();
		$query = $this->getRequest()->getQuery();
		$data = $bedmon->get($query);
		
		$data = $this->toFormatXML($data);
		
		$response = $this->sendRequest("ranap", 'POST', $data, "application/xml");
		
		return array("respon" => isset($response) ? $response : "Ok");
	}
	
	public function diagnosaAction() {
		$diagnosa = new DiagnosaService();
		$query = $this->getRequest()->getQuery();
		$id = $this->params()->fromRoute('id', 0);
		$data = array();
		if($id == "irj") $data = $diagnosa->getRJ($query);
		else if($id == "igd") $data = $diagnosa->getRD($query);
		else if($id == "iri") $data = $diagnosa->getRI($query);
		
		$data = $this->toFormatXML($data);
		
		$sysdate = $diagnosa->execute("SELECT DATE_FORMAT(NOW(), '%m-%Y') TANGGAL");
		if(count($sysdate) > 0) $sysdate = $sysdate[0]["TANGGAL"];
		
		$tgl = "/".(isset($query["bulan"]) ? $query["bulan"] : $sysdate);
		
		//return array("data" => $data);
		$response = $this->sendRequest("diagnosa_".$id.$tgl, 'POST', $data, "application/xml");
		
		return array("respon" => isset($response) ? $response : "Ok");
	}
		
	public function borAction() {
		$bor = new BorService();
		$query = $this->getRequest()->getQuery();
		$id = $this->params()->fromRoute('id', 0);
		$data = $bor->get($query);
		
		$data = $this->toFormatXML($data);
		
		$sysdate = $bor->execute("SELECT DATE_FORMAT(NOW(), '%m-%Y') TANGGAL");
		if(count($sysdate) > 0) $sysdate = $sysdate[0]["TANGGAL"];
		
		$tgl = "/".(isset($query["bulan"]) ? $query["bulan"] : $sysdate);
		
		//return array("data" => $data);
		$response = $this->sendRequest("bor".$tgl, 'POST', $data, "application/xml");
		
		return array("respon" => isset($response) ? $response : "Ok");
	}
}
