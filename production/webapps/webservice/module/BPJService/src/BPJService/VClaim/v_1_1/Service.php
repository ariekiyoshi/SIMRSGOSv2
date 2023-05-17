<?php
/**
 * BPJService
 * @author hariansyah
 * 
 */
 
namespace BPJService\VClaim\v_1_1;

use BPJService\VClaim\v_1_0\Service as BaseService;
use Zend\Json\Json;

class Service extends BaseService {
    protected function pesertaTableToFormatBPJS($rPeserta) {
        return [
            "cob" => [
                "noAsuransi" => $rPeserta['noAsuransi'],
                "nmAsuransi" => $rPeserta['nmAsuransi'],
                "tglTAT" => $rPeserta['tglTAT'],
                "tglTMT" => $rPeserta['cobTglTMT']
            ],
            'hakKelas' => [
                'kode' => $rPeserta['kdKelas'],
                'keterangan' => $rPeserta['nmKelas']
            ],
            "informasi" => [
                "dinsos" => $rPeserta['dinsos'],
                "noSKTM" => $rPeserta['noSKTM'],
                "prolanisPRB" => $rPeserta['prolanisPRB']
            ],
            'jenisPeserta' => [
                'kode' => $rPeserta['kdJenisPeserta'],
                'keterangan' => $rPeserta['nmJenisPeserta']
            ],
            "mr" => [
                "noMR" => $rPeserta['norm'],
                "noTelepon" => $rPeserta['noTelepon']
            ],
            'nama' => $rPeserta['nama'],
            'nik' => $rPeserta['nik'],
            'noKartu' => $rPeserta['noKartu'],
            'pisa' => $rPeserta['pisa'],
            'norm' => $rPeserta['norm'],
            'provUmum' => [
                'kdProvider' => $rPeserta['kdProvider'],
                'nmProvider' => $rPeserta['nmProvider']
            ],
            'sex' => $rPeserta['sex'],
            "statusPeserta" => [
                "keterangan" => $rPeserta['ketStatusPeserta'],
                "kode" => $rPeserta['kdStatusPeserta']
            ],
            'tglCetakKartu' => $rPeserta['tglCetakKartu'],
            'tglLahir' => $rPeserta['tglLahir'],
            "tglTAT" => $rPeserta['tglTAT'],
            "tglTMT" => $rPeserta['tglTMT'],
            "umur" => [
                "umurSaatPelayanan" => $rPeserta['umurSaatPelayanan'],
                "umurSekarang" => $rPeserta['umurSekarang']
            ]
        ];
    }
	/* SEP>>>Insert SEP
	 * generate Nomor SEP
	 * @parameter 
	 *   $params [
	 *		"noKartu" => value
	 *		, "tglSep" => value | yyyy-MM-dd
	 *		, "jnsPelayanan" => value
	 *		, "klsRawat" => value												
	 *		, "noMr" => value
	 
	 *		// rujukan
	 *		, "asalRujukan" => value | 1 = Faskes 1, 2 = Faskes 2 (RS)			
	 *		, "tglRujukan" => value | yyyy-MM-dd
	 *		, "noRujukan" => value
	 *		, "ppkRujukan" => value
	 
	 *		, "catatan" => value
	 *		, "diagAwal" => value
	 
	 *		// poli
	 *		, "poliTujuan" => value
	 *		, "eksekutif" => value | 0 = Tidak, 1 = Ya							
	 
	 *		, "cob" => value | 0 = Tidak, 1 = Ya								
	 *		, "katarak" => value | 0 = Tidak, 1 = Ya								#ADDED
	 *		, "noSuratSKDP" => value                                                #ADDED
	 *		, "dpjpSKDP" => value                                                   #ADDED
	 	 
	 *		// jaminan
	 *		, "lakaLantas" => value | 2 = Tidak, 1 = Ya # Skrg 0 = Tidak, 1 = Ya
	 *		, "penjamin" => value | 1=Jasa raharja PT, 2=BPJS Ketenagakerjaan, 3=TASPEN PT, 4=ASABRI PT} jika lebih dari 1 isi -> 1,2 (pakai delimiter koma)
	 *      , "tglKejadian" => value | yyyy-MM-dd                                  #ADDED
	 *      , "suplesi" => value | Skrg 0 = Tidak, 1 = Ya                          #ADDED
	 *      , "noSuplesi => value                                                  #ADDED
	 *		, "lokasiLaka" => value #change to keterangan                          #CHANGED
	 *      , "propinsi" => ""                                                     #ADDED
	 *      , "kabupaten" => ""                                                    #ADDED
	 *      , "kecamatan" => ""                                                    #ADDED
	 
	 *		, "noTelp" => value													
	 *		, "user" => value
	 *   ],
	 *   $uri string
	 */	
	function generateNoSEP($params = [], $uri = "SEP/1.1/insert") {
		$paket = $params;
		$paket["rujukan"] = [
			"asalRujukan" => "1"
			, "tglRujukan" => ""
			, "noRujukan" => "-"
			, "ppkRujukan" => "-"
		];
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
		
		$paket["poli"] = [
			"tujuan" => ""
			, "eksekutif" => "0"
		];
		
		if(isset($paket["poliTujuan"])) {
			$paket["poli"]["tujuan"] = $paket["poliTujuan"];
			unset($paket["poliTujuan"]);
		}
		if(isset($paket["eksekutif"])) {
			$paket["poli"]["eksekutif"] = strval($paket["eksekutif"]);
			unset($paket["eksekutif"]);
		}
		
		if(isset($paket["cob"])) {
			$paket["cob"] = [
				"cob" => strval($paket["cob"])
			];
		} else {
			$paket["cob"] = [
				"cob" => "0"
			];
		}
		
		if(isset($paket["katarak"])) {
		    $paket["katarak"] = [
		        "katarak" => strval($paket["katarak"])
		    ];
		} else {
		    $paket["katarak"] = [
		        "katarak" => "0"
		    ];
		}
		
		$paket["skdp"] = [
		    "noSurat" => ""
		    , "kodeDPJP" => ""
		];
		if(isset($paket["noSuratSKDP"])) {
		    $paket["skdp"]["noSurat"] = $paket["noSuratSKDP"];
		    unset($paket["noSuratSKDP"]);
		}
		if(isset($paket["dpjpSKDP"])) {
		    $paket["skdp"]["kodeDPJP"] = $paket["dpjpSKDP"];
		    unset($paket["dpjpSKDP"]);
		}
		
		$paket["jaminan"] = [
			"lakaLantas" => "0",
		    "penjamin" => [
		        "penjamin" => "0",
		        "tglKejadian" => "",
		        "keterangan" => "",
		        "suplesi" => [
		            "suplesi" => "0",
		            "noSepSuplesi" => "",
		            "lokasiLaka" => [
		                "kdPropinsi" => "",
		                "kdKabupaten" => "",
		                "kdKecamatan" => ""
		            ]
		        ]		        
		    ]
			//, "penjamin" => "0"
			//, "lokasiLaka" => "-"
		];
		if(isset($paket["lakaLantas"])) {
			$paket["jaminan"]["lakaLantas"] = strval($paket["lakaLantas"]) == "2" ? "0" : "1";
			unset($paket["lakaLantas"]);
		}
		if(isset($paket["penjamin"])) {
		    $paket["jaminan"]["penjamin"]["penjamin"] = strval($paket["penjamin"]);
			unset($paket["penjamin"]);
		}
		if(isset($paket["tglKejadian"])) {
		    $paket["jaminan"]["penjamin"]["tglKejadian"] = strval($paket["tglKejadian"]);
		    unset($paket["tglKejadian"]);
		}
		if(isset($paket["lokasiLaka"])) {
		    $paket["jaminan"]["penjamin"]["keterangan"] = $paket["lokasiLaka"];
			unset($paket["lokasiLaka"]);
		}
		if(isset($paket["suplesi"])) {
		    $paket["jaminan"]["penjamin"]["suplesi"]["suplesi"] = strval($paket["suplesi"]);
		    unset($paket["suplesi"]);
		}
		if(isset($paket["noSuplesi"])) {
		    $paket["jaminan"]["penjamin"]["suplesi"]["noSepSuplesi"] = $paket["noSuplesi"];
		    unset($paket["noSuplesi"]);
		}
		if(isset($paket["propinsi"])) {
		    $paket["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdPropinsi"] = $paket["propinsi"];
		    unset($paket["propinsi"]);
		}
		if(isset($paket["kabupaten"])) {
		    $paket["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKabupaten"] = $paket["kabupaten"];
		    unset($paket["kabupaten"]);
		}
		if(isset($paket["kecamatan"])) {
		    $paket["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKecamatan"] = $paket["kecamatan"];
		    unset($paket["kecamatan"]);
		}
		
		$paket["catatan"] = isset($paket["catatan"]) ? $paket["catatan"] : "-";
		$paket["noTelp"] = isset($paket["noTelp"]) ? $paket["noTelp"] : "-";
		
		/* request data peserta di bpjs */
		$result = $this->cariPesertaDgnNoKartuBPJS([
			"noKartu" => $params["noKartu"]
			, "norm" => $params["norm"]
		]);
				
		$peserta = null;
		
		$metaData = [
			'message' => 200,
			'code' => 200,
			'requestId'=> $this->config["koders"]
		];
		
		$message = trim($result->metadata->message);
		if($result->metadata->code == 200 && ($message == '200' || $message == 'OK' || $message == 'Sukses')) {		
			$peserta = $result->response->peserta;
		}
		
		if($peserta == null) {
			try {
				$rPeserta = $this->peserta->load(["noKartu" => $params["noKartu"]]);
				if(count($rPeserta) > 0) {
					$rPeserta = $rPeserta[0];
					$peserta = $this->pesertaTableToFormatBPJS($rPeserta);
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
			$paket["klsRawat"] = $peserta->hakKelas->kode;
			$paket["noMR"] = strval($params["norm"]);
			$paket["tglSep"] = substr($paket["tglSep"], 0, 10);
			$paket["jnsPelayanan"] = strval($params["jnsPelayanan"]);			
			unset($paket["create"]);
			unset($paket["ip"]);
			unset($paket["norm"]);
			
			$data = json_encode([
				"request" => [
					"t_sep" => $paket
				]
			]);
			
			$result = json_decode($this->sendRequest($uri, "POST", $data, "Application/x-www-form-urlencoded"));
			
			if($result) {
				if($result->metadata->code == 200 && (trim($result->metadata->message) == '200' || trim($result->metadata->message) == 'OK' || trim($result->metadata->message) == 'Sukses') && $result->response != null) {
					$noSEP = $result->response->sep->noSep;
					$ppkPelayanan = $this->config["koders"];
					
					$params["noSEP"] = $noSEP;
					$params["tglSEP"] = $params["tglSep"];
					$params["ppkPelayanan"] = $ppkPelayanan;
					$params["jenisPelayanan"] = $params["jnsPelayanan"];
					$params["klsRawat"] = $peserta->hakKelas->kode;
					
					$rKunjungan = $this->kunjungan->load([
						"noKartu" => $params["noKartu"]
						, "noSEP" => $params["noSEP"]
					]);
					
					$this->kunjungan->simpan($params, count($rKunjungan) == 0);						
					$kunjungan = $params;
					unset($kunjungan["jenisPelayanan"]);
					unset($kunjungan["ip"]);
					unset($kunjungan["create"]);
				   
					$metaData = [
						'message' => 200,
						'code' => 200,
						'requestId'=> $this->config["koders"]
					]; 
				} else {
					$metaData['message'] = $result == null ? "SIMRSInfo::Error" : $result->metadata->message." ".(is_string($result->response) ? $result->response : "");
					$metaData['code'] = $result == null ? 400 : $result->metadata->code;
				}
			} else {
				return json_decode(json_encode([					
					'metadata' => [
						'message' => "SIMRSInfo::Error Request Service - generate Nomor SEP<br/>".$this::RESULT_IS_NULL,
						'code' => 502,
						'requestId'=> $this->config["koders"]
					]
				]));
			}
		}
					
		return json_decode(json_encode([
			'response' => [
				'peserta' => $peserta,
				'kunjungan' => $kunjungan
			],
			'metadata' => $metaData
		]));
	}
	
	/* SEP>>>Update SEP
	 * Update SEP
	 * @parameter 
	 *   $params [
	 *		"noSep" => value
	*		, "klsRawat" => value												
	 *		, "noMr" => value
	 
	 *		// rujukan
	 *		, "asalRujukan" => value | 1 = Faskes 1, 2 = Faskes 2 (RS)			
	 *		, "tglRujukan" => value | yyyy-MM-dd
	 *		, "noRujukan" => value
	 *		, "ppkRujukan" => value
	 
	 *		, "catatan" => value
	 *		, "diagAwal" => value
	 
	 *		// poli
	 *		, "eksekutif" => value | 0 = Tidak, 1 = Ya							
	 
	 *		, "cob" => value | 0 = Tidak, 1 = Ya								
	 *		, "katarak" => value | 0 = Tidak, 1 = Ya								#ADDED
	 *		, "noSuratSKDP" => value                                                #ADDED
	 *		, "dpjpSKDP" => value                                                   #ADDED
	 	 
	 *		// jaminan
	 *		, "lakaLantas" => value | 2 = Tidak, 1 = Ya # Skrg 0 = Tidak, 1 = Ya
	 *		, "penjamin" => value | 1=Jasa raharja PT, 2=BPJS Ketenagakerjaan, 3=TASPEN PT, 4=ASABRI PT} jika lebih dari 1 isi -> 1,2 (pakai delimiter koma)
	 *      , "tglKejadian" => value | yyyy-MM-dd                                  #ADDED
	 *      , "suplesi" => value | Skrg 0 = Tidak, 1 = Ya                          #ADDED
	 *      , "noSuplesi => value                                                  #ADDED
	 *		, "lokasiLaka" => value #change to keterangan                          #CHANGED
	 *      , "propinsi" => ""                                                     #ADDED
	 *      , "kabupaten" => ""                                                    #ADDED
	 *      , "kecamatan" => ""                                                    #ADDED
	 
	 *		, "noTelp" => value													
	 *		, "user" => value
	 *)
	 *   $uri string
	 */	
	function updateSEP($params, $uri = "Sep/1.1/Update") {		
		$paket = $params;
		$paket["rujukan"] = [
			"asalRujukan" => "1"
			, "tglRujukan" => ""
			, "noRujukan" => "-"
			, "ppkRujukan" => "-"
		];
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
		
		$paket["poli"] = [
			//"tujuan" => "", 
			"eksekutif" => "0"
		];
		/*if(isset($paket["poliTujuan"])) {
			$paket["poli"]["tujuan"] = $paket["poliTujuan"];
			unset($paket["poliTujuan"]);
		}*/
		if(isset($paket["eksekutif"])) {
			$paket["poli"]["eksekutif"] = strval($paket["eksekutif"]);
			unset($paket["eksekutif"]);
		}
						
		if(isset($paket["cob"])) {
			$paket["cob"] = [
				"cob" => strval($paket["cob"])
			];
		} else {
			$paket["cob"] = [
				"cob" => "0"
			];
		}
		
		if(isset($paket["katarak"])) {
		    $paket["katarak"] = [
		        "katarak" => strval($paket["katarak"])
		    ];
		} else {
		    $paket["katarak"] = [
		        "katarak" => "0"
		    ];
		}
		
		$paket["skdp"] = [
		    "noSurat" => ""
		    , "kodeDPJP" => ""
		];
		if(isset($paket["noSuratSKDP"])) {
		    $paket["skdp"]["noSurat"] = $paket["noSuratSKDP"];
		    unset($paket["noSuratSKDP"]);
		}
		if(isset($paket["dpjpSKDP"])) {
		    $paket["skdp"]["kodeDPJP"] = $paket["dpjpSKDP"];
		    unset($paket["dpjpSKDP"]);
		}
		
		$paket["jaminan"] = [
		    "lakaLantas" => "0",
		    "penjamin" => [
		        "penjamin" => "0",
		        "tglKejadian" => "",
		        "keterangan" => "",
		        "suplesi" => [
		            "suplesi" => "0",
		            "noSepSuplesi" => "",
		            "lokasiLaka" => [
		                "kdPropinsi" => "",
		                "kdKabupaten" => "",
		                "kdKecamatan" => ""
		            ]
		        ]
		    ]
		    //, "penjamin" => "0"
		    //, "lokasiLaka" => "-"
		];
		if(isset($paket["lakaLantas"])) {
		    $paket["jaminan"]["lakaLantas"] = strval($paket["lakaLantas"]) == "2" ? "0" : "1";
		    unset($paket["lakaLantas"]);
		}
		if(isset($paket["penjamin"])) {
		    $paket["jaminan"]["penjamin"]["penjamin"] = strval($paket["penjamin"]);
		    unset($paket["penjamin"]);
		}
		if(isset($paket["tglKejadian"])) {
		    $paket["jaminan"]["penjamin"]["tglKejadian"] = strval($paket["tglKejadian"]);
		    unset($paket["tglKejadian"]);
		}
		if(isset($paket["lokasiLaka"])) {
		    $paket["jaminan"]["penjamin"]["keterangan"] = $paket["lokasiLaka"];
		    unset($paket["lokasiLaka"]);
		}
		if(isset($paket["suplesi"])) {
		    $paket["jaminan"]["penjamin"]["suplesi"]["suplesi"] = strval($paket["suplesi"]);
		    unset($paket["suplesi"]);
		}
		if(isset($paket["noSuplesi"])) {
		    $paket["jaminan"]["penjamin"]["suplesi"]["noSuplesi"] = $paket["noSuplesi"];
		    unset($paket["noSuplesi"]);
		}
		if(isset($paket["propinsi"])) {
		    $paket["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdPropinsi"] = $paket["propinsi"];
		    unset($paket["propinsi"]);
		}
		if(isset($paket["kabupaten"])) {
		    $paket["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKabupaten"] = $paket["kabupaten"];
		    unset($paket["kabupaten"]);
		}
		if(isset($paket["kecamatan"])) {
		    $paket["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKecamatan"] = $paket["kecamatan"];
		    unset($paket["kecamatan"]);
		}
		
		$paket["catatan"] = isset($paket["catatan"]) ? $paket["catatan"] : "-";
		$paket["noTelp"] = isset($paket["noTelp"]) ? $paket["noTelp"] : "-";
		
		/* request data peserta di bpjs */
		$result = $this->cariPesertaDgnNoKartuBPJS([
			"noKartu" => $params["noKartu"]
			, "norm" => $params["norm"]
		]);
				
		$peserta = null;
		
		$metaData = [
			'message' => 200,
			'code' => 200,
			'requestId'=> $this->config["koders"]
		];
		
		$message = trim($result->metadata->message);
		if($result->metadata->code == 200 && ($message == '200' || $message == 'OK' || $message == 'Sukses')) {
			$peserta = $result->response->peserta;
		}
		
		if($peserta == null) {
			try {
				$rPeserta = $this->peserta->load(["noKartu" => $params["noKartu"]]);
				if(count($rPeserta) > 0) {
					$rPeserta = $rPeserta[0];
					$peserta = $this->pesertaTableToFormatBPJS($rPeserta);
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
			
			$data = json_encode([
				"request" => [
					"t_sep" => $paket
				]
			]);
			
			$result = json_decode($this->sendRequest($uri, "PUT", $data, "Application/x-www-form-urlencoded"));			
			if($result) {
				if($result->metadata->code == 200 && (trim($result->metadata->message) == '200' || trim($result->metadata->message) == 'OK' || trim($result->metadata->message) == 'Sukses') && $result->response != null) {
					$ppkPelayanan = $this->config["koders"];
					
					$params["noSEP"] = $params["noSep"];
					#$params["tglSEP"] = $params["tglSep"];
					#$params["ppkPelayanan"] = $ppkPelayanan;
					#$params["jenisPelayanan"] = $params["jnsPelayanan"];
					$params["klsRawat"] = $paket["klsRawat"];
					
					$rKunjungan = $this->kunjungan->load([
						"noKartu" => $params["noKartu"]
						, "noSEP" => $params["noSEP"]
					]);
					
					$this->kunjungan->simpan($params, count($rKunjungan) == 0);						
					$kunjungan = $params;
					unset($kunjungan["jenisPelayanan"]);
					unset($kunjungan["ip"]);
					unset($kunjungan["create"]);
				   
					$metaData = [
						'message' => 200,
						'code' => 200,
						'requestId'=> $this->config["koders"]
					]; 
				} else {
					$metaData['message'] = $result == null ? "SIMRSInfo::Error" : $result->metadata->message." ".(is_string($result->response) ? $result->response : "");
					$metaData['code'] = $result == null ? 400 : $result->metadata->code;
				}
			} else {
				return json_decode(json_encode([					
					'metadata' => [
						'message' => "SIMRSInfo::Error Request Service - Update SEP<br/>".$this::RESULT_IS_NULL,
						'code' => 502,
						'requestId'=> $this->config["koders"]
					]
				]));
			}
		}
					
		return json_decode(json_encode([
			'response' => [
				'peserta' => $peserta,
				'kunjungan' => $kunjungan
			],
			'metadata' => $metaData
		]));
	}
	
	
	/* Rujukan>>>Berdasarkan Nomor Kartu (Multi Record)
	 * cari data rujukan berdasarkan Nomor Kartu BPJS (Multi Record)
	 * @parameter 
	 *   $params string | array("noKartu" => value, "faskes" => value | 1 = Faskes 1; 2 = Faskes 2/RS )
	 *   $uri string
	 */
	function listRujukanDgnNoKartuBPJS($params, $uri = "Rujukan/") {
	    $nomor = "";
	    $faskes = 1;
	    $url = "List/Peserta/";
	    if(is_array($params)) {
	        if(isset($params["noKartu"])) $nomor = $params["noKartu"];
	        if(isset($params["faskes"])) $faskes = $params["faskes"];	        
	    }
	    if($faskes == 2) $url = "RS/List/Peserta/";
	    
	    $result = Json::decode($this->sendRequest($uri.$url.$nomor));
	    if($result) {
	        $result->metadata->requestId = $this->config["koders"];
	        if($result->response) {
	            $data = isset($result->response->rujukan) ? (array) $result->response->rujukan : array();
	            $result->response->rujukan = $data;
	            $result->response->count = count($data);
	        }
	    } else {
	        return json_decode(json_encode(array(
	            'metadata' => array(
	                'message' => "SIMRSInfo::Error Request Service - list rujukan berdasarkan Nomor kartu <br/>".$this::RESULT_IS_NULL,
	                'code' => 502,
	                'requestId'=> $this->config["koders"]
	            )
	        )));
	    }
	    return $result;
	}
	
	/* Referensi>>>DPJP
	 * Pencarian data DPJP
	 * @parameter
	 *   $params array(
	 *		"jenisPelayanan" => value | Jenis Pelayanan (1. Rawat Inap, 2. Rawat Jalan)
	 *		, "tglPelayanan" => value | Tgl.Pelayanan/SEP (yyyy-mm-dd)
	 *		, "kodeSpesialis" => value | Kode Spesialis/Subspesialis see referensi spesialistik
	 * )
	 */
	function dpjp($params, $uri = "referensi/dokter/") {
	    $jenis = $tgl = $kode = "";
	    if(is_array($params)) {	        
	        if(isset($params["jenisPelayanan"])) $jenis = "pelayanan/".$params["jenisPelayanan"]."/";
	        if(isset($params["tglPelayanan"])) $tgl = "tglPelayanan/".$params["tglPelayanan"]."/";
	        if(isset($params["kodeSpesialis"])) $kode = "Spesialis/".$params["kodeSpesialis"];
	    }
	    $result = json_decode($this->sendRequest($uri.$jenis.$tgl.$kode));
	    if($result) {
	        $result->metadata->requestId = $this->config["koders"];
	        if($result->response) {
	            $data = isset($result->response->list) ? (array) $result->response->list : array();
	            $result->response->list = $data;
	            $result->response->count = count($data);
	        }
	    } else {
	        return json_decode(json_encode(array(
	            'metadata' => array(
	                'message' => "SIMRSInfo::Error Request Service - Pencarian DPJP<br/>".$this::RESULT_IS_NULL,
	                'code' => 502,
	                'requestId'=> $this->config["koders"]
	            )
	        )));
	    }
	    return $result;
	}
	
	/* Referensi>>>Propinsi
	 * Pencarian data Propinsi
	 */
	function propinsi($uri = "referensi/propinsi") {
	    $result = json_decode($this->sendRequest($uri));
	    if($result) {
	        $result->metadata->requestId = $this->config["koders"];
	        if($result->response) {
	            $data = isset($result->response->list) ? (array) $result->response->list : array();
	            $result->response->list = $data;
	            $result->response->count = count($data);
	        }
	    } else {
	        return json_decode(json_encode(array(
	            'metadata' => array(
	                'message' => "SIMRSInfo::Error Request Service - Pencarian Propinsi<br/>".$this::RESULT_IS_NULL,
	                'code' => 502,
	                'requestId'=> $this->config["koders"]
	            )
	        )));
	    }
	    return $result;
	}
	
	/* Referensi>>>Kabupaten
	 * Pencarian data Kabupaten
	 * @parameter
	 * $params array(
	 *		"kodePropinsi" => value
	 * )
	 */
	function kabupaten($params, $uri = "referensi/kabupaten/") {
	    $propinsi = "";
	    if(is_array($params)) {
	        if(isset($params["kodePropinsi"])) $propinsi = "propinsi/".$params["kodePropinsi"];
	        
	    }
	    $result = json_decode($this->sendRequest($uri.$propinsi));
	    if($result) {
	        $result->metadata->requestId = $this->config["koders"];
	        if($result->response) {
	            $data = isset($result->response->list) ? (array) $result->response->list : array();
	            $result->response->list = $data;
	            $result->response->count = count($data);
	        }
	    } else {
	        return json_decode(json_encode(array(
	            'metadata' => array(
	                'message' => "SIMRSInfo::Error Request Service - Pencarian Kabupaten<br/>".$this::RESULT_IS_NULL,
	                'code' => 502,
	                'requestId'=> $this->config["koders"]
	            )
	        )));
	    }
	    return $result;
	}
	
	/* Referensi>>>Kecamatan
	 * Pencarian data Kecamatan
	 * @parameter
	 * $params array(
	 *		"kodekabupaten" => value
	 * )
	 */
	function kecamatan($params, $uri = "referensi/kecamatan/") {
	    $kabupaten = "";
	    if(is_array($params)) {
	        if(isset($params["kodeKabupaten"])) $kabupaten = "kabupaten/".$params["kodeKabupaten"];
	        
	    }
	    $result = json_decode($this->sendRequest($uri.$kabupaten));
	    if($result) {
	        $result->metadata->requestId = $this->config["koders"];
	        if($result->response) {
	            $data = isset($result->response->list) ? (array) $result->response->list : array();
	            $result->response->list = $data;
	            $result->response->count = count($data);
	        }
	    } else {
	        return json_decode(json_encode(array(
	            'metadata' => array(
	                'message' => "SIMRSInfo::Error Request Service - Pencarian Kecamatan<br/>".$this::RESULT_IS_NULL,
	                'code' => 502,
	                'requestId'=> $this->config["koders"]
	            )
	        )));
	    }
	    return $result;
	}
	
	/* Monitoring>>>Data Histori Pelayanan Peserta
	 * Data Histori Pelayanan Peserta
	 * @parameter
	 *   $params array(
	 *		"noKartu" => value | No.Kartu Peserta
	 *		"tglMulai" => value | Tgl Mulai Pencarian (yyyy-mm-dd)
	 *		"tglAkhir" => value | Tgl Akhir Pencarian (yyyy-mm-dd)
	 *)
	 *   $uri string
	 */
	function monitoringHistoriPelayanan($params, $uri = "monitoring/HistoriPelayanan/") {
	    $nomor = $mulai = $akhir = "";
	    if(is_array($params)) {
	        if(isset($params["noKartu"])) $nomor = "NoKartu/".$params["noKartu"]."/";
	        if(isset($params["tglMulai"])) $mulai = "tglAwal/".$params["tglMulai"]."/";
	        if(isset($params["tglAkhir"])) $akhir = "tglAkhir/".$params["tglAkhir"];
	    }
	    $result = json_decode($this->sendRequest($uri.$nomor.$mulai.$akhir));
	    if($result) {
	        $result->metadata->requestId = $this->config["koders"];
	        if($result->response) {
	            $data = isset($result->response->histori) ? (array) $result->response->histori : array();
	            $result->response->list = $data;
	            $result->response->count = count($data);
	            unset($result->response->histori);
	        }
	    } else {
	        return json_decode(json_encode(array(
	            'metadata' => array(
	                'message' => "SIMRSInfo::Error Request Service - Monitoring Data Histori Pelayanan Peserta<br/>".$this::RESULT_IS_NULL,
	                'code' => 502,
	                'requestId'=> $this->config["koders"]
	            )
	        )));
	    }
	    return $result;
	}
	
	/* Monitoring>>>Data Klaim Jaminan Jasa Raharja
	 * Data Klaim Jaminan Jasa Raharja
	 * @parameter
	 *   $params array(
	 *		"tglMulai" => value | Tgl Mulai Pencarian (yyyy-mm-dd)
	 *		"tglAkhir" => value | Tgl Akhir Pencarian (yyyy-mm-dd)
	 *)
	 *   $uri string
	 */
	function monitoringKlaimJaminanJasaRaharja($params, $uri = "monitoring/JasaRaharja/") {
	    $mulai = $akhir = "";
	    if(is_array($params)) {
	        if(isset($params["tglMulai"])) $mulai = "tglMulai/".$params["tglMulai"]."/";
	        if(isset($params["tglAkhir"])) $akhir = "tglAkhir/".$params["tglAkhir"];
	    }
	    $result = json_decode($this->sendRequest($uri.$mulai.$akhir));
	    if($result) {
	        $result->metadata->requestId = $this->config["koders"];
	        if($result->response) {
	            $data = isset($result->response->jaminan) ? (array) $result->response->jaminan : array();
	            $result->response->list = $data;
	            $result->response->count = count($data);
	            unset($result->response->jaminan);
	        }
	    } else {
	        return json_decode(json_encode(array(
	            'metadata' => array(
	                'message' => "SIMRSInfo::Error Request Service - Monitoring Data Klaim Jaminan Jasa Raharja<br/>".$this::RESULT_IS_NULL,
	                'code' => 502,
	                'requestId'=> $this->config["koders"]
	            )
	        )));
	    }
	    return $result;
	}
}