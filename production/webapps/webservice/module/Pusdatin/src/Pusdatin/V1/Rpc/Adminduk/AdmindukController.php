<?php
namespace Pusdatin\V1\Rpc\Adminduk;

use DBService\RPCResource;
use Zend\View\Model\JsonModel;
use ZF\ApiProblem\ApiProblem;

use DukcapilService\db\keluarga\Service as KeluargaService;
use DukcapilService\db\penduduk\Service as PendudukService;

class AdmindukController extends RPCResource
{
    private $keluarga;
    private $penduduk;
    
    protected $authType = self::AUTH_TYPE_IP_OR_LOGIN;
    
    public function __construct($controller) {
        $this->config = $controller->get('Config');
        $this->config = $this->config['services']['PusdatinService'];
        $active = $this->config["active"];
        
        $class = "\\Pusdatin\\V1\\Rpc\\Adminduk\\".$active."\\Service";
        $this->service = new $class($this->config);
        
        $this->keluarga = new KeluargaService();
        $this->penduduk = new PendudukService();
    }
    
    public function getPendudukAction()
    {
        $nik = $this->params()->fromRoute('id', 0);
        $result = $this->service->getPenduduk($nik);
        $result = (array) $result;
        
        if($result["status"] == 200) {
            $content = $result["data"];
            $founds = $this->keluarga->load(["NO_KK" => $content["NO_KK"]]);
            $keluarga = $this->keluarga->simpanData($content, count($founds) == 0);
            
            $founds = $this->penduduk->load(["NIK" => $content["NIK"]]);
            $penduduk = $this->penduduk->simpanData($content, count($founds) == 0);
            
            $result["data"] = $this->toMap($keluarga[0], $penduduk[0]);
        }
        
        return $result;
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
            "AGAMA_DESK" => $penduduk["AGAMA"],
            "GOLONGAN_DARAH_DESK" => $penduduk["GOL_DARAH"],
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