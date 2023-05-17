<?php
namespace DukcapilService\V1\Rpc\Dukcapil;

use ZF\ApiProblem\ApiProblem;
use DBService\RPCResource;
use Zend\Json\Json;
use DukcapilService\db\keluarga\Service as KeluargaService;
use DukcapilService\db\penduduk\Service as PendudukService;

class DukcapilController extends RPCResource
{
    private $keluarga;
    private $penduduk;
    
    private $params = [];
    
    public function __construct($controller) {
        $this->authType = self::AUTH_TYPE_IP_OR_LOGIN;
        $this->keluarga = new KeluargaService();
        $this->penduduk = new PendudukService();
        
        $this->config = $controller->get('Config');
        $this->config = $this->config['services']['DukcapilService'];
        $this->params = [
            "user_id" => $this->config["id"],
            "password" => $this->config["pass"],
            "IP_user" => $this->config["ip"]
        ];

        $this->jenisBridge = 5;
    }
    
    public function nikAction() {
        $this->params["NIK"] = $this->params()->fromRoute('id', 0);
        
        $response = $this->sendRequestData([
            "action" => "CALL_NIK",
            "method" => "POST",
            "data" => $this->params
        ]);
        
        $result = $this->getResultRequest($response);
        
        $return = [
            "status" => 404,
            "success" => false,
            "data" => null,
            "detail" => "NIK tidak ditemukan"
        ];
        
        if(isset($result["content"])) {
            $content = $result["content"];
            if(count($content) > 0) {
                $content = $content[0];
                if(!isset($content["RESPON"])) {
                    $founds = $this->keluarga->load(["NO_KK" => $content["NO_KK"]]);
                    $keluarga = $this->keluarga->simpanData($content, count($founds) == 0);
                    
                    $founds = $this->penduduk->load(["NIK" => $content["NIK"]]);
                    $penduduk = $this->penduduk->simpanData($content, count($founds) == 0);
                    
                    $return["status"] = 200;
                    $return["success"] = true;
                    $return["data"] = $this->toMap($keluarga[0], $penduduk[0]);
                    $return["detail"] = "NIK ditemukan";
                } else {
                    $return["detail"] = $content["RESPON"];
                }
            }
        }
        
        return $return;
    }
    
    private function toMap($keluarga, $penduduk) {
        $wilayah = str_pad($keluarga["NO_PROP"], 2, "0", STR_PAD_LEFT)
        .str_pad($keluarga["NO_KAB"], 2, "0", STR_PAD_LEFT)
        .str_pad($keluarga["NO_KEC"], 2, "0", STR_PAD_LEFT)
        .$keluarga["NO_KEL"];
        return [
            "NO_KK" => $keluarga["NO_KK"],
            "NIK" => $penduduk["NIK"],
            "NAMA" => $penduduk["NAMA_LGKP"],
            "TEMPAT_LAHIR" => $penduduk["TMPT_LHR"],
            "TANGGAL_LAHIR" => $penduduk["TGL_LHR"]." 00:00:00",
            "JENIS_KELAMIN_DESK" => $penduduk["JENIS_KLMIN"],
            "ALAMAT" => $keluarga["ALAMAT"],
            "RT" => $keluarga["NO_RT"],
            "RW" => $keluarga["NO_RW"],
            "WILAYAH" => $wilayah,
            "PENDIDIKAN_DESK" => $penduduk["PDDK_AKH"],
            "PEKERJAAN_DESK" => $penduduk["JENIS_PKRJN"],
            "STATUS_PERKAWINAN_DESK" => $penduduk["STATUS_KAWIN"],
            "KARTUIDENTITAS" => [
                "JENIS" => "1",
                "NOMOR" => $penduduk["NIK"],
                "ALAMAT" => $keluarga["ALAMAT"],
                "RT" => $keluarga["NO_RT"],
                "RW" => $keluarga["NO_RW"],
                "WILAYAH" => $wilayah
            ]
        ];
    }
}
