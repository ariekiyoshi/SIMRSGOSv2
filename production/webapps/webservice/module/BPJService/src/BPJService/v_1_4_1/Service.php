<?php
/**
 * BPJService
 * @author hariansyah
 * 
 */
 
namespace BPJService\v_1_4_1;
	
use BPJService\BaseService;

class Service extends BaseService 
	/* 
	 * cari peserta berdasarkan Nomor Kartu BPJS 
	 * @parameter 
	 *   $params string | array("noKartu" => value, "norm" => value)
	 *   $uri string
	 */
	function cariPesertaDgnNoKartuBPJS($params, $uri = "peserta/") {
		return parent::cariPesertaDgnNoKartuBPJS($params, $uri);
	}
	
	/*
	 * cari perserta berdasarkan Nomor Induk Kependudukan (e-KTP)
	 * @parameter 
	 *   $params string | array("nik" => value, "norm" => value)
	 *   $uri string
	 */
	function cariPesertaDgnNIK($params, $uri = "peserta/nik/") {
		return parent::cariPesertaDgnNIK($params, $uri);
	}
	
	/*
	 * cari data rujukan berdasarkan Nomor rujukan
	 * @parameter 
	 *   $params string | array("nomor" => value)
	 *   $uri string
	 */
	function cariRujukanDgnNoRujukan($params, $uri = "rujukan/") {
		return parent::cariPesertaDgnNIK($params, $uri);
	}
	
	/*
	 * cari data rujukan berdasarkan Nomor Kartu BPJS
	 * @parameter 
	 *   $params string | array("noKartu" => value)
	 *   $uri string
	 */
	function cariRujukanDgnNoKartuBPJS($params, $uri = "rujukan/peserta/") {
		return parent::cariPesertaDgnNIK($params, $uri);
	}
	
	/*
	 * cari daftar peserta yang rujukan berdasarkan tanggal rujukan
	 * format tanggal Y-m-d
	 */
	function cariDaftarPersertaRujukanDgnTglRujukan($tanggal, $start, $limit) {
		$result = json_decode($this->sendRequest("rujukan/tglrujuk/".$tanggal."/query?start=".$start."&limit=".$limit));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(					
				'metaData' => array(
					'message' => "SIMRSInfo::Error Request Service - cari daftar peserta yang rujukan berdasarkan tanggal rujukan<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/*
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
	 *)
	 *   $uri string
	 */	
	function generateNoSEP($params = array(), $uri = "sep/") {
		$params["lakaLantas"] = isset($params["lakaLantas"]) ? $params["lakaLantas"] : 2;
		$params["lokasiLaka"] = isset($params["lokasiLaka"]) ? $params["lokasiLaka"] : "";		
		
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
						'noMr' => $rPeserta['norm'],
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
			$data = "<request>
				<data>
				  <t_sep>
						<noKartu>".$params["noKartu"]."</noKartu>
						<tglSep>".$params["tglSep"]."</tglSep>
						<tglRujukan>".$params["tglRujukan"]."</tglRujukan>
						<noRujukan>".$params["noRujukan"]."</noRujukan>
						<ppkRujukan>".$params["ppkRujukan"]."</ppkRujukan>
						<ppkPelayanan>".$this->config["koders"]."</ppkPelayanan>
						<jnsPelayanan>".$params["jnsPelayanan"]."</jnsPelayanan>
						<catatan>".$params["catatan"]."</catatan>
						<diagAwal>".$params["diagAwal"]."</diagAwal>
						<poliTujuan>".$params["poliTujuan"]."</poliTujuan>
						<klsRawat>".$peserta->kelasTanggungan->kdKelas."</klsRawat>
						<lakaLantas>".$params["lakaLantas"]."</lakaLantas>
						<lokasiLaka>".$params["lokasiLaka"]."</lokasiLaka>
						<user>".$params["user"]."</user>
						<noMr>".$params["norm"]."</noMr>
				  </t_sep>
				</data>
				</request>";

			$result = json_decode($this->sendRequest($uri, "POST", $data, "application/xml"));				
			if($result) {
				if($result->metadata->code == 200 && (trim($result->metadata->message) == '200' || trim($result->metadata->message) == 'OK') && $result->response != null) {
					$noSEP = $result->response;
					$ppkPelayanan = $this->config["koders"];
					
					$params["noSEP"] = $noSEP;
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
	
	/*
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
	function updateTanggalPulang($params, $uri = "sep/updtglplg/") {
		$data = "<request>
			<data>
			  <t_sep>
					<noSep>".$params["noSEP"]."</noSep>
					<tglPlg>".$params["tglPlg"]."</tglPlg>
					<ppkPelayanan>".$this->config["koders"]."</ppkPelayanan>
			  </t_sep>
			</data>
			</request>";
		
		$result =  json_decode($this->sendRequest($uri, "PUT", $data, "application/xml"));		
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
	
	/*
	 * mappingDataTransaksi
	 * Setelah sistem RS men-generate SEP dan menyimpan transaksi pendaftaran pada sistem RS, maka data masing-masing no transaksi unik disimpan pada 2 sistem (BPJS dan RS). Fungsi ini berguna untuk menyimpan no transaksi tersebut, agar nantinya dapat melakukan audit trail yang lebih efisien
	 * @parameter 
	 *   $params array(
	 *		"noSEP" => value
	 *		, "noTrans" => value
	 *)
	 *   $uri string
	 */
	function mappingDataTransaksi($params, $uri = "sep/map/trans/") {
		$data = "<request>
			<data>
				<t_map_sep>
					<noSep>".$params["noSEP"]."</noSep>
					<noTrans>".$params["noTrans"]."</noTrans>
					<ppkPelayanan>".$this->config["koders"]."</ppkPelayanan>
				</t_map_sep>
			</data>
			</request>";
		
		$result = json_decode($this->sendRequest($uri, "POST", $data, "application/xml"));
				
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
	
	/*
	 * batalkanNoSEP
	 * Data SEP yang dapat dihapus hanya jika data tersebut belum dibuatkan FPK/tagihan ke Kantor Cabang BPJS setempat
	 * @parameter 
	 *   $params array(
	 *		"noSEP" => value
	 *)
	 *   $uri string
	 */
	function batalkanSEP($params, $uri = "sep/") {
		$noSEP = $params;
		if(is_array($params)) {
			if(isset($params["noSEP"])) $noSEP = $params["noSEP"];
		}
		$data = "<request>
			<data>
			  <t_sep>
					<noSep>$noSEP</noSep>
					<ppkPelayanan>".$this->config["koders"]."</ppkPelayanan>
			  </t_sep>
			</data>
			</request>";
		
		$result =  json_decode($this->sendRequest($uri, "DELETE", $data, "application/xml"));		
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
					'message' => "SIMRSInfo::Error Request Service - batalkanNoSEP<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		
		return $result;
	}
	
	/*
	 * Mencari detail SEP / Cek SEP
	 * Melihat detail keterangan dari SEP.
	 * @parameter
	 *   $params array(
	 *		"noSEP" => value
	 *)
	 *   $uri string
	 */
	function cekSEP($params, $uri = "sep/") {		
		return parent::cekSEP($params, $uri);
	}
}