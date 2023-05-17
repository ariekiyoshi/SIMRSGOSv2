<?php
namespace Kemkes\V2\Rpc\PendaftaranOnline;

use DBService\ResponseFormat;
use General\V1\Rest\Pasien\PasienService;
use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\RuanganKonsul\RuanganKonsulService;
use Kemkes\V2\Rest\ReservasiAntrian\Service as ReservasiAntrianService;

class PendaftaranOnlineController extends ResponseFormat
{
    private $pasienService;
    private $referensiService;
    private $klinikService;
    private $resevasiAntrian;
    
    public function __construct() {
        $this->referensiService = new ReferensiService();
        $this->pasienService = new PasienService();
        $this->klinikService = new RuanganKonsulService();
        $this->resevasiAntrian = new ReservasiAntrianService();
    }
    
    public function getPasienAction() {
        $params = $this->getXMLPostData();
        $params = $params->data;
        $tglLahir = $params->TglLahir->__toString();
        $tglLahir = $tglLahir == "" ? "-" : $tglLahir;
        
        $data = array();
        $pasiens = $this->pasienService->load(array(
            "NORM" => $params->NoCM->__toString(),
            "TANGGAL_LAHIR" => $tglLahir,
            "STATUS" => 1
        ));
        
        if(count($pasiens) > 0){
            $i = 0;
            foreach($pasiens as $p) {
                $data[$i]["nama"] = $p["NAMA"];
                $data[$i]["tgllahir"] = $p["TANGGAL_LAHIR"];
                $data[$i]["alamat"] = $p["ALAMAT"];
                if(isset($p["KONTAK"])) {
                    if(is_array($p["KONTAK"])) {
                        foreach($p["KONTAK"] as $k) {
                            if($k["JENIS"] < 4) $data[$i]["nomorcontact"] = $k["NOMOR"];
                        }
                    }
                }
                $data[$i]["status"] = "true";
                $i++;
            }
        }else{
            $data[0]["status"] = "false";
            $data[0]["Pesan"] = "Data pasien tidak ditemukan";
        }
        
        $result = $this->toXML($data);
        return $result;
    }
    
    public function getPenjaminAction() {
        $penjamins = $this->referensiService->load(array(
            "JENIS" => 10,
            "STATUS" => 1
        ), array(
            "idcarabayar" => "ID",
            "deskripsi" => "DESKRIPSI"
        ));
        
        $result = $this->toXML($penjamins);
        return $result;
    }
    
    public function getKlinikAction() {
        $kliniks = $this->klinikService->load(array(
            "start" => 0,
            "limit" => 1000,
            "STATUS" => 1
        ), array("*", "TGL" => new \Zend\Db\Sql\Expression('NOW()')));
        
        $data = array();
        $haris = array("Senin", "Selasa", "Rabu", "Kamis", "Jumat");
        $i = 0;
        foreach($kliniks as $k) {
            foreach($haris as $h) {
                $data[$i]["idklinik"] = $k["REFERENSI"]["KONSUL"]["ID"];
                $data[$i]["namaklinik"] = $k["REFERENSI"]["KONSUL"]["DESKRIPSI"];
                $data[$i]["hari"] = $h;
                $data[$i]["jambukapelayanan"] = "07:30:00";
                $data[$i]["jamtutuppelayanan"] = "16:00:00";
                $data[$i]["kuota"] = "50";
                $data[$i]["update"] = $k["TGL"];
                $i++;
            }
        }
        
        $result = $this->toXML($data);
        return $result;
    }
    
    public function buatReservasiAntrianAction() {
        $data = $this->getXMLPostData();
        $data = $data->data;
        $now = date('Y-m-d');
        $tglres = $data->TglKunjungan->__toString();
        $rev = array(
            array(
                "status" => "false",
                "Pesan" => "Terjadi kesalahan pengambilan nomor antrian"
            )
        );
        if($now < $tglres){
            $pasien = $data->NoCM->__toString();
            $params = array(
                "PASIEN" => $data->NoCM->__toString(),
                "TANGGAL_KUNJUNGAN" => $data->TglKunjungan->__toString()
            );
            if(!isset($pasien)) {
                $params = array(
                    "NAMA" => $data->NamaPasien->__toString(),
                    "TEMPAT_LAHIR" => $data->TempatLahir->__toString(),
                    "TGL_LAHIR" => $data->TglLahir->__toString(),
                    "TANGGAL_KUNJUNGAN" => $data->TglKunjungan->__toString()
                );
            }
            $antrians = $this->resevasiAntrian->load($params);
            if(count($antrians) > 0) {
                $rev = array(
                    array(
                        "status" => "false",
                        "Pesan" => "Anda telah mendaftar sebelumnya dengan tanggal yang sama. No. Antrian ".$antrians[0]["NOMOR"]." datang sebelum jam ".$antrians[0]["JAM"]." "
                    )
                );
            } else {
                $dataReq = array(
                    "PASIEN" => $data->NoCM->__toString(),
                    "TANGGAL_KUNJUNGAN" => $data->TglKunjungan->__toString(),
                    "RUANGAN" => $data->idpoli->__toString(),
                    "DOKTER" => new \Zend\Db\Sql\Expression('NULL'),
                    "PENJAMIN" => $data->carabayar->__toString(),
                    "KONTAK" => $data->NomorContact->__toString(),
                    "TANGGAL_DAFTAR" => $data->TanggalDaftar->__toString(),
                    "JENIS" => 2,
                    "STATUS" => 1
                );
                if(!isset($pasien)) {
                    unset($dataReq["PASIEN"]);
                    $dataReq["NAMA"] = $data->NamaPasien->__toString();
                    $dataReq["TEMPAT_LAHIR"] = $data->TempatLahir->__toString();
                    $dataReq["TANGGAL_LAHIR"] = $data->TglLahir->__toString();
                    $dataReq["JENIS"] = 1;
                }
                
                $antrians = $this->resevasiAntrian->simpan($dataReq, true);
                
                if(count($antrians) > 0) {
                    $rev = array(
                        array(
                            "status" => "true",
                            "NomorReservasi" => $antrians[0]["NOMOR"],
                            "JamKunjungan" => $antrians[0]["JAM"]
                        )
                    );
                }
            }
        } else {
            $rev = array(
                array(
                    "status" => "false",
                    "Pesan" => "Anda tidak boleh mendaftar di bawah atau sama dengan tanggal hari ini"
                )
            );
        }
        
        $result = $this->toXML($rev);
        return $result;
    }
}
