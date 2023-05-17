<?php
namespace Kemkes\V2\Rpc\Sisrute;

use DBService\RPCResource;
use \DateTime;
use \DateTimeZone;
use Zend\Json\Json;
use Aplikasi\Signature;
use Kemkes\db\rujukan\Service as RujukanService;

class SisruteController extends RPCResource
{
    private $rujukan;
    
    public function __construct($controller) {
        $this->config = $controller->get('Config');
        $this->config = $this->config['services']['KemkesService'];
        $this->config = $this->config['Sisrute'];
        $this->headers = [
            "X-cons-id: ".$this->config["id"]
        ];
        
        $this->rujukan = new RujukanService();
    }
    
    private function doSignature() {
        $sign = new Signature(null, null, null);
        $timestamp = $sign->getTimestamp();
        $sig = $sign->generateSign([
            "X_ID" => $this->config["id"],
            "X_PASS" => md5($this->config["pass"])
        ], $timestamp);
        
        $this->headers[] = "X-Timestamp: ".$timestamp;
        $this->headers[] = "X-signature: ".$sig;
    }      
    
    /* Referensi 
     * @method getFaskes
     * @params query & page [optional]
     */  
    public function getFaskesAction() {
        $query = $this->getRequest()->getQuery();
        $query = count($query) > 0 ? "?".http_build_query($query) : "";
        $this->doSignature();
        $result = $this->sendRequest("referensi/faskes".$query);
        
        return $this->getResultRequest($result);
    }
    
    /* Rujukan
     * @method buatRujukan
     * @params 
     * {
           "PASIEN": {
              "NORM": 11223345,               # Nomor Rekam Medis
        	  "NIK": "7371140101010003",      # Nomor Induk Kependudukan
        	  "NO_KARTU_JKN": "0000001234501",# Nomor Kartu Jaminan Kesehatan Nasional / BPJS
        	  "NAMA": "Rahmat Hidayat",       # Nama Pasien (Tanpa Gelar)
        	  "JENIS_KELAMIN": "1",           # Jenis Kelamin 1. Laki - laki, 2. Perempuan
        	  "TANGGAL_LAHIR": "1980-01-03",  # Tanggal Lahir Format yyyy-mm-dd
        	  "TEMPAT_LAHIR": "Makassar",     # Tempat Lahir
        	  "ALAMAT": "Pettarani",          # Alamat
        	  "KONTAK": "085123123122"        # Nomor Kontak / HP
           },
           "RUJUKAN": {
        	  "JENIS_RUJUKAN": "2",          # Jenis Rujukan 1. Rawat Jalan, 2. Rawat Darurat/Inap, 3. Parsial
        	  "TANGGAL": "2018-08-29 10:00:00", # Tanggal Rujukan Format yyy-mm-dd hh:ii:ss
        	  "FASKES_TUJUAN": "3404015",     # Kode Faskes Tujuan
        	  "ALASAN": "1",                  # Lihat Referensi Alasan Rujukan
        	  "ALASAN_LAINNYA": "Pusing",     # Alasan Lainnya / Tambahan Alasan Rujukan
        	  "DIAGNOSA": "I10",              # Kode ICD10 Diagnosa Utama
        	  "DOKTER": {                     # Dokter DPJP
        	     "NIK": "7371140101010111",  # NIK Dokter
        		 "NAMA": "Dr. Raffi"         # Nama Dokter
              },
        	  "PETUGAS": {                    # Petugas yang merujuk
        	     "NIK": "7371140101010112",  # NIK Petugas
        		 "NAMA": "Enal"              # Nama Petugas
        	  }
           },
           "KONDISI_UMUM": {
              "KESADARAN": "1",               # Kondisi Kesadaran Pasien 1. Sadar, 2. Tidak Sadar
        	  "TEKANAN_DARAH": "120/90",      # Tekanan Darah Pasien dalam satuan mmHg
        	  "FREKUENSI_NADI": "50",         # Frekuensi Nadi Pasien (Kali/Menit)
        	  "SUHU": "37",                   # Suhu (Derajat Celcius)
        	  "PERNAPASAN": "25",             # Pernapasan (Kali/Menit)
        	  "KEADAAN_UMUM": "sesak, gelisah", # Keadaan Umum Pasien
        	  "NYERI": 0,                     # Skala Nyeri 0. Tidak Nyeri, 1. Ringan, 2. Sedang, 3. Berat
        	  "ALERGI": "-"                   # Alergi Pasien
           },
           "PENUNJANG": {
              "LABORATORIUM": "WBC:11,2;HB:15,6;PLT:215;", # Hasil Laboratorium format: parameter:hasil;
        	  "RADIOLOGI": "EKG:Sinus Takikardi;Foto Thorax:Cor dan pulmo normal;", # Hasil Radiologi format: tindakan:hasil;
        	  "TERAPI_ATAU_TINDAKAN": "TRP:LOADING NACL 0.9% 500 CC;INJ. RANITIDIN 50 MG;#TDK:TERPASANG INTUBASI ET NO 8 BATAS BIBIR 21CM;" # Terapi atau Tindakan yang diberikan format; TRP:Nama;#TDK:Nama;
           }
        }
     */
    public function buatRujukanAction() {
        $data = (array) $this->getPostData($this->getRequest());
        $this->doSignature();
        
        if($data["RUJUKAN"]) {
            $rujukan = $data["RUJUKAN"];
        }
        $data = Json::encode($data);
        $result = $this->sendRequest('rujukan', "POST", $data);
        $result = $this->getResultRequest($result);
        if($result["status"] == 200 || $result["status"] == 201) {
            if($result["data"]) {
                $rujukan["NOMOR"] = $result["data"]["RUJUKAN"]["NOMOR"];
                $rujukan["NIK_DOKTER"] = $rujukan["DOKTER"]["NIK"];
                $rujukan["NAMA_DOKTER"] = $rujukan["DOKTER"]["NAMA"];
                $rujukan["NIK_PETUGAS"] = $rujukan["PETUGAS"]["NIK"];
                $rujukan["NAMA_PETUGAS"] = $rujukan["PETUGAS"]["NAMA"];                
                $rujukan["STATUS"] = $result["data"]["RUJUKAN"]["STATUS"]["KODE"];
                
                $this->rujukan->simpan($rujukan, true);
            }
        }
        return $result;
    }
    
    /* Rujukan
     * @method ubahRujukan/id(Nomor Rujukan)
     * @params = sama dengan post 
     */
    public function ubahRujukanAction() {
        $id = $this->params()->fromRoute('id', 0);
        $data = (array) $this->getPostData($this->getRequest());
        $this->doSignature();
        
        if($data["RUJUKAN"]) {
            $rujukan = $data["RUJUKAN"];
        }
        $data = Json::encode($data);
        $result = $this->sendRequest('rujukan/'.$id, "PUT", $data);
        $result = $this->getResultRequest($result);
        if($result["status"] == 200 || $result["status"] == 201) {
            if($result["data"]) {
                $rujukan["NOMOR"] = $id;
                if($rujukan["DOKTER"]) {
                    $rujukan["NIK_DOKTER"] = $rujukan["DOKTER"]["NIK"];
                    $rujukan["NAMA_DOKTER"] = $rujukan["DOKTER"]["NAMA"];
                }
                if($rujukan["PETUGAS"]) {
                    $rujukan["NIK_PETUGAS"] = $rujukan["PETUGAS"]["NIK"];
                    $rujukan["NAMA_PETUGAS"] = $rujukan["PETUGAS"]["NAMA"];
                }
                $rujukan["STATUS"] = $result["data"]["RUJUKAN"]["STATUS"]["KODE"];
                
                $this->rujukan->simpan($rujukan);
            }
        }
        return $result;
    }
    
    /* Rujukan
     * @method simpanRujukan
     * @params = sama dengan post
     */
    public function simpanRujukanAction() {
        $data = (array) $this->getPostData($this->getRequest());
        $this->doSignature();
        
        $isCreate = true;        
        if($data["RUJUKAN"]) {
            $rujukan = $data["RUJUKAN"];
            if(isset($rujukan["REF"])) {
                $params = [
                    "REF" => $rujukan["REF"]
                ];
                $params[] = new \Zend\Db\Sql\Predicate\Expression("NOT STATUS = 2");
                $result = $this->rujukan->load($params);
                $isCreate = count($result) == 0;
            }           
        }
        $data = Json::encode($data);
        if($isCreate) {
            $result = $this->sendRequest('rujukan', "POST", $data);
        } else {
            $nomor = $result[0]["NOMOR"];
            $result = $this->sendRequest('rujukan/'.$nomor, "PUT", $data);
        }
        $result = $this->getResultRequest($result);
        if($result["status"] == 200 || $result["status"] == 201) {
            if($result["data"]) {
                $rujukan["NOMOR"] = $result["data"]["RUJUKAN"]["NOMOR"];
                if($rujukan["DOKTER"]) {
                    $rujukan["NIK_DOKTER"] = $rujukan["DOKTER"]["NIK"];
                    $rujukan["NAMA_DOKTER"] = $rujukan["DOKTER"]["NAMA"];
                }
                if($rujukan["PETUGAS"]) {
                    $rujukan["NIK_PETUGAS"] = $rujukan["PETUGAS"]["NIK"];
                    $rujukan["NAMA_PETUGAS"] = $rujukan["PETUGAS"]["NAMA"];
                }
                $rujukan["STATUS"] = $result["data"]["RUJUKAN"]["STATUS"]["KODE"];
                
                $this->rujukan->simpan($rujukan, $isCreate);
            }
        }
        return $result;
    }       
    
    /* Rujukan
     * @method batalRujukan/id(Nomor Rujukan)
     */
    public function batalRujukanAction() {
        $id = $this->params()->fromRoute('id', 0);
        $data = Json::encode($this->getPostData($this->getRequest()));
        $params = [
            "REF" => $id
        ];
        $params[] = new \Zend\Db\Sql\Predicate\Expression("NOT STATUS = 2");
        $result = $this->rujukan->load($params);
        if(count($result) > 0) {
            $id = $result[0]["NOMOR"];
        }
        
        $this->doSignature();
        $result = $this->sendRequest('rujukan/batal/'.$id, "PUT", $data);
        $result = $this->getResultRequest($result);
        if($result["status"] == 200) {
            if($result["success"]) {
                $tmp = Json::decode($data, Json::TYPE_ARRAY);
                $data = [
                    "NOMOR" => $id,
                    "STATUS" => 2,
                    "NIK_PETUGAS_PEMBATALAN" => $tmp["PETUGAS"]["NIK"],
                    "NAMA_PETUGAS_PEMBATALAN" => $tmp["PETUGAS"]["NAMA"]
                ];
                $this->rujukan->simpan($data);
            }
        }
        return $result;
    }
    
    /* Rujukan
     * @method batalRujukan/id(Nomor Rujukan)
     */
    public function getRujukanAction() {
        $id = $this->params()->fromRoute('id', 0);
        $this->doSignature();
        $result = $this->sendRequest('rujukan/'.$id);
        return $this->getResultRequest($result);
    }
    
    /* Rujukan
     * @method listRujukan
     * @params nomor [optional], tanggal [optional], page [optional], create [optional] (menampilkan rujukan yg dibuat)
     */
    public function listRujukanAction() {
        $query = $this->getRequest()->getQuery();
        $query = count($query) > 0 ? "?".http_build_query($query) : "";
        $this->doSignature();
        $result = $this->sendRequest('rujukan'.$query);
        return $this->getResultRequest($result);
    }
}
