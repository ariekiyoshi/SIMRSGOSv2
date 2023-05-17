<?php
namespace Kemkes\V2\Rpc\Run;

use DBService\RPCResource;
use Kemkes\V2\Rest\BedMonitor\Service as BedMonitorService;
use Kemkes\V2\Rpc\Diagnosa\Service as DiagnosaService;
use Kemkes\V2\Rpc\Bor\Service as BorService;
use Kemkes\V2\Rpc\Kunjungan\Service as KunjunganService;
use Kemkes\V2\Rpc\Indikator\Service as IndikatorService;
use Kemkes\V2\Rpc\Kematian\Service as KematianService;
use Kemkes\V2\Rpc\Rujukan\Service as RujukanService;
use Kemkes\V2\Rpc\Penyakit10Besar\Service as Penyakit10BesarService;
use Kemkes\V2\Rpc\DiagnosaRujukan10Besar\Service as DiagnosaRujukan10BesarService;
use Kemkes\V2\Rpc\GolonganDarah\Service as GolonganDarahService;

use \DateTime;
use \DateTimeZone;

class RunController extends RPCResource
{
    private $configKemkes;
    
	public function __construct($controller) {
		$this->config = $controller->get('Config');
		$this->config = $this->config['services']['KemkesService'];
		$this->configKemkes = $this->config;
		$this->config = $this->config["Dashboard"];
		$this->headers = array(
			"X-rs-id: ".$this->config["id"],
			"X-pass: ".md5($this->config["key"])
		);
	}
	
	private function generateTimestamp() {
	    $dt = new DateTime(null, new DateTimeZone("UTC"));
	    $timestamp = $dt->getTimestamp();
	    $this->headers[] = "X-Timestamp: ".$timestamp;
	}
	
	public function kunjunganAction() {
	    $service = new KunjunganService();
	    $this->generateTimestamp();
	    
	    /* ambil data kunjungan */
	    $params = [
	        "start" => 0,
	        "limit" => 10
	    ];
	    $params[] = "KIRIM = 1";
	    
	    $froms = $service->load($params, array('*'), array('TANGGAL_UPDATED ASC'));
	    foreach($froms as &$row) {
	        $params = [
	            "tanggal" => $row["TANGGAL"],
	            "kunjungan_rj" => $row["RJ"],
	            "kunjungan_igd" => $row["RD"],
	            "pasien_ri" => $row["RI"]
	        ];
	        $uri = "kunjungan";
	        if(trim($row["REF_ID_KEMKES"]) != "") {
	            $uri = "updatekunjungan";
	            $params["kode_kirim"] = $row["REF_ID_KEMKES"];
	        }
	        //$params = json_encode($params);
	        //$response = $this->sendRequest($uri, "POST", $params);
	        /* kirim data kunjungan */
	        $response = $this->sendRequestData([
	            "action" => $uri,
	            "method" => "POST",
	            "data" => $params
	        ]);
	        
	        $result = $this->getResultRequest($response);
	        if(isset($result["kode"])) {
	            if($result["kode"] == 200) {
	                $row["KIRIM"] = 0;
	                if(isset($result["kode_kirim"])) {
	                    $row["REF_ID_KEMKES"] = $result["kode_kirim"];
	                }
	                $service->simpan($row);
	            }
	        }
	    }
	    
	    return [
	        "data" => $froms
	    ];
	}
	
	public function bedMonitorAction() {
	    $bedmon = new BedMonitorService();
	    $query = $this->getRequest()->getQuery();
	    $data = $bedmon->get($query);
	    
	    $data = $this->toFormatXML($data);
	    
	    $this->headers = array(
	        "X-rs-id: ".$this->configKemkes["id"],
	        "X-pass: ".md5($this->configKemkes["key"])
	    );
	    $response = $this->sendRequest("ranap", 'POST', $data, "application/xml", $this->configKemkes["url"]);
	    
	    return array("respon" => isset($response) ? $response : "Ok");
	}
	
	public function diagnosaAction() {
	    return [];
	}
	
	public function indikatorAction() {
	    $service = new IndikatorService();
	    $this->generateTimestamp();
	    
	    /* ambil data indikator */
	    $params = [
	        "start" => 0,
	        "limit" => 10
	    ];
	    $params[] = "JENIS != 3 AND KIRIM = 1";
	    
	    $froms = $service->load($params, array('*'), array('TANGGAL_UPDATED ASC'));
	    foreach($froms as &$row) {
	        $params = [
	            "tahun" => $row["TAHUN"],
	            "periode" => $row["PERIODE"],
	            "bor" => number_format($row["BOR"], 1),
	            "alos" => number_format($row["ALOS"], 1),
	            "bto" => number_format($row["BTO"], 1),
	            "toi" => number_format($row["TOI"], 1),
	            "ndr" => number_format($row["NDR"], 1),
	            "gdr" => number_format($row["GDR"], 1)
	        ];
	        $uri = "indikator";
	        if(trim($row["REF_ID_KEMKES"]) != "") {
	            $uri = "updateindikator";
	            $params["kode_kirim"] = $row["REF_ID_KEMKES"];
	        }
	        /* kirim data */
	        $response = $this->sendRequestData([
	            "action" => $uri,
	            "method" => "POST",
	            "data" => $params
	        ]);
	        $result = $this->getResultRequest($response);
	        if(isset($result["kode"])) {
	            if($result["kode"] == 200) {
	                $row["KIRIM"] = 0;
	                if(isset($result["kode_kirim"])) {
	                    $row["REF_ID_KEMKES"] = $result["kode_kirim"];
	                }
	                $service->simpan($row);
	            }
	        }
	    }
	    
	    return [
	        "data" => $froms
	    ];
	}
	
	public function kematianAction() {
	    $service = new KematianService();
	    $this->generateTimestamp();
	    
	    /* ambil data kematian */
	    $params = [
	        "start" => 0,
	        "limit" => 10
	    ];
	    $params[] = "KIRIM = 1";
	    
	    $froms = $service->load($params, array('*'), array('TANGGAL_UPDATED ASC'));
	    foreach($froms as &$row) {
	        $params = [
	            "tahun" => $row["TAHUN"],
	            "bulan" => $row["BULAN"],
	            "data_kematian" => (array) json_decode("[".$row["KONTEN"]."]")
	        ];
	        $uri = "kematian";
	        if(trim($row["REF_ID_KEMKES"]) != "") {
	            $uri = "update_kematian";
	            $params["kode_kirim"] = $row["REF_ID_KEMKES"];
	        }
	        /* kirim data */
	        $response = $this->sendRequestData([
	            "action" => $uri,
	            "method" => "POST",
	            "data" => $params
	        ]);
	        $result = $this->getResultRequest($response);
	        if(isset($result["kode"])) {
	            if($result["kode"] == 200) {
	                $row["KIRIM"] = 0;
	                if(isset($result["response"])) {
	                    $row["REF_ID_KEMKES"] = $result["response"];
	                }
	                $service->simpan($row);
	            } else if($result["kode"] == 404) {
	                $row["REF_ID_KEMKES"] = "";
	                $service->simpan($row);
	            }
	        }
	    }
	    
	    return [
	        "data" => $froms
	    ];
	}
	
	public function rujukanAction() {
	    $service = new RujukanService();
	    $this->generateTimestamp();
	    
	    /* ambil data rujukan */
	    $params = [
	        "start" => 0,
	        "limit" => 10
	    ];
	    $params[] = "KIRIM = 1";
	    
	    $froms = $service->load($params, array('*'), array('TANGGAL_UPDATED ASC'));
	    foreach($froms as &$row) {
	        $params = [
	            "tanggal" => $row["TANGGAL"],
	            "jumlah_rujukan" => $row["MASUK"],
	            "jumlah_rujuk_balik" => $row["BALIK"]
	        ];
	        $uri = "rujukan";
	        if(trim($row["REF_ID_KEMKES"]) != "") {
	            $uri = "updaterujukan";
	            $params["kode_kirim"] = $row["REF_ID_KEMKES"];
	        }
	        //$params = json_encode($params);
	        //$response = $this->sendRequest($uri, "POST", $params);
	        /* kirim data */
	        $response = $this->sendRequestData([
	            "action" => $uri,
	            "method" => "POST",
	            "data" => $params
	        ]);
	        $result = $this->getResultRequest($response);
	        
	        if(isset($result["kode"])) {
	            if($result["kode"] == 200) {
	                $row["KIRIM"] = 0;
	                if(isset($result["response"])) {
	                    $row["REF_ID_KEMKES"] = $result["response"];
	                }
	                $service->simpan($row);
	            }
	        }
	    }
	    
	    return [
	        "data" => $froms
	    ];
	}
	
	public function penyakit10BesarAction() {
	    $service = new Penyakit10BesarService();
	    $this->generateTimestamp();
	    
	    /* ambil data 10 besar penyakit */
	    $params = [
	        "start" => 0,
	        "limit" => 10
	    ];
	    $params[] = "KIRIM = 1";
	    
	    $froms = $service->load($params, array('*'), array('TANGGAL_UPDATED ASC'));
	    foreach($froms as &$row) {
	        $jenisPelayanan = $row["JENIS_PELAYANAN"] == 1 ? "rawat_inap" : "rawat_jalan";
	        $params = [
	            "tahun" => $row["TAHUN"],
	            "bulan" => $row["BULAN"],
	            $jenisPelayanan => (array) json_decode("[".$row["KONTEN"]."]")
	        ];
	        if($params["bulan"] == 0) unset($params["bulan"]);
	        $uri = $row["JENIS_PELAYANAN"] == 1 ? "toptenranap" : "toptenrajal";
	        if(trim($row["REF_ID_KEMKES"]) != "") {
	            $uri = $row["JENIS_PELAYANAN"] == 1 ? "updatetoptenranap" : "updatetoptenrajal";
	            $params["kode_kirim"] = $row["REF_ID_KEMKES"];
	        }
	        /* kirim data */
	        $response = $this->sendRequestData([
	            "action" => $uri,
	            "method" => "POST",
	            "data" => $params
	        ]);
	        $result = $this->getResultRequest($response);
	        if(isset($result["kode"])) {
	            if($result["kode"] == 200) {
	                $row["KIRIM"] = 0;
	                if(isset($result["kode_kirim"])) {
	                    $row["REF_ID_KEMKES"] = $result["kode_kirim"];
	                }
	                $service->simpan($row);
	            }
	        }
	    }
	    
	    return [
	        "data" => $froms
	    ];
	}
	
	public function diagnosaRujukan10BesarAction() {
	    $service = new DiagnosaRujukan10BesarService();
	    $this->generateTimestamp();
	    
	    /* ambil data 10 besar diagnosa rujukan */
	    $params = [
	        "start" => 0,
	        "limit" => 10
	    ];
	    $params[] = "KIRIM = 1 AND JENIS_RUJUKAN = 1";
	    
	    $froms = $service->load($params, array('*'), array('TANGGAL_UPDATED ASC'));
	    foreach($froms as &$row) {
	        $jenisRujukan = $row["JENIS_RUJUKAN"] == 1 ? "dirujuk" : "rujuk";
	        $params = [
	            "tahun" => $row["TAHUN"],
	            "bulan" => $row["BULAN"],
	            $jenisRujukan => (array) json_decode("[".$row["KONTEN"]."]")
	        ];
	        if($params["bulan"] == 0) unset($params["bulan"]);
	        $uri = "toptenrujukan";
	        if(trim($row["REF_ID_KEMKES"]) != "") {
	            $uri = "updatetoptenrujukan";
	            $params["kode_kirim"] = $row["REF_ID_KEMKES"];
	        }
	        /* kirim data */
	        $response = $this->sendRequestData([
	            "action" => $uri,
	            "method" => "POST",
	            "data" => $params
	        ]);
	        
	        $result = $this->getResultRequest($response);
	        if(isset($result["kode"])) {
	            if($result["kode"] == 200) {
	                $row["KIRIM"] = 0;
	                if(isset($result["kode_kirim"])) {
	                    $row["REF_ID_KEMKES"] = $result["kode_kirim"];
	                }
	                $service->simpan($row);
	            }
	        }
	    }
	    
	    return [
	        "data" => $froms
	    ];
	}
	
	public function golonganDarahAction() {
	    $service = new GolonganDarahService();
	    $this->generateTimestamp();
	    
	    /* ambil data kematian */
	    $params = [
	        "start" => 0,
	        "limit" => 10
	    ];
	    $params[] = "KIRIM = 1";
	    
	    $froms = $service->load($params, array('*'), array('TANGGAL_UPDATED ASC'));
	    foreach($froms as &$row) {
	        $params = [
	            //"tahun" => $row["TAHUN"],
	            "bulan" => $row["BULAN"],
	            "kode_darah" => $row["KODE"],
	            "jumlah" => $row["JUMLAH_PASIEN"]
	        ];
	        $uri = "darah";
	        if(trim($row["REF_ID_KEMKES"]) != "") {
	            $uri = "updatedarah";
	            $params["kode_kirim"] = $row["REF_ID_KEMKES"];
	        }
	        /* kirim data */
	        $response = $this->sendRequestData([
	            "action" => $uri,
	            "method" => "POST",
	            "data" => $params
	        ]);
	        $result = $this->getResultRequest($response);
	        if(isset($result["kode"])) {
	            if($result["kode"] == 200) {
	                $row["KIRIM"] = 0;
	                if(isset($result["response"])) {
	                    $row["REF_ID_KEMKES"] = $result["response"];
	                }
	                $service->simpan($row);
	            } else if($result["kode"] == 404) {
	                $row["REF_ID_KEMKES"] = "";
	                $service->simpan($row);
	            }
	        }
	    }
	    
	    return [
	        "data" => $froms
	    ];
	}
		
	/**
	 * @deprecated
	 * @return array
	 */
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
