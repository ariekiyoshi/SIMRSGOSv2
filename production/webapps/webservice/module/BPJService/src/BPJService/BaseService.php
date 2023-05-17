<?php
/**
 * BPJService
 * @author hariansyah
 * Standard service version 2.0
 */
 
namespace BPJService;
	
/* kode error
	500: Error Query Select / Tidak Konek ke Database
	501: Peserta tidak terdaftar di table bpjs.peserta
	502: Error Request Service
*/

use \DateTime;
use \DateInterval;
use \DateTimeZone;
use Zend\Json\Json;

use BPJService\db\peserta\Service as PesertaService;
use BPJService\db\kunjungan\Service as KunjunganService;
use BPJService\db\pengajuan\Service as PengajuanService;
use BPJService\db\rujukan\Service as RujukanService;
use BPJService\db\referensi\ppk\Service as PPKService;
use BPJService\db\referensi\poli\Service as PoliService;
use BPJService\db\klaim\Service as KlaimService;
use Aplikasi\db\bridge_log\Service as BridgeLogService;
use DBService\generator\Generator;

abstract class BaseService {
	protected $config;
	protected $headers = array();
	protected $peserta;
	protected $kunjungan;
	protected $pengajuan;
	protected $rujukan;
	protected $ppk;
	protected $poli;
	protected $klaim;
	protected $bridgeLog;
	protected $jenisBridge = 1;
		
	CONST RESULT_IS_NULL = "Silahkan hubungi BPJS respon data null";
	
	function __construct($config) {
		$this->config = $config;
		$this->peserta = new PesertaService();
		$this->kunjungan = new KunjunganService();
		$this->pengajuan = new PengajuanService();
		$this->rujukan = new RujukanService();
		$this->ppk = new PPKService();
		$this->poli = new PoliService();
		$this->klaim = new KlaimService();

		$this->bridgeLog = new BridgeLogService();
	}

	public function getPeserta() {
		return $this->peserta;
	}
	
	public function getKunjungan() {
		return $this->kunjungan;
	}
	
	public function getPengajuan() {
		return $this->pengajuan;
	}
	
	public function getRujukan() {
		return $this->rujukan;
	}
	
	public function getKlaim() {
	    return $this->klaim;
	}
	
	public function getConfig() {
		return $this->config;
	}
	
	protected function setHeaderSignature($config = array()) {
		$config = isset($config) ? (count($config) > 0 ? $config : $this->config) : $this->config;		
		$dt = new DateTime(null, new DateTimeZone($this->config["timezone"]));
		$dt->add(new DateInterval($config["addTime"]));
		$ts = $dt->getTimestamp();
		$var = $config["id"]."&".$ts;
		$sign = base64_encode(hash_hmac("sha256", utf8_encode($var), utf8_encode($config["key"]), true));
		$this->headers = array(
			"Accept: application/Json",
			"X-cons-id: ".$config["id"],
			"X-timestamp: ".$ts,
			"X-signature: ".$sign
		);
	}

	protected function writeBridgeLog($data=[]) {
		$isCreate = isset($data["ID"]) ? false : true;
		if($isCreate) $data["ID"] = Generator::generateIdBridgeLog();
		$data["JENIS"] = $this->jenisBridge;
		$this->bridgeLog->simpanData($data, $isCreate);
		return $data["ID"];
	}
	
	protected function sendRequest($action = "", $method = "GET", $data = "", $contenType = "application/json", $url = "") {
		$curl = curl_init();
		$this->setHeaderSignature();
		$url = ($url == '' ? $this->config["url"] : $url);
		$id = $this->writeBridgeLog([
			"URL" => $url."/".$action,
			"REQUEST" => $data,
			"ACCESS_FROM_IP" => $_SERVER['REMOTE_ADDR']
		]);
		curl_setopt($curl, CURLOPT_URL, $url."/".$action);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$this->headers[count($this->headers)] = "Content-type: ".$contenType;
		$this->headers[count($this->headers)] = "Content-length: ".strlen($data);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
		
		$result = curl_exec($curl);
		$result = str_replace("metaData", "metadata", $result);
		$this->writeBridgeLog([
			"ID" => $id,
			"RESPONSE" => $result
		]);
		file_put_contents("logs/bpjs_log.txt", $result);
		if($data !== "") file_put_contents("logs/bpjs_post_data_log.txt", $data);
		file_put_contents("logs/bpjs_url.txt", $url."/".$action);
		file_put_contents("logs/bpjs_headers.txt", json_encode($this->headers));
		
		curl_close($curl);
		
		try {
			$val = Json::decode($result);
		} catch(\Exception $e) {
			$result = Json::encode(array(
				"metadata" => array(
					"code" => 502,
					"message" => $this::RESULT_IS_NULL
				),
				"response" => null
			));
		}
		return $result;
	}
	
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
				, "kdCabang" => $peserta->provUmum->kdCabang
				, "nmCabang" => $peserta->provUmum->nmCabang
				, "kdJenisPeserta" => $peserta->jenisPeserta->kdJenisPeserta
				, "nmJenisPeserta" => $peserta->jenisPeserta->nmJenisPeserta
				, "kdKelas" => $peserta->kelasTanggungan->kdKelas
				, "nmKelas" => $peserta->kelasTanggungan->nmKelas
				, "tglTAT" => isset($peserta->tglTAT) ? $peserta->tglTAT : new \Zend\Db\Sql\Expression('NULL')
				, "tglTMT" => isset($peserta->tglTMT) ? $peserta->tglTMT : new \Zend\Db\Sql\Expression('NULL')
				, "kdStatusPeserta" => isset($peserta->statusPeserta) ? $peserta->statusPeserta->kode : new \Zend\Db\Sql\Expression('NULL')
				, "ketStatusPeserta" => isset($peserta->statusPeserta) ? $peserta->statusPeserta->keterangan : new \Zend\Db\Sql\Expression('NULL')
				, "umurSaatPelayanan" => isset($peserta->umur) ? $peserta->umur->umurSaatPelayanan : new \Zend\Db\Sql\Expression('NULL')
				, "umurSekarang" => isset($peserta->umur) ? $peserta->umur->umurSekarang : new \Zend\Db\Sql\Expression('NULL')
				, "dinsos" => isset($peserta->informasi) ? $peserta->informasi->dinsos : new \Zend\Db\Sql\Expression('NULL')
				, "iuran" => isset($peserta->informasi) ? $peserta->informasi->iuran : new \Zend\Db\Sql\Expression('NULL')
				, "noSKTM" => isset($peserta->informasi) ? $peserta->informasi->noSKTM : new \Zend\Db\Sql\Expression('NULL')
				, "prolanisPRB" => isset($peserta->informasi) ? $peserta->informasi->prolanisPRB : new \Zend\Db\Sql\Expression('NULL')
				
			);
			if(count($rPeserta) > 0) {
				if($norm == 0) unset($data["norm"]);
			}
			$this->peserta->simpan($data, count($rPeserta) == 0);			
		}
	}
		
	/* Peserta>>>No.Kartu BPJS
	 * cari peserta berdasarkan Nomor Kartu BPJS 
	 * @parameter 
	 *   $params string | array("noKartu" => value, "norm" => value)
	 *   $uri string
	 */
	function cariPesertaDgnNoKartuBPJS($params, $uri = "Peserta/peserta/") {
		$nomor = $params;
		$norm = 0;
		if(is_array($params)) {
			if(isset($params["noKartu"])) $nomor = $params["noKartu"];
			if(isset($params["norm"])) $norm = $params["norm"];
		}
		
		$result = Json::decode($this->sendRequest($uri.$nomor));
		if($result) {
			$result->metadata->requestId = $this->config["koders"];
			if($result->metadata->code == 200 && (trim($result->metadata->message) == '200' || trim($result->metadata->message) == 'OK')) {
				$peserta = $result->response->peserta;
				$this->storePeserta($norm, $peserta);
			}
		} else {
			return json_decode(json_encode(array(					
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - cari peserta berdasarkan Nomor Kartu BPJS<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* Peserta>>>NIK
	 * cari perserta berdasarkan Nomor Induk Kependudukan (e-KTP)
	 * @parameter 
	 *   $params string | array("nik" => value, "norm" => value)
	 *   $uri string
	 */
	function cariPesertaDgnNIK($params, $uri = "Peserta/peserta/nik/") {
		$nomor = $params;
		$norm = 0;
		if(is_array($params)) {
			if(isset($params["nik"])) $nomor = $params["nik"];
			if(isset($params["norm"])) $norm = $params["norm"];
		}
		
		$result = Json::decode($this->sendRequest($uri.$nomor));
		if($result) {
			$result->metadata->requestId = $this->config["koders"];
			if($result->metadata->code == 200 && (trim($result->metadata->message) == '200' || trim($result->metadata->message) == 'OK')) {
				$peserta = $result->response->peserta;
				$this->storePeserta($norm, $peserta);
			}
		} else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - cari perserta berdasarkan Nomor Induk Kependudukan (e-KTP)<br/>".$this::RESULT_IS_NULL,
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
	function cariRujukanDgnNoRujukan($params, $uri = "Rujukan/rujukan/") {
		$nomor = $params;
		if(is_array($params)) {
			if(isset($params["nomor"])) $nomor = $params["nomor"];
		}
		
		$result = Json::decode($this->sendRequest($uri.$nomor));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - cari data rujukan berdasarkan Nomor rujukan<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* Rujukan>>>Data Rujukan Berdasarkan Nomor kartu
	 * cari data rujukan berdasarkan Nomor Kartu BPJS
	 * @parameter 
	 *   $params string | array("noKartu" => value)
	 *   $uri string
	 */
	function cariRujukanDgnNoKartuBPJS($params, $uri = "Rujukan/rujukan/peserta/") {
		$nomor = $params;
		if(is_array($params)) {
			if(isset($params["noKartu"])) $nomor = $params["noKartu"];
		}
		$result = Json::decode($this->sendRequest($uri.$nomor));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - cari data rujukan berdasarkan Nomor Kartu BPJS<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
	}
	
	/* Rujukan>>>Insert Rujukan
	 */
	function insertRujukan($params, $uri = "Rujukan/insert") {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Insert Rujukan tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
	}
	
	/* Rujukan>>>Update Rujukan
	 */
	function updateRujukan($params, $uri = "Rujukan/update") {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Update Rujukan tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
	}
	
	/* Rujukan>>>Delete Rujukan
	 */
	function deleteRujukan($params, $uri = "Rujukan/delete") {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Delete Rujukan tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
	}
	
	/*
	 * cari daftar peserta yang rujukan berdasarkan tanggal rujukan
	 * format tanggal Y-m-d
	 */
	function cariDaftarPersertaRujukanDgnTglRujukan($tanggal, $start, $limit) {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - cari daftar peserta yang rujukan tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
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
	function generateNoSEP($params = array(), $uri = "SEP/sep") {
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

			$result = json_decode($this->sendRequest($uri, "POST", $data, "application/x-www-form-urlencoded"));				
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
	 *		, "noMr" => value
	 *		, "lakaLantas" => value
	 *		, "lokasiLaka" => value
	 *)
	 *   $uri string
	 */	
	function updateSEP($params, $uri = "Sep/Update") {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Update SEP tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
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
	function updateTanggalPulang($params, $uri = "Sep/Sep/updtglplg") {
		$data = "<request>
			<data>
			  <t_sep>
					<noSep>".$params["noSEP"]."</noSep>
					<tglPlg>".$params["tglPlg"]."</tglPlg>
					<ppkPelayanan>".$this->config["koders"]."</ppkPelayanan>
			  </t_sep>
			</data>
			</request>";
		
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
	function mappingDataTransaksi($params, $uri = "Sep/map/trans") {
		$data = "<request>
			<data>
				<t_map_sep>
					<noSep>".$params["noSEP"]."</noSep>
					<noTrans>".$params["noTrans"]."</noTrans>
					<ppkPelayanan>".$this->config["koders"]."</ppkPelayanan>
				</t_map_sep>
			</data>
			</request>";
		
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
	function batalkanSEP($params, $uri = "SEP/sep") {
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
	function cekSEP($params, $uri = "SEP/sep/") {
		$noSEP = $params;
		if(is_array($params)) {
			if(isset($params["noSEP"])) $noSEP = $params["noSEP"];
		}
		$result = json_decode($this->sendRequest($uri.$noSEP));
		if($result) $result->metadata->requestId = $this->config["koders"];
		else {
			return json_decode(json_encode(array(			
				'metadata' => array(
					'message' => "SIMRSInfo::Error Request Service - Cek SEP<br/>".$this::RESULT_IS_NULL,
					'code' => 502,
					'requestId'=> $this->config["koders"]
				)
			)));
		}
		return $result;
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
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Pengajuan SEP tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
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
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Aproval Pengajuan SEP tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
	}
	
	/* SEP>>>Data Riwayat Pelayanan Peserta
	 * Mencari Data Riwayat Pelayanan Peserta
	 * @parameter 
	 *   $params array(
	 *		"noKartu" => value
	 *)
	 *   $uri string
	 */
	function riwayatPelayananPeserta($params, $uri = "sep/peserta/") {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - cari Data Riwayat Pelayanan Peserta tidak disupport<br/>",
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
	 *		"cari" => value | 0 (tanggal masuk), 1 (tanggal keluar
	 *		"status" => value | 00 (Klaim_Baru); 10 (Klaim_Terima_CBG); 21 (Klaim_Layak); 22 (Klaim_Tidak_Layak); 23 (Klaim_Pending); 30 (TerVerifikasi); 40 (Proses_Cabang)
	 *)
	 *   $uri string
	 */
	function monitoringVerifikasiKlaim($params, $uri = "sep/integrated/Kunjungan/") {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Monitoring Verifikasi Klaim Pelayanan tidak disupport<br/>",
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
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Integrasi SEP dengan Inacbg tidak disupport<br/>",
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
				'message' => "SIMRSInfo::Error Request Service - Data Kunjungan Peserta tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
	}
	
	/* Referensi>>>Poli
	 * ambil daftar poli bpjs
	 */
	function poli($uri = "poli/ref/poli") {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - ambil daftar referensi poli bpjs tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
	}
	
	/* Referensi>>>Diagnosa	 
	 * Tampil data diagnosa	 
	 */
	function diagnosa($params, $uri = "diagnosa/cbg/diagnosa/") {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Pencarian data diagnosa tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
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
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Pencarian data procedure/tindakan tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
	}
	
	/* Referensi>>>Kelas Rawat
	 * Pencarian data kelas rawat	
	 */
	function kelasRawat($uri = "referensi/kelasrawat") {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Pencarian data kelas rawat tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
	}
	
	/* Referensi>>>Dokter
	 * Pencarian data dokter DPJP
	 * @parameter 
	 *   $params array(
	 *		"query" => value | nama dokter/DPJP
	 * )
	 */
	function dokter($params, $uri = "referensi/dokter/") {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Pencarian data dokter DPJP tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
	}
	
	/* Referensi>>>Spesialistik
	 * Pencarian data spesialistik
	 */
	function spesialistik($uri = "referensi/spesialistik") {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Pencarian data spesialistik tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
	}
	
	/* Referensi>>>Ruang Rawat
	 * Pencarian data ruang rawat
	 */
	function ruangRawat($uri = "referensi/ruangrawat") {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Pencarian data ruang rawat tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
	}
	
	/* Referensi>>>Cara Keluar
	 * Pencarian data cara keluar
	 */
	function caraKeluar($uri = "referensi/carakeluar") {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Pencarian data cara keluar tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
	}
	
	/* Referensi>>>Pasca Pulang
	 * Pencarian data pasca pulang
	 */
	function pascaPulang($uri = "referensi/pascapulang") {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Pencarian data pasca pulang tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
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
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Pencarian data fasilitas kesehatan tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
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
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Monitoring Data Kunjungan tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
	}
	
	/* Monitoring>>>Data Klaim
	 * Data Klaim
	 * @parameter 
	 *   $params array(
	 *		"tanggal" => value | yyyy-MM-dd	 
	 *		"jnsPelayanan" => value | 1 (Rawat Inap), 2 (Rawat Jalan)
	 *		"cari" => value | 0 (tanggal masuk), 1 (tanggal keluar)
	 *		"status" => value | 1 (Proses Verifikasi); 2 (Pending Verifikasi); 3 (Klaim)
	 *)
	 *   $uri string
	 */
	function monitoringKlaim($params, $uri = "Monitoring/Klaim/") {
		return json_decode(json_encode(array(					
			'metadata' => array(
				'message' => "SIMRSInfo::Error Request Service - Monitoring Data Klaim tidak disupport<br/>",
				'code' => 502,
				'requestId'=> $this->config["koders"]
			)
		)));
	}
}