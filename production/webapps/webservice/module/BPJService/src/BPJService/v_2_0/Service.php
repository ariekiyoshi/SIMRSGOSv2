<?php
/**
 * BPJService
 * @author hariansyah
 * 
 */
 
namespace BPJService\v_2_0;

use BPJService\BaseService;

class Service extends BaseService {
	/*
	 * cari daftar peserta yang rujukan berdasarkan tanggal rujukan
	 * format tanggal Y-m-d
	 */
	function cariDaftarPersertaRujukanDgnTglRujukan($tanggal, $start, $limit) {
		$result = json_decode($this->sendRequest("Rujukan/rujukan/tglrujuk/".$tanggal."/query?start=".$start."&limit=".$limit));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - cari daftar peserta yang rujukan berdasarkan tanggal rujukan<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* SEP>>>Data Riwayat Pelayanan Peserta
	 * Mencari Data Riwayat Pelayanan Peserta
	 * @parameter 
	 *   $params array(
	 *		"noKartu" => value
	 *)
	 *   $uri string
	 */
	function riwayatPelayananPeserta($params, $uri = "SEP/sep/peserta/") {		
		$noKartu = $params;
		if(is_array($params)) {
			if(isset($params["noKartu"])) $noKartu = $params["noKartu"];
		}
		$result = json_decode($this->sendRequest($uri.$noKartu));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(			
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Data Riwayat Pelayanan Peserta<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* SEP>>>Monitoring Verifikasi Klaim
	 * Monitoring Verifikasi Klaim Pelayanan
	 * @parameter 
	 *   $params array(
	 *		"tglMasuk" => value | yyyy-MM-dd
	 *		"tglKeluar" => value | yyyy-MM-dd
	 *		"kelasRawat" => value | 1 (Kelas 1), 2 (Kelas 2), 3 (Kelas 3)
	 *		"jnsPelayanan" => value | 1 (Rawat Inap), 2 (Rawat Jalan)
	 *		"cari" => value | 0 (tanggal masuk), 1 (tanggal keluar
	 *		"status" => value | 00 (Klaim_Baru); 10 (Klaim_Terima_CBG); 21 (Klaim_Layak); 22 (Klaim_Tidak_Layak); 23 (Klaim_Pending); 30 (TerVerifikasi); 40 (Proses_Cabang)
	 *)
	 *   $uri string
	 */
	function monitoringVerifikasiKlaim($params, $uri = "sep/integrated/Kunjungan/") {
		$tglMasuk = $tglKeluar = $kelasRawat= $jnsPelayanan = $cari = $status = "";
		if(is_array($params)) {
			if(isset($params["tglMasuk"])) $tglMasuk = $params["tglMasuk"];
			if(isset($params["tglKeluar"])) $tglKeluar = $params["tglKeluar"];
			if(isset($params["kelasRawat"])) $kelasRawat = $params["kelasRawat"];
			if(isset($params["jnsPelayanan"])) $jnsPelayanan = $params["jnsPelayanan"];
			if(isset($params["cari"])) $cari = $params["cari"];
			if(isset($params["status"])) $cari = $params["status"];
		}
		$result = json_decode($this->sendRequest($uri
				."tglMasuk/$tglMasuk/tglKeluar/$tglKeluar/KlsRawat/$kelasRawat/Kasus/$jnsPelayanan/Cari/"
				."$cari/status/$status"));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(			
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Data Riwayat Pelayanan Peserta<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* SEP>>>Data Kunjungan Peserta
	 * Data Kunjungan Peserta
	 * @parameter 
	 *   $params array(
	 *		"noSEP" => value
	 *)
	 *   $uri string
	 */
	function kunjunganPeserta($params, $uri = "sep/integrated/Kunjungan/sep/") {
		$noSEP = $params;
		if(is_array($params)) {
			if(isset($params["noSEP"])) $noSEP = $params["noSEP"];
		}
		$result = json_decode($this->sendRequest($uri.$noSEP));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(			
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Data Kunjungan Peserta<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* Referensi>>>Diagnosa
	 * Pencarian data diagnosa 
	 */
	function diagnosa($params, $uri = "diagnosa/cbg/diagnosa/") {
		$kode = $params;
		if(is_array($params)) {
			if(isset($params["kode"])) $kode = $params["kode"];
		}
		$result = json_decode($this->sendRequest($uri.$kode));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(			
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Pencarian data diagnosa<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
}