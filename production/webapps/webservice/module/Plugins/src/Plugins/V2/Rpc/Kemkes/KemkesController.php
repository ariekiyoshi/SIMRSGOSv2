<?php
namespace Plugins\V2\Rpc\Kemkes;

use DBService\RPCResource;
use Zend\Json\Json;
use ZF\ApiProblem\ApiProblem;

class KemkesController extends RPCResource
{
    protected $authType = self::AUTH_TYPE_LOGIN;
    
    public function __construct($controller) {
        $this->config = $controller->get('Config');
        $this->config = $this->config['services']['SIMpelService'];
        $this->config = $this->config['plugins']['Kemkes'];
    }
    
    /* Referensi
     * @method getFaskes
     * @params query & page [optional]
     */
    public function getFaskesAction() {
        $this->jenisBridge = 3;
        $query = $this->getRequest()->getQuery();
        $query = count($query) > 0 ? "?".http_build_query($query) : "";
        $result = $this->sendRequest("sisrute/getFaskes".$query);
        
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
        $this->jenisBridge = 3;
        $data = Json::encode($this->getPostData($this->getRequest()));
        $result = $this->sendRequest('sisrute/buatRujukan', "POST", $data);
        return $this->getResultRequest($result);
    }
    
    /* Rujukan
     * @method ubahRujukan/id(Nomor Rujukan)
     * @params = sama dengan post
     */
    public function ubahRujukanAction() {
        $this->jenisBridge = 3;
        $id = $this->params()->fromRoute('id', 0);
        $data = Json::encode($this->getPostData($this->getRequest()));
        $result = $this->sendRequest('sisrute/ubahRujukan/'.$id, "PUT", $data);
        return $this->getResultRequest($result);
    }
    
    /* Rujukan
     * @method simpanRujukan
     * @params = sama dengan post
     */
    public function simpanRujukanAction() {
        $this->jenisBridge = 3;
        $data = Json::encode($this->getPostData($this->getRequest()));
        $result = $this->sendRequest('sisrute/simpanRujukan', "POST", $data);
        return $this->getResultRequest($result);
    }
    
    /* Rujukan
     * @method batalRujukan/id(Nomor Rujukan)
     */
    public function batalRujukanAction() {
        $this->jenisBridge = 3;
        $id = $this->params()->fromRoute('id', 0);
        $data = Json::encode($this->getPostData($this->getRequest()));
        $result = $this->sendRequest('sisrute/batalRujukan/'.$id, "PUT", $data);
        return $this->getResultRequest($result);
    }
    
    /* Rujukan
     * @method getRujukan/id(Nomor Rujukan)
     */
    public function getRujukanAction() {
        $this->jenisBridge = 3;
        $id = $this->params()->fromRoute('id', 0);
        $result = $this->sendRequest('sisrute/getRujukan/'.$id);
        return $this->getResultRequest($result);
    }
    
    /* Rujukan
     * @method listRujukan
     * @params nomor [optional], tanggal [optional], page [optional], create [optional] (menampilkan rujukan yg dibuat)
     */
    public function listRujukanAction() {
        $this->jenisBridge = 3;
        $query = $this->getRequest()->getQuery();
        $query = count($query) > 0 ? "?".http_build_query($query) : "";
        $result = $this->sendRequest('sisrute/listRujukan'.$query);
        return $this->getResultRequest($result);
    }
    
    /* SITT
     * @method sitt
     * @params 
     */
    public function sittAction() {
        $this->jenisBridge = 6;
        $request = $this->getRequest();
        $method = $request->getMethod();
        if($method == "GET") {
            $querys = $request->getQuery();
            $querys = count($querys) > 0 ? "?".http_build_query($querys) : "";
            $result = $this->sendRequest('sitt'.$querys);
        } else {
            $id = $this->params()->fromRoute('id', 0);
            $data = Json::encode($this->getPostData($request));
            if(isset($id)) {
                $result = $this->sendRequest('sitt/'.$id, "PUT", $data);
            } else {
                $result = $this->sendRequest('sitt', "POST", $data);
            }
        }
        
        return $this->getResultRequest($result);
    }
}