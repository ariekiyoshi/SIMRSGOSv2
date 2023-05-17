<?php
/**
 * BPJService
 * @author hariansyah
 * 
 */
 
namespace BPJService\v_2_1;

use BPJService\BaseService;

class Service extends BaseService {
	/* Peserta>>>No.Kartu BPJS
	 * cari peserta berdasarkan Nomor Kartu BPJS 
	 * @parameter 
	 *   $params string | array("noKartu" => value, "norm" => value)
	 *   $uri string
	 */
	function cariPesertaDgnNoKartuBPJS($params, $uri = "Peserta/Peserta/") {
		return parent::cariPesertaDgnNoKartuBPJS($params, $uri);
	}
	
	/* Peserta>>>NIK
	 * cari perserta berdasarkan Nomor Induk Kependudukan (e-KTP)
	 * @parameter 
	 *   $params string | array("nik" => value, "norm" => value)
	 *   $uri string
	 */
	function cariPesertaDgnNIK($params, $uri = "Peserta/Peserta/nik/") {
		return parent::cariPesertaDgnNIK($params, $uri);
	}
	
	/* Rujukan>>>Data Rujukan by Nomor Rujukan
	 * cari data rujukan berdasarkan Nomor rujukan
	 * @parameter 
	 *   $params string | array("nomor" => value)
	 *   $uri string
	 */
	function cariRujukanDgnNoRujukan($params, $uri = "Rujukan/") {
		$tgl = null;
		$r1 = parent::cariRujukanDgnNoRujukan($params, $uri);
		if($r1) {			
			if($r1->response) {
				if($r1->response->item) {					
					$tgl = $r1->response->item->tglKunjungan;
				}
			}			
		}
		$r2 = parent::cariRujukanDgnNoRujukan($params, $uri."RS/");
		if($tgl) {
			if($r2) {			
				if($r2->response) {
					if($r2->response->item) {
						$tgl1 = $r2->response->item->tglKunjungan;
						if($tgl1 > $tgl) $r1 = $r2;
					}
				}
			}
		} else $r1 = $r2;
		
		if($r1) {			
			if($r1->response) {
				if($r1->response->item) {
					$rujukan = $r1->response->item;
					unset($r1->response->item);
					
					$rujukan->diagnosa->kode = $rujukan->diagnosa->kdDiag;
					$rujukan->diagnosa->nama = $rujukan->diagnosa->nmDiag;
					unset($rujukan->diagnosa->kdDiag);
					unset($rujukan->diagnosa->nmDiag
					
					$rujukan->poliRujukan->kode = $rujukan->poliRujukan->kdPoli;
					$rujukan->poliRujukan->nama = $rujukan->poliRujukan->nmPoli;
					unset($rujukan->poliRujukan->kdPoli);
					unset($rujukan->poliRujukan->nmPoli);
					
					$rujukan->provPerujuk = (object) array();					
					$rujukan->provPerujuk->kode = $rujukan->provKunjungan->kdProvider;
					$rujukan->provPerujuk->nama = $rujukan->provKunjungan->nmProvider;
					unset($rujukan->provKunjungan);
					
					$r1->response->rujukan = $rujukan;
				}
			}			
		}
		
		return $r1;
	}
	
	/* Rujukan>>>Data Rujukan Berdasarkan Nomor kartu
	 * cari data rujukan berdasarkan Nomor Kartu BPJS
	 * @parameter 
	 *   $params string | array("noKartu" => value)
	 *   $uri string
	 */
	function cariRujukanDgnNoKartuBPJS($params, $uri = "Rujukan/") {
		$tgl = null;
		$r1 = parent::cariRujukanDgnNoKartuBPJS($params, $uri."Peserta/");
		if($r1) {
			if($r1->response) {
				if($r1->response->item) {
					$tgl = $r1->response->item->tglKunjungan;
				}
			}			
		}
		$r2 = parent::cariRujukanDgnNoKartuBPJS($params, $uri."RS/Peserta/");
		if($tgl) {
			if($r2) {
				if($r2->response) {
					if($r2->response->item) {
						$tgl1 = $r2->response->item->tglKunjungan;
						if($tgl1 > $tgl) $r1 = $r2;
					}
				}
			}
		} else $r1 = $r2;
		
		if($r1) {			
			if($r1->response) {
				if($r1->response->item) {
					$rujukan = $r1->response->item;
					unset($r1->response->item);
					
					$rujukan->diagnosa->kode = $rujukan->diagnosa->kdDiag;
					$rujukan->diagnosa->nama = $rujukan->diagnosa->nmDiag;
					unset($rujukan->diagnosa->kdDiag);
					unset($rujukan->diagnosa->nmDiag
					
					$rujukan->poliRujukan->kode = $rujukan->poliRujukan->kdPoli;
					$rujukan->poliRujukan->nama = $rujukan->poliRujukan->nmPoli;
					unset($rujukan->poliRujukan->kdPoli);
					unset($rujukan->poliRujukan->nmPoli);
					
					$rujukan->provPerujuk = (object) array();					
					$rujukan->provPerujuk->kode = $rujukan->provKunjungan->kdProvider;
					$rujukan->provPerujuk->nama = $rujukan->provKunjungan->nmProvider;
					unset($rujukan->provKunjungan);
					
					$r1->response->rujukan = $rujukan;
				}
			}			
		}
		
		return $r1;
	}	
	
	/* SEP>>>Insert SEP
	 * generate Nomor SEP
	 * @parameter 
	 *   $params array(
	 *		"noKartu" => value
	 *		, "tglSep" => value
	 *		, "tglRujukan" => value
	 *		, "noRujukan" => value
	 *		, "ppkRujukan" => value
	 *		, "jnsPelayanan" => value
	 *		, "catatan" => value
	 *		, "diagAwal" => value
	 *		, "poliTujuan" => value
	 *		, "user" => value
	 *		, "noMr" => value
	 *		, "lakaLantas" => value
	 *		, "lokasiLaka" => value
	 *)
	 *   $uri string
	 */	
	function generateNoSEP($params = array(), $uri = "SEP/insert") {
		$params["lakaLantas"] = isset($params["lakaLantas"]) ? strval($params["lakaLantas"]) : "2";
		$params["lokasiLaka"] = isset($params["lokasiLaka"]) ? $params["lokasiLaka"] : "-";		
		$params["catatan"] = isset($params["catatan"]) ? $params["catatan"] : "-";
		
		/* request data peserta di bpjs */
		$result = $this->cariPesertaDgnNoKartuBPJS(array(
			"noKartu" => $params["noKartu"]
			, "norm" => $params["norm"]
		));
				
		$peserta = null;
		
		$metaData = array(
			'message' => 200,
			'code' => 200,
			'requestId'=> $this->config["koders"]
		);
		
		if($result->metadata->code == 200 && (trim($result->metadata->message) == '200' || trim($result->metadata->message) == 'OK')) {
			$peserta = $result->response->peserta;
		}
		
		if($peserta == null) {
			try {
				$rPeserta = $this->peserta->load(array("noKartu" => $params["noKartu"]));
				if(count($rPeserta) > 0) {
					$rPeserta = $rPeserta[0];
					$peserta = array(
						'noKartu' => $rPeserta['noKartu'],
						'nik' => $rPeserta['nik'],
						'norm' => $rPeserta['norm'],
						'nama' => $rPeserta['nama'],
						'pisa' => $rPeserta['pisa'],
						'sex' => $rPeserta['sex'],
						'tglLahir' => $rPeserta['tglLahir'],
						'tglCetakKartu' => $rPeserta['tglCetakKartu'],
						'provUmum' => array(
							'kdProvider' => $rPeserta['kdProvider'],
							'nmProvider' => $rPeserta['nmProvider'],
							'kdCabang' => $rPeserta['kdCabang'],
							'nmCabang' => $rPeserta['nmCabang']
						),
						'jenisPeserta' => array(
							'kdJenisPeserta' => $rPeserta['kdJenisPeserta'],
							'nmJenisPeserta' => $rPeserta['nmJenisPeserta']
						),
						'kelasTanggungan' => array(
							'kdKelas' => $rPeserta['kdKelas'],
							'nmKelas' => $rPeserta['nmKelas']
						),
						"informasi" => array(
							"dinsos" => $rPeserta['dinsos'],
							"iuran" => $rPeserta['iuran'],
							"noSKTM" => $rPeserta['noSKTM'],
							"prolanisPRB" => $rPeserta['prolanisPRB']
						),
						"statusPeserta" => array(
							"keterangan" => $rPeserta['ketStatusPeserta'],
							"kode" => $rPeserta['kdStatusPeserta']
						),
						"tglTAT" => $rPeserta['tglTAT'],
						"tglTMT" => $rPeserta['tglTMT'],
						"umur" => array(
							"umurSaatPelayanan" => $rPeserta['umurSaatPelayanan'],
							"umurSekarang" => $rPeserta['umurSekarang']
						)
					);
					
					$peserta = json_decode(json_encode($peserta));
				} else {
					return $result;
				}
			} catch(\Exception $e) {
				$metaData['message'] = "SIMRSInfo::Error Query Select";
				$metaData['code'] = 500;
			}
		}
								  
		if($metaData['code'] == 200) {
			$kunjungan = null;
			$params["ppkPelayanan"] = $this->config["koders"];
			$params["klsRawat"] = $peserta->kelasTanggungan->kdKelas;
			$params["noMr"] = strval($params["norm"]);
			$params["jnsPelayanan"] = strval($params["jnsPelayanan"]);
			$paket = $params;
			unset($paket["create"]);
			unset($paket["ip"]);
			unset($paket["norm"]);
			
			$data = json_encode(array(
				"request" => array(
					"t_sep" => $paket
				)
			));
			
			$result = json_decode($this->sendRequest($uri, "POST", $data, "Application/x-www-form-urlencoded"));
			
			if($result) {
				if($result->metadata->code == 200 && (trim($result->metadata->message) == '200' || trim($result->metadata->message) == 'OK') && $result->response != null) {
					$noSEP = $result->response;
					$ppkPelayanan = $this->config["koders"];
					
					$params["noSEP"] = $noSEP;
					$params["tglSEP"] = $params["tglSep"];
					$params["ppkPelayanan"] = $ppkPelayanan;
					$params["jenisPelayanan"] = $params["jnsPelayanan"];
					$params["klsRawat"] = $peserta->kelasTanggungan->kdKelas;
					
					$rKunjungan = $this->kunjungan->load(array(
						"noKartu" => $params["noKartu"]
						, "noSEP" => $params["noSEP"]
					));
					
					$this->kunjungan->simpan($params, count($rKunjungan) == 0);						
					$kunjungan = $params;
					unset($kunjungan["jenisPelayanan"]);
					unset($kunjungan["ip"]);
					unset($kunjungan["create"]);
				   
					$metaData = array(
						'message' => 200,
						'code' => 200,
						'requestId'=> $this->config["koders"]
					); 
				} else {
					$metaData['message'] = $result == null ? "SIMRSInfo::Error" : $result->metadata->message." ".(is_string($result->response) ? $result->response : "");
					$metaData['code'] = $result == null ? 400 : $result->metadata->code;
				}
			} else {
				return json_decode(json_encode(array(					
					'metadata' => array(
						'message' => "SIMRSInfo::Error Request Service - generate Nomor SEP<br/>".$this::RESULT_IS_NULL,
						'code' => 502,
						'requestId'=> $this->config["koders"]
					)
				)));
			}
		}
					
		return json_decode(json_encode(array(
			'response' => array(
				'peserta' => $peserta,
				'kunjungan' => $kunjungan
			),
			'metadata' => $metaData
		)));
	}
	
	/* SEP>>>Update SEP
	 * Update SEP
	 * @parameter 
	 *   $params array(		
	 *		"noSep" => value
	 *		, "noKartu" => value
	 *		, "tglSep" => value
	 *		, "tglRujukan" => value
	 *		, "noRujukan" => value
	 *		, "ppkRujukan" => value
	 *		, "jnsPelayanan" => value
	 *		, "catatan" => value
	 *		, "diagAwal" => value
	 *		, "poliTujuan" => value
	 *		, "user" => value
	 *		, "norm" => value
	 *		, "lakaLantas" => value
	 *		, "lokasiLaka" => value
	 *)
	 *   $uri string
	 */	
	function updateSEP($params, $uri = "Sep/Update") {
		$params["lakaLantas"] = isset($params["lakaLantas"]) ? strval($params["lakaLantas"]) : "2";
		$params["lokasiLaka"] = isset($params["lokasiLaka"]) ? $params["lokasiLaka"] : "-";		
		$params["catatan"] = isset($params["catatan"]) ? $params["catatan"] : "-";
		
		/* request data peserta di bpjs */
		$result = $this->cariPesertaDgnNoKartuBPJS(array(
			"noKartu" => $params["noKartu"]
			, "norm" => $params["norm"]
		));
				
		$peserta = null;
	   
		$metaData = array(
			'message' => 200,
			'code' => 200,
			'requestId'=> $this->config["koders"]
		);
		
		if($result->metadata->code == 200 && (trim($result->metadata->message) == '200' || trim($result->metadata->message) == 'OK')) {
			$peserta = $result->response->peserta;
		}
		
		if($peserta == null) {
			try {
				$rPeserta = $this->peserta->load(array("noKartu" => $params["noKartu"]));
				if(count($rPeserta) > 0) {
					$rPeserta = $rPeserta[0];
					$peserta = array(
						'noKartu' => $rPeserta['noKartu'],
						'nik' => $rPeserta['nik'],
						'norm' => $rPeserta['norm'],
						'nama' => $rPeserta['nama'],
						'pisa' => $rPeserta['pisa'],
						'sex' => $rPeserta['sex'],
						'tglLahir' => $rPeserta['tglLahir'],
						'tglCetakKartu' => $rPeserta['tglCetakKartu'],
						'provUmum' => array(
							'kdProvider' => $rPeserta['kdProvider'],
							'nmProvider' => $rPeserta['nmProvider'],
							'kdCabang' => $rPeserta['kdCabang'],
							'nmCabang' => $rPeserta['nmCabang']
						),
						'jenisPeserta' => array(
							'kdJenisPeserta' => $rPeserta['kdJenisPeserta'],
							'nmJenisPeserta' => $rPeserta['nmJenisPeserta']
						),
						'kelasTanggungan' => array(
							'kdKelas' => $rPeserta['kdKelas'],
							'nmKelas' => $rPeserta['nmKelas']
						),
						"informasi" => array(
							"dinsos" => $rPeserta['dinsos'],
							"iuran" => $rPeserta['iuran'],
							"noSKTM" => $rPeserta['noSKTM'],
							"prolanisPRB" => $rPeserta['prolanisPRB']
						),
						"statusPeserta" => array(
							"keterangan" => $rPeserta['ketStatusPeserta'],
							"kode" => $rPeserta['kdStatusPeserta']
						),
						"tglTAT" => $rPeserta['tglTAT'],
						"tglTMT" => $rPeserta['tglTMT'],
						"umur" => array(
							"umurSaatPelayanan" => $rPeserta['umurSaatPelayanan'],
							"umurSekarang" => $rPeserta['umurSekarang']
						)
					);
					
					$peserta = json_decode(json_encode($peserta));
				} else {
					return $result;
				}
			} catch(\Exception $e) {
				$metaData['message'] = "SIMRSInfo::Error Query Select";
				$metaData['code'] = 500;
			}
		}
								  
		if($metaData['code'] == 200) {
			$kunjungan = null;
			$params["ppkPelayanan"] = $this->config["koders"];
			$params["klsRawat"] = $peserta->kelasTanggungan->kdKelas;
			$params["noMr"] = strval($params["norm"]);
			$params["jnsPelayanan"] = strval($params["jnsPelayanan"]);
			$paket = $params;
			unset($paket["norm"]);
			
			$data = json_encode(array(
				"request" => array(
					"t_sep" => $paket
				)
			));
			
			$result = json_decode($this->sendRequest($uri, "PUT", $data, "Application/x-www-form-urlencoded"));
			
			if($result) {
				if($result->metadata->code == 200 && (trim($result->metadata->message) == '200' || trim($result->metadata->message) == 'OK') && $result->response != null) {
					$ppkPelayanan = $this->config["koders"];
					
					$params["noSEP"] = $params["noSep"];
					$params["tglSEP"] = $params["tglSep"];
					$params["ppkPelayanan"] = $ppkPelayanan;
					$params["jenisPelayanan"] = $params["jnsPelayanan"];
					$params["klsRawat"] = $peserta->kelasTanggungan->kdKelas;
					
					$rKunjungan = $this->kunjungan->load(array(
						"noKartu" => $params["noKartu"]
						, "noSEP" => $params["noSEP"]
					));
					
					$this->kunjungan->simpan($params, count($rKunjungan) == 0);						
					$kunjungan = $params;
					unset($kunjungan["jenisPelayanan"]);
				   
					$metaData = array(
						'message' => 200,
						'code' => 200,
						'requestId'=> $this->config["koders"]
					); 
				} else {
					$metaData['message'] = $result == null ? "SIMRSInfo::Error" : $result->metadata->message." ".(is_string($result->response) ? $result->response : "");
					$metaData['code'] = $result == null ? 400 : $result->metadata->code;					
				}
			} else {
				return json_decode(json_encode(array(					
					'metadata' => array(
						'message' => "SIMRSInfo::Error Request Service - Update SEP<br/>".$this::RESULT_IS_NULL,
						'code' => 502,
						'requestId'=> $this->config["koders"]
					)
				)));
			}
		}
					
		return json_decode(json_encode(array(
			'response' => array(
				'peserta' => $peserta,
				'kunjungan' => $kunjungan
			),
			'metadata' => $metaData
		)));
	}
	
	/* SEP>>>Update Tanggal Pulang
	 * updateTanggalPulang
	 * Terjadi penolakan saat pembuatan SEP jika sistem mengidentifikasi bahwa pasien masih dalam status menginap. Untuk mengisi tanggal pulang pada sistem BPJS/SEP, hanya dapat dilakukan dengan menerima file hasil entrian dari sistem INA-CBGs (kemenkes). Namun hal ini biasa dilakukan/diberikan oleh pihak RS kepada pihak BPJS pada beberapa hari kemudian.
	 * Untuk mengantisipasi kasus penolakan terhadap pasien, dibutuhkan suatu sistem yang dapat meng-update data pasien pada BPJS melalui sistem RS. Disinilah fungsi ini berguna untuk meng-update tanggal pulang pasien pada data BPJS yang mana saat sistem RS melakukan update tanggal pulang pada sistem RS, sekaligus mengakses WebService ini agar data pasien terupdate pada server BPJS.        
	 * @parameter 
	 *   $params array(
	 *		"noSEP" => value
	 *		, "tglPlg" => value | format Y-m-d H:i:s	
	 *)
	 *   $uri string
	 */
	function updateTanggalPulang($params, $uri = "Sep/updtglplg") {		
		$data = json_encode(array(
			"request" => array(
				"t_sep" => array(
					"noSep" => $params["noSEP"]
					, "tglPlg" => $params["tglPlg"]
					, "ppkPelayanan" => $this->config["koders"]
				)
			)
		));
		
		$result =  json_decode($this->sendRequest($uri, "PUT", $data, "application/x-www-form-urlencoded"));		
		if($result) { 
			$message = trim($result->metadata->message);
			$result->metadata->requestId = $this->config["koders"];
			
			if($result->metadata->code == 200 && ($message == '200' || $message == 'OK')) {
				$this->kunjungan->simpan($params);
			} else {
				if($result->metadata != null) {
					$message = $result->metadata->code."-".$message;
					$this->kunjungan->simpan(array(
						"noSEP" => $params["noSEP"]
						, "errMsgUptTglPlg" => $message
					));
				}
			}
		} else {
			return json_decode(json_encode(array(					
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - updateTanggalPulang<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		
		return $result;
	}
	
	/* SEP>>>Mapping Transaksi SEP
	 * mappingDataTransaksi
	 * Setelah sistem RS men-generate SEP dan menyimpan transaksi pendaftaran pada sistem RS, maka data masing-masing no transaksi unik disimpan pada 2 sistem (BPJS dan RS). Fungsi ini berguna untuk menyimpan no transaksi tersebut, agar nantinya dapat melakukan audit trail yang lebih efisien
	 * @parameter 
	 *   $params array(
	 *		"noSEP" => value
	 *		, "noTrans" => value
	 *)
	 *   $uri string
	 */
	function mappingDataTransaksi($params, $uri = "SEP/map/trans") {
		$data = json_encode(array(
			"request" => array(
				"t_map_sep" => array(
					"noSep" => $params["noSEP"]
					, "noTrans" => $params["noTrans"]
					, "ppkPelayanan" => $this->config["koders"]
				)
			)
		));
		
		$result = json_decode($this->sendRequest($uri, "POST", $data, "application/x-www-form-urlencoded"));
				
		if($result) {
			$message = trim($result->metadata->message);
			$result->metadata->requestId = $this->config["koders"];
			if($result->metadata->code == 200 && ($message == '200' || $message == 'OK')) {
				$this->kunjungan->simpan($params);
			} else {
				if($result->metadata != null) {
					$message = $result->metadata->code."-".$message;
					$this->kunjungan->simpan(array(
						"noSEP" => $params["noSEP"]
						, "errMsgMapTrx" => $message
					));
				}
			}

		} else {
			return json_decode(json_encode(array(					
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - mappingDataTransaksi<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		
		return $result;
	}
	
	/* SEP>>>Hapus SEP
	 * batalkanNoSEP
	 * Data SEP yang dapat dihapus hanya jika data tersebut belum dibuatkan FPK/tagihan ke Kantor Cabang BPJS setempat
	 * @parameter 
	 *   $params array(
	 *		"noSEP" => value
	 *)
	 *   $uri string
	 */
	function batalkanSEP($params, $uri = "SEP/Delete") {
		$noSEP = $params;
		if(is_array($params)) {
			if(isset($params["noSEP"])) $noSEP = $params["noSEP"];
		}
		$data = json_encode(array(
			"request" => array(
				"t_sep" => array(
					"noSep" => $noSEP
					, "ppkPelayanan" => $this->config["koders"]
				)
			)
		));
		
		$result =  json_decode($this->sendRequest($uri, "DELETE", $data, "application/x-www-form-urlencoded"));		
		if($result) {
			$message = trim($result->metadata->message);
			$result->metadata->requestId = $this->config["koders"];			
			if($result->metadata->code == 200 && ($message == '200' || $message == 'OK')) {
				$this->kunjungan->simpan(array(
					"noSEP" => $noSEP
					, "batalSEP" => 1
				));
			} else {
				if($result->metadata != null) {
					$message = $result->metadata->code."-".$message;
					$this->kunjungan->simpan(array(
						"noSEP" => $noSEP
						, "errMsgBatalSEP" => $message
					));
				}
			}
		} else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Hapus SEP<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		
		return $result;
	}
	
	/* SEP>>Detail SEP Peserta
	 * Mencari detail SEP / Cek SEP
	 * Melihat detail keterangan dari SEP.
	 * @parameter 
	 *   $params array(
	 *		"noSEP" => value
	 *)
	 *   $uri string
	 */
	function cekSEP($params, $uri = "SEP/") {		
		return parent::cekSEP($params, $uri);
	}
	
	/* SEP>>>Data Riwayat Pelayanan Peserta
	 * Mencari Data Riwayat Pelayanan Peserta
	 * @parameter 
	 *   $params array(
	 *		"noSEP" => value
	 *)
	 *   $uri string
	 */
	function riwayatPelayananPeserta($params, $uri = "sep/peserta/") {		
		$noKartu = $params;
		if(is_array($params)) {
			if(isset($params["noKartu"])) $noKartu = $params["noKartu"];
		}
		//$result = json_decode($this->sendRequest($uri.$noKartu, "GET", "", "application/x-www-form-urlencoded"));
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
	 *		"cari" => value | 0 (tanggal masuk), 1 (tanggal keluar)
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
			if(isset($params["status"])) $status = $params["status"];
		}
		$req = $uri
				."tglMasuk/$tglMasuk/tglKeluar/$tglKeluar/KlsRawat/$kelasRawat/Kasus/$jnsPelayanan/Cari/"
				."$cari/status/$status";
		$result = json_decode($this->sendRequest($req));
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
	
	/* SEP>>>Integrasi SEP dengan Inacbg
	 * Integrasi SEP dengan Inacbg
	 * @parameter 
	 *   $params array(
	 *		"noSEP" => value
	 *)
	 *   $uri string
	 */
	function inacbg($params, $uri = "sep/cbg/") {
		$noSEP = $params;
		if(is_array($params)) {
			if(isset($params["noSEP"])) $noSEP = $params["noSEP"];
		}
		$result = json_decode($this->sendRequest($uri.$noSEP));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(			
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Integrasi SEP dengan Inacbg<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* Referensi>>>Poli
	 * ambil daftar poli bpjs
	 */
	function poli($uri = "poli/ref/poli") {
		$result = json_decode($this->sendRequest($uri));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(			
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - ambil daftar poli bpjs <br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* Referensi>>>Diagnosa
	 * Pencarian data diagnosa
	 * @parameter 
	 *   $params array(
	 *		"query" => value | kode atau nama diagnosa
	 *)
	 *   $uri string
	 */
	function diagnosa($params, $uri = "diagnosa/ref/diagnosa/") {
		$query = $params;
		if(is_array($params)) {
			if(isset($params["query"])) $query = $params["query"];
		}
		$result = json_decode($this->sendRequest($uri.$query));
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
	
	/* Referensi>>>Fasilitas Kesehatan
	 * Pencarian data fasilitas kesehatan
	 * @parameter 
	 *   $params array(
	 *		"nama" => value | kode atau nama faskes
	 *		"start" => value | awal mulai row
	 *		"limit" => value | jumlah record yg akan ditampilkan
	 *)
	 *   $uri string
	 */
	function faskes($params, $uri = "provider/ref/provider/query") {
		$query = "";
		if(is_array($params)) {
			if(count($params) > 0) $query = "?".http_build_query($params);
		}
		$result = json_decode($this->sendRequest($uri.$query));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(			
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Pencarian data fasilitas kesehatan<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
}