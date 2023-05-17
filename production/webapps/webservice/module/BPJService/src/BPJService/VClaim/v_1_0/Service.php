<?php
/**
 * BPJService
 * @author hariansyah
 * 
 */
 
namespace BPJService\VClaim\v_1_0;

use BPJService\BaseService;
use Zend\Json\Json;

class Service extends BaseService {
	protected function storePeserta($norm, $peserta) {
		if($peserta) {
			$peserta->norm = $norm;
			$rPeserta = $this->peserta->load(array("noKartu" => $peserta->noKartu));
			$data = array(
				"noKartu" => $peserta->noKartu
				, "nik" => $peserta->nik
				, "norm" => $peserta->norm
				, "nama" => $peserta->nama
				, "pisa" => $peserta->pisa
				, "sex" => $peserta->sex
				, "tglLahir" => $peserta->tglLahir
				, "tglCetakKartu" => $peserta->tglCetakKartu
				, "kdProvider" => $peserta->provUmum->kdProvider
				, "nmProvider" => $peserta->provUmum->nmProvider
				//, "kdCabang" => $peserta->provUmum->kdCabang				#REMOVED
				//, "nmCabang" => $peserta->provUmum->nmCabang				#REMOVED
				, "kdJenisPeserta" => $peserta->jenisPeserta->kode 			#CHANGED
				, "nmJenisPeserta" => $peserta->jenisPeserta->keterangan 	#CHANGED
				, "kdKelas" => $peserta->hakKelas->kode 					#CHANGED
				, "nmKelas" => $peserta->hakKelas->keterangan 				#CHANGED
				, "tglTAT" => isset($peserta->tglTAT) ? $peserta->tglTAT : new \Zend\Db\Sql\Expression('NULL')
				, "tglTMT" => isset($peserta->tglTMT) ? $peserta->tglTMT : new \Zend\Db\Sql\Expression('NULL')
				, "kdStatusPeserta" => isset($peserta->statusPeserta) ? $peserta->statusPeserta->kode : new \Zend\Db\Sql\Expression('NULL')
				, "ketStatusPeserta" => isset($peserta->statusPeserta) ? $peserta->statusPeserta->keterangan : new \Zend\Db\Sql\Expression('NULL')
				, "umurSaatPelayanan" => isset($peserta->umur) ? $peserta->umur->umurSaatPelayanan : new \Zend\Db\Sql\Expression('NULL')
				, "umurSekarang" => isset($peserta->umur) ? $peserta->umur->umurSekarang : new \Zend\Db\Sql\Expression('NULL')
				, "dinsos" => isset($peserta->informasi) ? $peserta->informasi->dinsos : new \Zend\Db\Sql\Expression('NULL')
				//, "iuran" => isset($peserta->informasi) ? $peserta->informasi->iuran : new \Zend\Db\Sql\Expression('NULL') #REMOVED
				, "noSKTM" => isset($peserta->informasi) ? $peserta->informasi->noSKTM : new \Zend\Db\Sql\Expression('NULL')
				, "prolanisPRB" => isset($peserta->informasi) ? $peserta->informasi->prolanisPRB : new \Zend\Db\Sql\Expression('NULL')
				, "noTelepon" => isset($peserta->mr) ? $peserta->mr->noTelepon : new \Zend\Db\Sql\Expression('NULL') #ADDED
				, "noAsuransi" => isset($peserta->cob) ? $peserta->cob->noAsuransi : new \Zend\Db\Sql\Expression('NULL') #ADDED
				, "nmAsuransi" => isset($peserta->cob) ? $peserta->cob->nmAsuransi : new \Zend\Db\Sql\Expression('NULL') #ADDED
				, "cobTglTAT" => isset($peserta->cob) ? $peserta->cob->tglTAT : new \Zend\Db\Sql\Expression('NULL') #ADDED
				, "cobTglTMT" => isset($peserta->cob) ? $peserta->cob->tglTMT : new \Zend\Db\Sql\Expression('NULL') #ADDED
			);
			if(count($rPeserta) > 0) {
				if($norm == 0) unset($data["norm"]);
			}
			$this->peserta->simpan($data, count($rPeserta) == 0);			
		}
	}
	/* Peserta>>>No.Kartu BPJS
	 * Pencarian data peserta BPJS Kesehatan
	 * @parameter 
	 *   $params string | array("noKartu" => value, "norm" => value, "tglSEP" => 'yyyy-MM-dd')
	 *   $uri string
	 */
	function cariPesertaDgnNoKartuBPJS($params = "", $uri = "Peserta/nokartu/") {
		$nomor = $params;
		$norm = 0;
		$tgl = date("Y-m-d");		
		if(is_array($params)) {
			if(isset($params["noKartu"])) $nomor = $params["noKartu"];
			if(isset($params["norm"])) $norm = $params["norm"];
			if(isset($params["tglSEP"])) {
				if($params["tglSEP"] != "") $tgl = $params["tglSEP"];
			}
		}
				
		$result = Json::decode($this->sendRequest($uri.$nomor."/tglSEP/".$tgl));
		if($result) {
			$result->metadata->requestId = $this->config["koders"];
			$message = trim($result->metadata->message);
			if($result->metadata->code == 200 && ($message == '200' || $message == 'OK' || $message == 'Sukses')) {
				$peserta = $result->response->peserta;				
				$this->storePeserta($norm, $peserta);
			}
		} else {
			return json_decode(json_encode(array(					
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Pencarian data peserta BPJS Kesehatan<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* Peserta>>>NIK
	 * Pencarian data peserta berdasarkan NIK Kependudukan
	 * @parameter
	 *   $params string | array("nik" => value, "norm" => value, "tglSEP" => 'yyyy-MM-dd')
	 *   $uri string
	 */
	function cariPesertaDgnNIK($params, $uri = "Peserta/nik/") {
		$nomor = $params;
		$norm = 0;
		$tgl = date("Y-m-d");
		if(is_array($params)) {
			if(isset($params["nik"])) $nomor = $params["nik"];
			if(isset($params["norm"])) $norm = $params["norm"];
			if(isset($params["tglSEP"])) {
				if($params["tglSEP"] != "") $tgl = $params["tglSEP"];
			}
		}
		
		$result = Json::decode($this->sendRequest($uri.$nomor."/tglSEP/".$tgl));
		if($result) {
			$result->metadata->requestId = $this->config["koders"];
			$message = trim($result->metadata->message);
			if($result->metadata->code == 200 && ($message == '200' || $message == 'OK' || $message == 'Sukses')) {
				$peserta = $result->response->peserta;				
				$this->storePeserta($norm, $peserta);
			}
		} else {
			return json_decode(json_encode(array(					
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Pencarian data peserta berdasarkan NIK Kependudukan<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
		
	/* SEP>>>Insert SEP
	 * generate Nomor SEP
	 * @parameter 
	 *   $params array(
	 *		"noKartu" => value
	 *		, "tglSep" => value | yyyy-MM-dd
	 *		, "jnsPelayanan" => value
	 *		, "klsRawat" => value												
	 *		, "noMr" => value
	 
	 *		// rujukan
	 *		, "asalRujukan" => value | 1 = Faskes 1, 2 = Faskes 2 (RS)			#ADDED
	 *		, "tglRujukan" => value | yyyy-MM-dd
	 *		, "noRujukan" => value
	 *		, "ppkRujukan" => value
	 
	 *		, "catatan" => value
	 *		, "diagAwal" => value
	 
	 *		// poli
	 *		, "poliTujuan" => value
	 *		, "eksekutif" => value | 0 = Tidak, 1 = Ya							#ADDED
	 
	 *		, "cob" => value | 0 = Tidak, 1 = Ya								#ADDED
	 	 
	 *		// jaminan
	 *		, "lakaLantas" => value | 2 = Tidak, 1 = Ya # Skrg 0 = Tidak, 1 = Ya
	 *		, "penjamin" => value | 1=Jasa raharja PT, 2=BPJS Ketenagakerjaan, 3=TASPEN PT, 4=ASABRI PT} jika lebih dari 1 isi -> 1,2 (pakai delimiter koma)	#ADDED
	 *		, "lokasiLaka" => value
	 
	 *		, "noTelp" => value													#ADDED
	 *		, "user" => value
	 *)
	 *   $uri string
	 */	
	function generateNoSEP($params = array(), $uri = "SEP/insert") {
		$paket = $params;
		$paket["rujukan"] = array(
			"asalRujukan" => "1"
			, "tglRujukan" => ""
			, "noRujukan" => "-"
			, "ppkRujukan" => "-"
		);
		if(isset($paket["asalRujukan"])) {
			$paket["rujukan"]["asalRujukan"] = strval($paket["asalRujukan"]);
			unset($paket["asalRujukan"]);
		}
		if(isset($paket["tglRujukan"])) {
			$paket["rujukan"]["tglRujukan"] = substr($paket["tglRujukan"], 0, 10);
			unset($paket["tglRujukan"]);
		}
		if(isset($paket["noRujukan"])) {
			$paket["rujukan"]["noRujukan"] = $paket["noRujukan"];
			unset($paket["noRujukan"]);
		}
		if(isset($paket["ppkRujukan"])) {
			$paket["rujukan"]["ppkRujukan"] = $paket["ppkRujukan"];
			unset($paket["ppkRujukan"]);
		}
		
		$paket["poli"] = array(
			"tujuan" => ""
			, "eksekutif" => "0"
		);
		
		if(isset($paket["poliTujuan"])) {
			$paket["poli"]["tujuan"] = $paket["poliTujuan"];
			unset($paket["poliTujuan"]);
		}
		if(isset($paket["eksekutif"])) {
			$paket["poli"]["eksekutif"] = strval($paket["eksekutif"]);
			unset($paket["eksekutif"]);
		}
		
		if(isset($paket["cob"])) {
			$paket["cob"] = array(
				"cob" => strval($paket["cob"])
			);
		} else {
			$paket["cob"] = array(
				"cob" => "0"
			);
		}
		
		$paket["jaminan"] = array(
			"lakaLantas" => "0"
			, "penjamin" => "0"
			, "lokasiLaka" => "-"
		);
		if(isset($paket["lakaLantas"])) {
			$paket["jaminan"]["lakaLantas"] = strval($paket["lakaLantas"]) == "2" ? "0" : "1";
			unset($paket["lakaLantas"]);
		}
		if(isset($paket["penjamin"])) {
			$paket["jaminan"]["penjamin"] = strval($paket["penjamin"]);
			unset($paket["penjamin"]);
		}
		if(isset($paket["lokasiLaka"])) {
			$paket["jaminan"]["lokasiLaka"] = $paket["lokasiLaka"];
			unset($paket["lokasiLaka"]);
		}
		
		$paket["catatan"] = isset($paket["catatan"]) ? $paket["catatan"] : "-";
		$paket["noTelp"] = isset($paket["noTelp"]) ? $paket["noTelp"] : "-";
		
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
		
		$message = trim($result->metadata->message);
		if($result->metadata->code == 200 && ($message == '200' || $message == 'OK' || $message == 'Sukses')) {		
			$peserta = $result->response->peserta;
		}
		
		if($peserta == null) {
			try {
				$rPeserta = $this->peserta->load(array("noKartu" => $params["noKartu"]));
				if(count($rPeserta) > 0) {
					$rPeserta = $rPeserta[0];
					$peserta = array(
						"cob" => array (
							"noAsuransi" => $rPeserta['noAsuransi'],
							"nmAsuransi" => $rPeserta['nmAsuransi'],
							"tglTAT" => $rPeserta['tglTAT'],
							"tglTMT" => $rPeserta['cobTglTMT']
						),
						'hakKelas' => array(
							'kode' => $rPeserta['kdKelas'],
							'keterangan' => $rPeserta['nmKelas']
						),
						"informasi" => array(
							"dinsos" => $rPeserta['dinsos'],							
							"noSKTM" => $rPeserta['noSKTM'],
							"prolanisPRB" => $rPeserta['prolanisPRB']
						),
						'jenisPeserta' => array(
							'kode' => $rPeserta['kdJenisPeserta'],
							'keterangan' => $rPeserta['nmJenisPeserta']
						),
						"mr" => array(
							"noMR" => $rPeserta['norm'],
							"noTelepon" => $rPeserta['noTelepon']
						),
						'nama' => $rPeserta['nama'],
						'nik' => $rPeserta['nik'],
						'noKartu' => $rPeserta['noKartu'],
						'pisa' => $rPeserta['pisa'],
						'norm' => $rPeserta['norm'],
						'provUmum' => array(
							'kdProvider' => $rPeserta['kdProvider'],
							'nmProvider' => $rPeserta['nmProvider']							
						),						
						'sex' => $rPeserta['sex'],
						"statusPeserta" => array(
							"keterangan" => $rPeserta['ketStatusPeserta'],
							"kode" => $rPeserta['kdStatusPeserta']
						),
						'tglCetakKartu' => $rPeserta['tglCetakKartu'],
						'tglLahir' => $rPeserta['tglLahir'],
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
			$paket["ppkPelayanan"] = $this->config["koders"];
			if(!isset($paket["klsRawat"])) $paket["klsRawat"] = $peserta->hakKelas->kode;
			else $paket["klsRawat"] = strval($paket["klsRawat"]);
			$paket["noMR"] = strval($params["norm"]);
			$paket["tglSep"] = substr($paket["tglSep"], 0, 10);
			$paket["jnsPelayanan"] = strval($params["jnsPelayanan"]);			
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
				if($result->metadata->code == 200 && (trim($result->metadata->message) == '200' || trim($result->metadata->message) == 'OK' || trim($result->metadata->message) == 'Sukses') && $result->response != null) {
					$noSEP = $result->response->sep->noSep;
					$ppkPelayanan = $this->config["koders"];
					
					$params["noSEP"] = $noSEP;
					$params["tglSEP"] = $params["tglSep"];
					$params["ppkPelayanan"] = $ppkPelayanan;
					$params["jenisPelayanan"] = $params["jnsPelayanan"];
					$params["klsRawat"] = $paket["klsRawat"];
					
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
	 *		, "noKartu" => value												#REMOVED
	 *		, "tglSep" => value | yyyy-MM-dd									#REMOVED
	 *		, "jnsPelayanan" => value											#REMOVED
	 *		, "klsRawat" => value												
	 *		, "noMr" => value
	 
	 *		// rujukan
	 *		, "asalRujukan" => value | 1 = Faskes 1, 2 = Faskes 2 (RS)			#ADDED
	 *		, "tglRujukan" => value | yyyy-MM-dd
	 *		, "noRujukan" => value
	 *		, "ppkRujukan" => value
	 
	 *		, "catatan" => value
	 *		, "diagAwal" => value
	 
	 *		// poli
	 *		, "poliTujuan" => value												#REMOVED
	 *		, "eksekutif" => value | 0 = Tidak, 1 = Ya							#ADDED
	 
	 *		, "cob" => value | 0 = Tidak, 1 = Ya								#ADDED
	 	 
	 *		// jaminan
	 *		, "lakaLantas" => value | 2 = Tidak, 1 = Ya # Skrg 0 = Tidak, 1 = Ya
	 *		, "penjamin" => value | 1=Jasa raharja PT, 2=BPJS Ketenagakerjaan, 3=TASPEN PT, 4=ASABRI PT} jika lebih dari 1 isi -> 1,2 (pakai delimiter koma)	#ADDED
	 *		, "lokasiLaka" => value
	 
	 *		, "noTelp" => value													#ADDED
	 *		, "user" => value
	 *)
	 *   $uri string
	 */	
	function updateSEP($params, $uri = "Sep/Update") {
		$paket = $params;
		$paket["rujukan"] = array(
			"asalRujukan" => "1"
			, "tglRujukan" => ""
			, "noRujukan" => "-"
			, "ppkRujukan" => "-"
		);
		if(isset($paket["asalRujukan"])) {
			$paket["rujukan"]["asalRujukan"] = strval($paket["asalRujukan"]);
			unset($paket["asalRujukan"]);
		}
		if(isset($paket["tglRujukan"])) {
			$paket["rujukan"]["tglRujukan"] = substr($paket["tglRujukan"], 0, 10);
			unset($paket["tglRujukan"]);
		}
		if(isset($paket["noRujukan"])) {
			$paket["rujukan"]["noRujukan"] = $paket["noRujukan"];
			unset($paket["noRujukan"]);
		}
		if(isset($paket["ppkRujukan"])) {
			$paket["rujukan"]["ppkRujukan"] = $paket["ppkRujukan"];
			unset($paket["ppkRujukan"]);
		}
		
		$paket["poli"] = array(
			//"tujuan" => "", 
			"eksekutif" => "0"
		);
		/*if(isset($paket["poliTujuan"])) {
			$paket["poli"]["tujuan"] = $paket["poliTujuan"];
			unset($paket["poliTujuan"]);
		}*/
		if(isset($paket["eksekutif"])) {
			$paket["poli"]["eksekutif"] = strval($paket["eksekutif"]);
			unset($paket["eksekutif"]);
		}
						
		if(isset($paket["cob"])) {
			$paket["cob"] = array(
				"cob" => strval($paket["cob"])
			);
		} else {
			$paket["cob"] = array(
				"cob" => "0"
			);
		}
		
		$paket["jaminan"] = array(
			"lakaLantas" => "0"
			, "penjamin" => "0"
			, "lokasiLaka" => "-"
		);
		if(isset($paket["lakaLantas"])) {
			$paket["jaminan"]["lakaLantas"] = strval($paket["lakaLantas"]) == "2" ? "0" : "1";
			unset($paket["lakaLantas"]);
		}
		if(isset($paket["penjamin"])) {
			$paket["jaminan"]["penjamin"] = strval($paket["penjamin"]);
			unset($paket["penjamin"]);
		}
		if(isset($paket["lokasiLaka"])) {
			$paket["jaminan"]["lokasiLaka"] = $paket["lokasiLaka"];
			unset($paket["lokasiLaka"]);
		}
		
		$paket["catatan"] = isset($paket["catatan"]) ? $paket["catatan"] : "-";
		$paket["noTelp"] = isset($paket["noTelp"]) ? $paket["noTelp"] : "-";
		
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
		
		$message = trim($result->metadata->message);
		if($result->metadata->code == 200 && ($message == '200' || $message == 'OK' || $message == 'Sukses')) {
			$peserta = $result->response->peserta;
		}
		
		if($peserta == null) {
			try {
				$rPeserta = $this->peserta->load(array("noKartu" => $params["noKartu"]));
				if(count($rPeserta) > 0) {
					$rPeserta = $rPeserta[0];
					$peserta = array(
						"cob" => array (
							"noAsuransi" => $rPeserta['noAsuransi'],
							"nmAsuransi" => $rPeserta['nmAsuransi'],
							"tglTAT" => $rPeserta['tglTAT'],
							"tglTMT" => $rPeserta['cobTglTMT']
						),
						'hakKelas' => array(
							'kode' => $rPeserta['kdKelas'],
							'keterangan' => $rPeserta['nmKelas']
						),
						"informasi" => array(
							"dinsos" => $rPeserta['dinsos'],							
							"noSKTM" => $rPeserta['noSKTM'],
							"prolanisPRB" => $rPeserta['prolanisPRB']
						),
						'jenisPeserta' => array(
							'kode' => $rPeserta['kdJenisPeserta'],
							'keterangan' => $rPeserta['nmJenisPeserta']
						),
						"mr" => array(
							"noMR" => $rPeserta['norm'],
							"noTelepon" => $rPeserta['noTelepon']
						),
						'nama' => $rPeserta['nama'],
						'nik' => $rPeserta['nik'],
						'noKartu' => $rPeserta['noKartu'],
						'pisa' => $rPeserta['pisa'],
						'norm' => $rPeserta['norm'],
						'provUmum' => array(
							'kdProvider' => $rPeserta['kdProvider'],
							'nmProvider' => $rPeserta['nmProvider']							
						),						
						'sex' => $rPeserta['sex'],
						"statusPeserta" => array(
							"keterangan" => $rPeserta['ketStatusPeserta'],
							"kode" => $rPeserta['kdStatusPeserta']
						),
						'tglCetakKartu' => $rPeserta['tglCetakKartu'],
						'tglLahir' => $rPeserta['tglLahir'],
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
			#$paket["ppkPelayanan"] = $this->config["koders"];
			if(!isset($paket["klsRawat"])) $paket["klsRawat"] = $peserta->hakKelas->kode;
			else $paket["klsRawat"] = strval($paket["klsRawat"]);
			$paket["noMR"] = strval($params["norm"]);			
			#$paket["jnsPelayanan"] = strval($params["jnsPelayanan"]);			
			unset($paket["create"]);
			unset($paket["ip"]);
			unset($paket["norm"]);
			unset($paket["noKartu"]);
			unset($paket["tglSep"]);
			unset($paket["poliTujuan"]);
			unset($paket["jnsPelayanan"]);
			
			$data = json_encode(array(
				"request" => array(
					"t_sep" => $paket
				)
			));
			
			$result = json_decode($this->sendRequest($uri, "PUT", $data, "Application/x-www-form-urlencoded"));
			
			if($result) {
				if($result->metadata->code == 200 && (trim($result->metadata->message) == '200' || trim($result->metadata->message) == 'OK' || trim($result->metadata->message) == 'Sukses') && $result->response != null) {
					$ppkPelayanan = $this->config["koders"];
					
					$params["noSEP"] = $params["noSep"];
					#$params["tglSEP"] = $params["tglSep"];
					#$params["ppkPelayanan"] = $ppkPelayanan;
					#$params["jenisPelayanan"] = $params["jnsPelayanan"];
					$params["klsRawat"] = $paket["klsRawat"];
					
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
					, "tglPulang" => substr($params["tglPlg"], 0, 10)
					, "user" => $params["user"]
				)
			)
		));
		
		$result =  json_decode($this->sendRequest($uri, "PUT", $data, "application/x-www-form-urlencoded"));		
		if($result) { 
			$message = trim($result->metadata->message);
			$result->metadata->requestId = $this->config["koders"];
			
			if($result->metadata->code == 200 && ($message == '200' || $message == 'OK' || $message == 'Sukses')) {
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
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Mapping Transaksi SEP tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
	}
	
	/* SEP>>>Hapus SEP
	 * batalkanNoSEP
	 * Data SEP yang dapat dihapus hanya jika data tersebut belum dibuatkan FPK/tagihan ke Kantor Cabang BPJS setempat
	 * @parameter 
	 *   $params array(
	 *		"noSEP" => value
	 *		"user" => value
	 *)
	 *   $uri string
	 */
	function batalkanSEP($params, $uri = "SEP/Delete") {
		$noSEP = $params;
		$user = "";
		if(is_array($params)) {
			if(isset($params["noSEP"])) $noSEP = $params["noSEP"];
			if(isset($params["user"])) $user = $params["user"];
		}
		$data = json_encode(array(
			"request" => array(
				"t_sep" => array(
					"noSep" => $noSEP
					, "user" => $user
				)
			)
		));
		
		$result =  json_decode($this->sendRequest($uri, "DELETE", $data, "application/x-www-form-urlencoded"));		
		if($result) {
			$message = trim($result->metadata->message);
			$result->metadata->requestId = $this->config["koders"];			
			if($result->metadata->code == 200 && ($message == '200' || $message == 'OK' || $message == 'Sukses')) {
				$this->kunjungan->simpan(array(
					"noSEP" => $noSEP
					, "batalSEP" => 1
					, "user_batal" => $user
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
	
	/* SEP>>Aproval Pengajuan SEP
	 * Pengajuan SEP
	 * @parameter 
	 *   $params array(
	 *		"noKartu" => value,
	 *		"tglSep" => value,
	 *		"jnsPelayanan" => value,
	 *		"keterangan" => value,
	 *		"user" => value,
	 *)
	 *   $uri string
	 */
	function pengajuanSEP($params, $uri = "Sep/pengajuanSEP") {
		if(isset($params["jnsPelayanan"])) $params["jnsPelayanan"] = strval($params["jnsPelayanan"]);
		$data = json_encode(array(
			"request" => array(
				"t_sep" => $params
			)
		));
		
		$result =  json_decode($this->sendRequest($uri, "POST", $data, "application/x-www-form-urlencoded"));		
		if($result) {
			$message = trim($result->metadata->message);
			$result->metadata->requestId = $this->config["koders"];			
			if($result->metadata->code == 200 && ($message == '200' || $message == 'OK' || $message == 'Sukses')) {
				$params["tgl"] = new \Zend\Db\Sql\Expression('NOW()');
				
				$rPengajuan = $this->pengajuan->load(array(
					"noKartu" => $params["noKartu"]
					, "tglSep" => $params["tglSep"]
					, "jnsPelayanan" => $params["jnsPelayanan"]
				));
				
				$this->pengajuan->simpan($params, count($rPengajuan) == 0);
			} else {
				if($result->metadata != null) {
					$message = $result->metadata->code."-".$message;
				}
			}
		} else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Pengajuan SEP<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		
		return $result;
	}
	
	/* SEP>>Aproval Pengajuan SEP
	 * Aproval Pengajuan SEP
	 * @parameter 
	 *   $params array(
	 *		"noKartu" => value,
	 *		"tglSep" => value,
	 *		"jnsPelayanan" => value,
	 *		"keterangan" => value,
	 *		"user" => value,
	 *)
	 *   $uri string
	 */
	function aprovalPengajuanSEP($params, $uri = "Sep/aprovalSEP") {
		if(isset($params["jnsPelayanan"])) $params["jnsPelayanan"] = strval($params["jnsPelayanan"]);
		$data = json_encode(array(
			"request" => array(
				"t_sep" => $params
			)
		));
		
		$result =  json_decode($this->sendRequest($uri, "POST", $data, "application/x-www-form-urlencoded"));		
		if($result) {
			$message = trim($result->metadata->message);
			$result->metadata->requestId = $this->config["koders"];			
			if($result->metadata->code == 200 && ($message == '200' || $message == 'OK' || $message == 'Sukses')) {
				$params["tglAprove"] = new \Zend\Db\Sql\Expression('NOW()');
				if(isset($params["user"])) {
					$params["userAprove"] = $params["user"];
					$params["status"] = 2;
					unset($params["user"]);
				}					
				$this->pengajuan->simpan($params);
			} else {
				if($result->metadata != null) {
					$message = $result->metadata->code."-".$message;
				}
			}
		} else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Aproval Pengajuan SEP<br/>".$this::RESULT_IS_NULL,
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
	 *		"noSEP" => value
	 *)
	 *   $uri string
	 */
	function riwayatPelayananPeserta($params, $uri = "sep/peserta/") {		
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Data Riwayat Pelayanan Peserta tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
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
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Monitoring Verifikasi Klaim tidak disupport / pindah ke katalog Monitoring<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
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
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Data Kunjungan Peserta tidak disupport / pindah ke katalog Monitoring<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
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
				if($r1->response->rujukan) {					
					$tgl = $r1->response->rujukan->tglKunjungan;
				}
			}			
		}
		$r2 = parent::cariRujukanDgnNoRujukan($params, $uri."RS/");
		if($tgl) {
			if($r2) {			
				if($r2->response) {
					if($r2->response->rujukan) {
						$tgl1 = $r2->response->rujukan->tglKunjungan;
						if($tgl1 > $tgl) $r1 = $r2;
					}
				}
			}
		} else $r1 = $r2;
		
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
				if($r1->response->rujukan) {
					$tgl = $r1->response->rujukan->tglKunjungan;
				}
			}			
		}
		$r2 = parent::cariRujukanDgnNoKartuBPJS($params, $uri."RS/Peserta/");
		if($tgl) {
			if($r2) {
				if($r2->response) {
					if($r2->response->rujukan) {
						$tgl1 = $r2->response->rujukan->tglKunjungan;
						if($tgl1 > $tgl) $r1 = $r2;
					}
				}
			}
		} else $r1 = $r2;
		
		return $r1;
	}
	
	/* Rujukan>>>Insert Rujukan
	 * @parameter 
	 *   $params array(
	 *		"noSep" => value,
	 *		"tglRujukan" => value, | yyyy-MM-dd
	 *		"ppkDirujuk" => value
	 *		"jnsPelayanan" => value, | 1 (Rawat Inap), 2 (Rawat Jalan)
	 *		"catatan" => value,
	 *		"diagRujukan" => value, | kode diagnosa ICD-10
	 *		"tipeRujukan" => value, | tipe rujukan -> 0.penuh, 1.Partial 2.rujuk balik
	 *		"poliRujukan" => value, | kode poli rujukan -> data di referensi poli
	 *		"user" => value,
	 *  )
	 *   $uri string
	 */
	function insertRujukan($params, $uri = "Rujukan/insert") {
		if(isset($params["jnsPelayanan"])) $params["jnsPelayanan"] = strval($params["jnsPelayanan"]);
		if(isset($params["tipeRujukan"])) $params["tipeRujukan"] = strval($params["tipeRujukan"]);
		if(isset($params["tglRujukan"])) {
			$params["tglRujukan"] = substr($params["tglRujukan"], 0, 10);
		}
		$data = json_encode(array(
			"request" => array(
				"t_rujukan" => $params
			)
		));
		
		$result =  json_decode($this->sendRequest($uri, "POST", $data, "application/x-www-form-urlencoded"));		
		if($result) {
			$message = trim($result->metadata->message);
			$result->metadata->requestId = $this->config["koders"];			
			if($result->metadata->code == 200 && ($message == '200' || $message == 'OK' || $message == 'Sukses')) {
				$params["noRujukan"] = $params["noSep"];
				if($params["tipeRujukan"] != 1) {
					$params["noRujukan"] = $result->response->rujukan->noRujukan;
				}
				$rRujukan = $this->rujukan->load(array(
					"noSep" => $params["noSep"],
					"status" => 1
				));
				
				$this->rujukan->simpan($params, count($rRujukan) == 0);
			} else {
				if($result->metadata != null) {
					$message = $result->metadata->code."-".$message;
				}
			}
		} else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Insert Rujukan<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		
		return $result;
	}
	
	/* Rujukan>>>Update Rujukan
	 * @parameter 
	 *   $params array(
	  *		"noSep" || "noRujukan" => value,
	 *		"ppkDirujuk" => value
	 *		"jnsPelayanan" => value, | 1 (Rawat Inap), 2 (Rawat Jalan)
	 *		"catatan" => value,
	 *		"diagRujukan" => value, | kode diagnosa ICD-10
	 *		"tipeRujukan" => value, | tipe rujukan -> 0.penuh, 1.Partial 2.rujuk balik
	 *		"poliRujukan" => value, | kode poli rujukan -> data di referensi poli
	 *		"user" => value,
	 *  )
	 *   $uri string
	 */
	function updateRujukan($params, $uri = "Rujukan/update") {
		$noSep = isset($params["noSep"]) ? $params["noSep"] : "";
		if(isset($params["jnsPelayanan"])) $params["jnsPelayanan"] = strval($params["jnsPelayanan"]);
		if(isset($params["tipeRujukan"])) $params["tipeRujukan"] = strval($params["tipeRujukan"]);
		if(isset($params["tglRujukan"])) {
			$params["tglRujukan"] = substr($params["tglRujukan"], 0, 10);
		}
		
		if(!isset($params["noRujukan"])) {
			if($noSep != "") {
				$rRujukan = $this->rujukan->load(array(
					"noSep" => $noSep,
					"status" => 1
				));
				$params["noRujukan"] = $noSep;
				if(count($rRujukan) > 0) {
					$params["noRujukan"] = $rRujukan[0]["noRujukan"];					
				}
			}
		}
		if($noSep != "") unset($params["noSep"]);
		
		$data = json_encode(array(
			"request" => array(
				"t_rujukan" => $params
			)
		));			
		
		$result =  json_decode($this->sendRequest($uri, "PUT", $data, "application/x-www-form-urlencoded"));		
		if($result) {
			$message = trim($result->metadata->message);
			$result->metadata->requestId = $this->config["koders"];			
			if($result->metadata->code == 200 && ($message == '200' || $message == 'OK' || $message == 'Sukses')) {
				$rRujukan = $this->rujukan->load(array(
					"noRujukan" => $params["noRujukan"],
					"status" => 1
				));
				
				$this->rujukan->simpan($params, count($rRujukan) == 0);
			} else {
				if($result->metadata != null) {
					$message = $result->metadata->code."-".$message;
				}
			}
		} else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Update Rujukan<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		
		return $result;
	}
	
	/* Rujukan>>>Delete Rujukan
	 * @parameter 
	 *   $params array(
	 *		"noSep" || "noRujukan" => value,	
	 *		"user" => value,
	 *  )
	 *   $uri string
	 */
	function deleteRujukan($params, $uri = "Rujukan/delete") {
		$noSep = isset($params["noSep"]) ? $params["noSep"] : "";
		if(!isset($params["noRujukan"])) {
			if($noSep != "") {
				$rRujukan = $this->rujukan->load(array(
					"noSep" => $noSep,
					"status" => 1
				));
				$params["noRujukan"] = $noSep;
				if(count($rRujukan) > 0) {
					$params["noRujukan"] = $rRujukan[0]["noRujukan"];					
				}
			}
		}
		if($noSep != "") unset($params["noSep"]);
		
		$data = json_encode(array(
			"request" => array(
				"t_rujukan" => $params
			)
		));
		
		$result =  json_decode($this->sendRequest($uri, "DELETE", $data, "application/x-www-form-urlencoded"));		
		if($result) {
			$message = trim($result->metadata->message);
			$result->metadata->requestId = $this->config["koders"];			
			if($result->metadata->code == 200 && ($message == '200' || $message == 'OK' || $message == 'Sukses')) {
				$rRujukan = $this->rujukan->load(array(
					"noRujukan" => $params["noRujukan"],
					"status" => 1
				));
				
				if(count($rRujukan) > 0) {					
					$params["status"] = 0;
					$this->rujukan->simpan($params);
				}
			} else {
				if($result->metadata != null) {
					$message = $result->metadata->code."-".$message;
				}
			}
		} else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Delete Rujukan<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		
		return $result;
	}
	
	/* Referensi>>>Poli
	 * Pencarian data poli
	 * @parameter 
	 *   $params array(
	 *		"query" => value | kode atau nama poli
	 * )
	 */
	function poli($params = "", $uri = "referensi/poli/") {
		$query = $params;
		if(is_array($params)) {
			if(isset($params["query"])) $query = $params["query"];
		}
		
		$result = json_decode($this->sendRequest($uri.$query));
		if($result) {
			$result->metadata->requestId = $this->config["koders"];			
			if($result->response) {
				$poli = isset($result->response->poli) ? (array) $result->response->poli : array();
				$data = array();
				foreach($poli as $p) {
					$data[] = array("kdPoli" => $p->kode, "nmPoli" => $p->nama);
					$found = $this->poli->load(array("kode" => $p->kode));
					$this->poli->simpan(array(
					    "kode" => $p->kode,
					    "nama" => $p->nama
					), count($found) == 0);
				}
				$result->response->list = $data;
				$result->response->count = count($data);
				unset($result->response->poli);
			}
		} else {
			return json_decode(json_encode(array(			
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Pencarian data poli<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* Referensi>>>Diagnosa
	 * Pencarian data diagnosa (ICD-10)
	 * @parameter 
	 *   $params array(
	 *		"query" => value | kode atau nama diagnosa
	 * )
	 *   $uri string
	 */
	function diagnosa($params, $uri = "referensi/diagnosa/") {
		$query = $params;
		if(is_array($params)) {
			if(isset($params["query"])) $query = $params["query"];
		}
		$result = json_decode($this->sendRequest($uri.$query));		
		if($result) {
			$result->metadata->requestId = $this->config["koders"];			
			if($result->response) {
				$diagnosa = isset($result->response->diagnosa) ? (array) $result->response->diagnosa : array();
				$result->response->list = $diagnosa;
				$result->response->count = count($diagnosa);
				unset($result->response->diagnosa);
			}
		} else {
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
	
	/* Referensi>>>Procedure / Tindakan
	 * Pencarian data procedure/tindakan
	 * @parameter 
	 *   $params array(
	 *		"query" => value | kode atau nama diagnosa
	 * )
	 *   $uri string
	 */
	function procedure($params, $uri = "referensi/procedure/") {
		$query = $params;
		if(is_array($params)) {
			if(isset($params["query"])) $query = $params["query"];
		}
		$result = json_decode($this->sendRequest($uri.$query));
		if($result) {
			$result->metadata->requestId = $this->config["koders"];			
			if($result->response) {
				$procedure = isset($result->response->procedure) ? (array) $result->response->procedure : array();
				$result->response->list = $procedure;
				$result->response->count = count($procedure);
				unset($result->response->procedure);
			}
		} else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Pencarian data procedure/tindakan<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* Referensi>>>Kelas Rawat
	 * Pencarian data kelas rawat	
	 */
	function kelasRawat($uri = "referensi/kelasrawat") {
		$result = json_decode($this->sendRequest($uri));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(			
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Pencarian data kelas rawat<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* Referensi>>>Dokter
	 * Pencarian data dokter DPJP
	 * @parameter 
	 *   $params array(
	 *		"query" => value | nama dokter/DPJP
	 * )
	 */
	function dokter($params, $uri = "referensi/dokter/") {
		$query = $params;
		if(is_array($params)) {
			if(isset($params["query"])) $query = $params["query"];
		}
		$result = json_decode($this->sendRequest($uri.$query));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Pencarian data dokter DPJP<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* Referensi>>>Spesialistik
	 * Pencarian data spesialistik
	 */
	function spesialistik($uri = "referensi/spesialistik") {
		$result = json_decode($this->sendRequest($uri));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Pencarian data spesialistik<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* Referensi>>>Ruang Rawat
	 * Pencarian data ruang rawat
	 */
	function ruangRawat($uri = "referensi/ruangrawat") {
		$result = json_decode($this->sendRequest($uri));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Pencarian data ruang rawat<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* Referensi>>>Cara Keluar
	 * Pencarian data cara keluar
	 */
	function caraKeluar($uri = "referensi/carakeluar") {
		$result = json_decode($this->sendRequest($uri));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Pencarian data cara keluar<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* Referensi>>>Pasca Pulang
	 * Pencarian data pasca pulang
	 */
	function pascaPulang($uri = "referensi/pascapulang") {
		$result = json_decode($this->sendRequest($uri));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Pencarian data pasca pulang<br/>".$this::RESULT_IS_NULL,
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
	 *		"jenis" => value | 1. Faskes 1, 2. Faskes 2/RS
	 *)
	 *   $uri string
	 */
	function faskes($params, $uri = "referensi/faskes/") {
		$nama = $jenis = "";
		if(is_array($params)) {
			if(isset($params["nama"])) $nama = $params["nama"];
			if(isset($params["jenis"])) $jenis = $params["jenis"];
		}
		$result = json_decode($this->sendRequest($uri.$nama."/".$jenis));
		if($result) {
			$result->metadata->requestId = $this->config["koders"];			
			if($result->response) {
				$faskes = isset($result->response->faskes) ? (array) $result->response->faskes : array();				
				$data = array();				
				foreach($faskes as $f) {
					$data[] = array("kdProvider" => $f->kode, "nmProvider" => $f->nama);
					$namas = explode(" - ", $f->nama);	
					$found = $this->ppk->load(array("kode" => $f->kode));
					$create = count($found) == 0;
					$this->ppk->simpan(array(
						"kode" => $f->kode,
						"nama" => $namas[0],
						"jenis" => $jenis,
						"deswilayah" => $namas[1]
					), $create);
					
					/*file_put_contents("logs/ppk.txt", json_encode(array(
						"kode" => $f->kode,
						"nama" => $namas[0],
						"jenis" => $jenis,
						"deswilayah" => $namas[1]
					));*/
				}
				$result->response->list = $data;
				$result->response->count = count($data);
				unset($result->response->faskes);
			}
		} else {	
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
	
	/* Monitoring>>>Data Kunjungan
	 * Data Kunjungan
	 * @parameter 
	 *   $params array(
	 *		"tanggal" => value | yyyy-MM-dd,
	 *		"jenis" => value,
	 *)
	 *   $uri string
	 */
	function monitoringKunjungan($params, $uri = "Monitoring/Kunjungan/") {
		$tanggal = $jenis = "";
		if(is_array($params)) {
			if(isset($params["tanggal"])) $tanggal = "Tanggal/".$params["tanggal"]."/";
			if(isset($params["jenis"])) $jenis = "JnsPelayanan/".$params["jenis"];
		}
		$result = json_decode($this->sendRequest($uri.$tanggal.$jenis));
		if($result) {
			$result->metadata->requestId = $this->config["koders"];			
			if($result->response) {
				$list = isset($result->response->sep) ? (array) $result->response->sep : array();				
				$result->response->list = $list;
				$result->response->count = count($list);
				unset($result->response->sep);
			}
		} else {
			return json_decode(json_encode(array(			
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Monitoring Data Kunjungan<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* Monitoring>>>Data Klaim
	 * Data Klaim
	 * @parameter 
	 *   $params array(
	 *		"tanggal" => value | yyyy-MM-dd	 
	 *		"jenis" => value | 1 (Rawat Inap), 2 (Rawat Jalan)	 
	 *		"status" => value | 1 (Proses Verifikasi); 2 (Pending Verifikasi); 3 (Klaim)
	 *)
	 *   $uri string
	 */
	function monitoringKlaim($params, $uri = "Monitoring/Klaim/") {
		$tanggal = $jenis = $status = "";
		if(is_array($params)) {
			if(isset($params["tanggal"])) $tanggal = "Tanggal/".$params["tanggal"]."/";
			if(isset($params["jenis"])) $jenis = "JnsPelayanan/".$params["jenis"]."/";
			if(isset($params["status"])) $status = "Status/".$params["status"];
		}
		$result = json_decode($this->sendRequest($uri.$tanggal.$jenis.$status));
		if($result) {
			$result->metadata->requestId = $this->config["koders"];			
			if($result->response) {
				$klaim = isset($result->response->klaim) ? (array) $result->response->klaim : array();				
				$result->response->list = $klaim;
				$result->response->count = count($klaim);
				unset($result->response->klaim);
			}
		} else {
			return json_decode(json_encode(array(			
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Monitoring Data Klaim<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
}