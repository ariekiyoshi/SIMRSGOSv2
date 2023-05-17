<?php
/**
 * INACBGService
 * @author hariansyah
 * 
 */
namespace INACBGService\V5;
	
use Zend\Json\Json;
use Zend\Db\Adapter\Adapter;
use Zend\Stdlib\Parameters;
use DBService\DatabaseService;
use INACBGService\V1\Rest\HasilGrouping\HasilGroupingService;

use INACBGService\Crypto;

use Aplikasi\db\bridge_log\Service as BridgeLogService;
use DBService\generator\Generator;

class Service {
	private $config;
	private $adapter;
	private $hasilGrouping;
	protected $bridgeLog;
	protected $jenisBridge = 2;
	
	function __construct($config, $adapter) {
		$this->config = $config;
		$this->adapter = $adapter;
		
		$this->hasilGrouping = new HasilGroupingService();
		$this->bridgeLog = new BridgeLogService();
	}

	protected function writeBridgeLog($data=[]) {
		$isCreate = isset($data["ID"]) ? false : true;
		if($isCreate) $data["ID"] = Generator::generateIdBridgeLog();
		$data["JENIS"] = $this->jenisBridge;
		$this->bridgeLog->simpanData($data, $isCreate);
		return $data["ID"];
	}
	
	private function sendRequest($method = "GET", $data = "", $contenType = "application/json", $url = "") {
		$curl = curl_init();
		
		$headers = array(
			"Accept: application/Json"
		);		
		
		$url = ($url == '' ? $this->config["url"] : $url);

		$id = $this->writeBridgeLog([
			"URL" => $url,
			"REQUEST" => $data,
			"ACCESS_FROM_IP" => $_SERVER['REMOTE_ADDR']
		]);
		
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$headers[count($headers)] = "Content-type: ".$contenType;
		$headers[count($headers)] = "Content-length: ".strlen($data);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		
		//curl_setopt($curl, CURLOPT_FAILONERROR, true); 
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		
		$result = curl_exec($curl);
		
		curl_close($curl);

		$this->writeBridgeLog([
			"ID" => $id,
			"RESPONSE" => $result
		]);
		
		return $result;
	}
	
	/**
	  * @method klaimBaru
	  * @param $data array
	  * 1. Membuat Klaim baru
	  * => Data Request
	  * {
	  *		"metadata": {
	  *			"method": "new_claim"
	  *		},
	  *		"data": {
	  *			"nomor_kartu": "0000668873981",
	  *			"nomor_sep": "0301R00112140006067",
	  *			"nomor_rm": "123-45-67",
	  *			"nama_pasien": "SATINI",
	  *			"tgl_lahir": "1940-01-01 02:00:00",
	  *			"gender": "1=L;2=P"
	  *		}
	  *	}
	  * @return data response
	  * => Data Response
	  * {
	  *		"metadata": {
	  *			"code":"200",
	  *			"message":"OK"
	  *		}
	  *	}
	  *	Jika ada duplikasi nomor SEP:
	  *	{
	  *		"metadata": {
	  *			"code": 400,
	  *			"message": "Duplikasi nomor SEP"
	  *		}
	  *	}
	 */		
	public function klaimBaru($data = array()) {
		$request = array(
			"metadata" => array(
				"method" => "new_claim"
			),
			"data" => $data
		);
		
		$data = json_encode($request);		
		$data = Crypto::encrypt($data, $this->config["key"]);
		
		$result = $this->sendRequest("POST", $data, "application/json");
		$result = Crypto::decrypt($result, $this->config["key"]);
		
		return json_decode($result);
	}
	
	/**
	  * @method updateDataPasien
	  * @param $data array
	  * 1. Membuat Update Data Pasien
	  * => Data Request
	  * {
	  *		"metadata": {
	  *			"method": "update_patient",
	  *			"nomor_rm": "123-45-67"
	  *		},
	  *		"data": {
	  *			"nomor_kartu": "0000668873981",
	  *			"nomor_rm": "123-45-67",
	  *			"nama_pasien": "SATINI",
	  *			"tgl_lahir": "1940-01-01 02:00:00",
	  *			"gender": "1=L;2=P"
	  *		}
	  *	}
	  * @return data response
	  * => Data Response
	  * {
	  *		"metadata": {
	  *			"code":"200",
	  *			"message":"OK"
	  *		}
	  *	}
	  *	Jika ada duplikasi nomor SEP:
	  *	{
	  *		"metadata": {
	  *			"code": 400,
	  *			"message": "Duplikasi nomor SEP"
	  *		}
	  *	}
	 */		
	public function updateDataPasien($data = array()) {
		$request = array(
			"metadata" => array(
				"method" => "update_patient",
				"nomor_rm" => $data["nomor_rm"]
			),
			"data" => $data
		);
		
		$data = json_encode($request);
		$data = Crypto::encrypt($data, $this->config["key"]);
		
		$result = $this->sendRequest("POST", $data, "application/json");
		return json_decode(Crypto::decrypt($result, $this->config["key"]));
	}
	
	/**
	  * @method hapusDataPasien
	  * @param $data array
	  * 1. Membuat Hapus Data Pasien
	  * => Data Request
	  * {
	  *		"metadata": {
	  *			"method": "delete_patient"
	  *		},
	  *		"data": {
	  *			"nomor_rm": "123-45-67",
	  *			"coder_nik": "123123123123"
	  *		}
	  *	}
	  * @return data response
	  * => Data Response
	  * {
	  *		"metadata": {
	  *			"code":"200",
	  *			"message":"OK"
	  *		}
	  *	}	 
	 */		
	public function hapusDataPasien($data = array()) {
		$request = array(
			"metadata" => array(
				"method" => "delete_patient"
			),
			"data" => $data
		);
		
		$data = json_encode($request);
		$data = Crypto::encrypt($data, $this->config["key"]);
		
		$result = $this->sendRequest("POST", $data, "application/json");
		return json_decode(Crypto::decrypt($result, $this->config["key"]));
	}
	
	/**
	  * @method updateDataKlaim
	  * @param $data array
	  * 2. Untuk mengisi/update data klaim:
	  * => Data Request
	  * {
	  *		"metadata": {
	  *			"method": "set_claim_data",
	  *			"nomor_sep": "0301R00112140006067"
	  *		},
	  *		"data": {
	  *			"nomor_sep": "0301R00112140006067",
	  *			"nomor_kartu": "0301R00112140006067",
	  *			"tgl_masuk": "2015-07-01 07:00:00",
	  *			"tgl_pulang": "2016-01-07 15:00:00",
	  *			"jenis_rawat": "1",
	  *			"kelas_rawat": "3",
	  *			"adl_sub_acute": "15",
	  *			"adl_chronic": "12",
	  *			"icu_indikator": "1",
	  *			"icu_los": "2",
	  *			"ventilator_hour": "5",
	  
	  *			"upgrade_class_ind": "1",
	  *			"upgrade_class_class": "vip",
	  *			"upgrade_class_los": "5",
	  
	  *			"birth_weight": "",
	  *			"discharge_status": "1",
	  *			"diagnosa": �D56.1#A41.3",
	  *			"procedure": "36.6#88.09",	  
	  *			"tarif_rs": "2500000", // FOR INACBG BEFORE
	  * ADD NEW FIELD INACBG 5.2 DISTRIBUSI TARIF RS
	  *			"tarif_rs" : {
	  *				"prosedur_non_bedah": 0,	
	  *				"prosedur_bedah": 0,		
	  *				"konsultasi": 0,
	  *				"tenaga_ahli": 0,	
	  *				"keperawatan": 0,		
	  *				"penunjang": 0,			
	  *				"radiologi": 0,
	  *				"laboratorium": 0,	
	  *				"pelayanan_darah": 0,	
	  *				"rehabilitasi": 0,		
	  *				"kamar": 0,
	  *				"rawat_intensif": 0,	
	  *				"obat": 0,
	  *             "obat_kronis": 0,          #ADD 5.3
	  *             "obat_kemoterapi": 0,      #ADD 5.3				
	  *				"alkes": 0, 
	  *				"bmhp": 0,			
	  *				"sewa_alat": 0,
	  *			}
	  *			"tarif_poli_eks": "2500000",
	  *			"nama_dokter": "dr. Erna",	  	  
	  *			"kode_tarif": "AP",
	  *			"payor_id": "3",
	  *			"payor_cd": "JKN",
	  *         "cob_cd": "0001",          #ADD 5.3
	  *			"coder_nik": "123123123123"
	  *		}
	  *	}
	  * @return data response
	  * => Data Response
	  * {
	  *		"metadata": {
	  *			"code":"200",
	  *			"message":"OK"
	  *		}
	  *	}		
	 */		
	public function updateDataKlaim($data = array()) {
		$request = array(
			"metadata" => array(
				"method" => "set_claim_data",
				"nomor_sep" => $data["nomor_sep"]
			),
			"data" => $data
		);
		
		$data = json_encode($request);
		//file_put_contents("grouper.txt", $data);		
		$data = Crypto::encrypt($data, $this->config["key"]);
		
		return json_decode(Crypto::decrypt($this->sendRequest("POST", $data, "application/json"), $this->config["key"])); 
	}
	
	/**
	  * @method grouping
	  * @param $data array
	  * 3 & 4 Grouping
	  * => Data Request
	  * {
	  *		"metadata": {
	  *			"method": "grouper",
	  *			"stage": "1" / "2"
	  *		},
	  *		"data": {
	  *			"nomor_sep":"0301R00112140006067",
	  *			"special_cmg": "DD04" // stage 2
	  *		}
	  *	}
	  * @return data response
	  * => Data Response
	  * {
	  *		"metadata": {
	  *			"code":"200",
	  *			"message":"OK"
	  *		},
	  *		"response": {
	  *			"cbg": {
	  *				"code": "D-4-13-III",
	  *				"description": "GANGGUAN SEL . . ANEMIA SEL SICKLE (BERAT)",
	  *				"tariff": "7501700"
	  *			},
	  *			// untuk stage 2 
	  *			"special_cmg": [{
	  *				"code": "DD-04-II",
	  *				"description": "DEFERASIROX (IP)",
	  *				"tariff": 6216000,
	  *				"type": "Special Drug"	  
	  *			}]
	  *		},
	  *		"special_cmg_option": [
	  *			{
	  *				"code": "DD02",
	  *				"description": "Deferiprone (IP)",
	  *				"type": "Special Drug"
	  *			},
	  *			{
	  *				"code": "DD03",
	  *				"description": "Deferoksamin (IP)",
	  *				"type": "Special Drug"
	  *			},
	  *			{
	  *				"code": "DD04",
	  *				"description": "Deferasirox (IP)",
	  *				"type": "Special Drug"
	  *			}
	  *		]
	  *	}		
	 */		
	public function grouping($data = array()) {
		$stage = 1;
		$request = array(
			"metadata" => array(
				"method" => "grouper",
				"stage" => $stage
			),
			"data" => $data
		);
		
		if(isset($data["special_cmg"])) {
			$stage = 2;
			$request["metadata"]["stage"] = $stage;
		}
		
		$data = json_encode($request);
		$data = Crypto::encrypt($data, $this->config["key"]);
		
		return json_decode(Crypto::decrypt($this->sendRequest("POST", $data, "application/json"), $this->config["key"]));
	}
	
	/**
	  * @method finalKlaim
	  * @param $data array
	  * 5. Untuk finalisasi klaim
	  * => Data Request
	  * {
	  *		"metadata": {
	  *			"method": "claim_final"
	  *		},
	  *		"data": {
	  *			"nomor_sep":"0301R00112140006067",
	  *			"coder_nik":"NIK Pegawai"
	  *		}
	  *	}
	  * @return data response
	  * => Data Response
	  * {
	  *		"metadata": {
	  *			"code":"200",
	  *			"message":"OK"
	  *		}
	  *	}		
	 */		
	public function finalKlaim($data = array()) {
		$request = array(
			"metadata" => array(
				"method" => "claim_final"
			),
			"data" => $data
		);
		
		$data = json_encode($request);
		$data = Crypto::encrypt($data, $this->config["key"]);
		
		return json_decode(Crypto::decrypt($this->sendRequest("POST", $data, "application/json"), $this->config["key"])); 
	}
	
	/**
	  * @method reEditKlaim
	  * @param $data array
	  6. Untuk mengedit ulang klaim
	  * => Data Request
	  * {
	  *		"metadata": {
	  *			"method": "reedit_claim"
	  *		},
	  *		"data": {
	  *			"nomor_sep":"0301R00112140006067"
	  *		}
	  *	}
	  * @return data response
	  * => Data Response
	  * {
	  *		"metadata": {
	  *			"code":"200",
	  *			"message":"OK"
	  *		}
	  *	}		
	 */		
	public function reEditKlaim($data = array()) {
		$request = array(
			"metadata" => array(
				"method" => "reedit_claim"
			),
			"data" => $data
		);
		
		$data = json_encode($request);
		$data = Crypto::encrypt($data, $this->config["key"]);
		
		return json_decode(Crypto::decrypt($this->sendRequest("POST", $data, "application/json"), $this->config["key"])); 
	}
	
	/**
	  * @method kirimKlaim
	  * @param $data array
	  * 7. Untuk mengirim klaim ke DC per tanggal
	  * => Data Request
	  * {
	  *		"metadata": {
	  *			"method": "send_claim"
	  *		},
	  *		"data": {
	  *			"start_dt":"2016-01-07",
	  *			"stop_dt":"2016-01-07",
	  *			"jenis_rawat":"1"
	  *		}
	  *	}
	  * @return data response
	  * => Data Response
	  * {
	  *		"metadata": {
	  *			"code":"200",
	  *			"message":"OK"
	  *		},
	  *		"response": {
	  *			"data": [
	  *				{
	  *					"SEP": "0301R00112140006067",
	  *					"tgl_pulang": "2016-01-07 15:00:00",
	  *					"KEMENKES_DC_Status": "sent",
	  *					"BPJS_DC_Status": "unsent"
	  *				}
	  *			]
	  *		}		  
	  *	}		
	 */		
	public function kirimKlaim($data = array()) {
		$request = array(
			"metadata" => array(
				"method" => "send_claim"
			),
			"data" => $data
		);
		
		$data = json_encode($request);
		$data = Crypto::encrypt($data, $this->config["key"]);
		
		return json_decode(Crypto::decrypt($this->sendRequest("POST", $data, "application/json"), $this->config["key"])); 
	}
	
	/**
	  * @method kirimKlaimIndividual
	  * @param $data array
	  * 7. Untuk mengirim klaim ke DC berdasarkan nomor sep
	  * => Data Request
	  * {
	  *		"metadata": {
	  *			"method": "send_claim_individual"
	  *		},
	  *		"data": {
	  *			"nomor_sep":"0301R00112140006067"
	  *		}
	  *	}
	  * @return data response
	  * => Data Response
	  * {
	  *		"metadata": {
	  *			"code":"200",
	  *			"message":"OK"
	  *		},
	  *		"response": {
	  *			"data": [
	  *				{
	  *					"SEP": "0301R00112140006067",
	  *					"tgl_pulang": "2016-01-07 15:00:00",
	  *					"KEMENKES_DC_Status": "sent",
	  *					"BPJS_DC_Status": "unsent"
	  *				}
	  *			]
	  *		}		  
	  *	}		
	 */		
	public function kirimKlaimIndividual($data = array()) {
		$request = array(
			"metadata" => array(
				"method" => "send_claim_individual"
			),
			"data" => $data
		);
		
		$data = json_encode($request);
		$data = Crypto::encrypt($data, $this->config["key"]);
		
		return json_decode(Crypto::decrypt($this->sendRequest("POST", $data, "application/json"), $this->config["key"])); 
	}
	
	/**
	  * @method batalKlaim
	  * @param $data array
	  * Untuk membatalkan pengklaiman
	  * => Data Request
	  * {
	  *		"metadata": {
	  *			"method": "delete_claim"
	  *		},
	  *		"data": {
	  *			"nopen":"0301R00112140006067"
	  *			"nomor_sep":"0301R00112140006067"
	  *			"coder_nik":"1234567890123456"
	  *		}
	  *	}
	  * @return data response
	  * => Data Response
	  * {
	  *		"metadata": {
	  *			"code":"200",
	  *			"message":"OK"
	  *		}
	  *	}		
	 */		
	public function batalKlaim($data = array()) {
		$nopen = $data["nopen"];
		$hasilGrouping = $this->hasilGrouping->load(array("NOPEN" => $nopen));
		
		unset($data["nopen"]);
		unset($data["tipe"]);
		
		if(count($hasilGrouping) > 0) {
			$this->hasilGrouping->hapus(array("NOPEN" => $nopen));
		}
		
		$request = array(
			"metadata" => array(
				"method" => "delete_claim"
			),
			"data" => $data
		);		
		
		$data = json_encode($request);
		$data = Crypto::encrypt($data, $this->config["key"]);
		
		$result = json_decode(Crypto::decrypt($this->sendRequest("POST", $data, "application/json"), $this->config["key"])); 		
		return $result;
	}
	
	/**
	  * @method grouper 
	  * @params $data array
	  * => Data Request
	  * {
	  *		"norm": 123456,
	  *		"nm_pasien": "Nama Pasien",
	  *		"jns_kelamin": "Jenis Kelamin",	  
	  *		"tgl_lahir": "Tanggal Lahir",
	  *		"jns_pbyrn": "Jenis Pembayaran",
	  *		"no_peserta": "No. kartu Peserta",
	  *		"no_sep": "No. SEP",
	  *		"jns_perawatan": "Jenis Perawatan",
	  *		"kls_perawatan": "Kelas Perawatan",
	  *		"tgl_masuk": "Tanggal Masuk",
	  *		"tgl_keluar": "Tanggal Keluar",
	  *		"cara_keluar": "Cara Keluar / Pulang",
	  *		"dpjp": "Dokter Penanggungjawab",
	  *		"berat_lahir": "Berat Bayi Lahir",
	  *		"tarif_rs": "Tarif Rumah Sakit
	  *	NEW IN INACBG 5.2
	  *		"prosedur_non_bedah": 0,	
	  *		"prosedur_bedah": 0,		
	  *		"konsultasi": 0,
	  *		"tenaga_ahli": 0,	
	  *		"keperawatan": 0,		
	  *		"penunjang": 0,			
	  *		"radiologi": 0,
	  *		"laboratorium": 0,	
	  *		"pelayanan_darah": 0,	
	  *		"rehabilitasi": 0,		
	  *		"kamar": 0,
	  *		"rawat_intensif": 0,	
	  *		"obat": 0,
	  *     "obat_kronis": 0,
	  *     "obat_kemoterapi": 0,				
	  *		"alkes": 0, 
	  *		"bmhp": 0,			
	  *		"sewa_alat": 0,
	  *		"tarif_poli_eks": "Tarif Poli Eksekutif",
	  *		"srt_rujukan": "Ada Surat Rujukan",
	  *		"bhp": "Bahan Habis Pakai",
	  *		"severity3": "Severity3",
	  *		"diag1": "Diagnosa ke 1", // sampai ke
	  *		"diag30": "Diagnosa ke 30",
	  *		"proc1": "Procedure ke 1", // sampai ke
	  *		"proc30": "Procedure ke 30",
	  *		"adl_sub_acute": "ADL Sub Akut",
	  *		"adl_chronic": "ADL Chronic",
	  *		"icu_indikator": "ICU Indikator",
	  *		"icu_los": "ICU LOS",
	  *		"ventilator_hour": "Ventilator Hour",
	  *		"spec_proc": "Special Procedure",
	  *		"spec_dr": "Special Drug",
	  *		"spec_inv": "Special Investigation",
	  *		"spec_prosth": "Special Prosthesis",
	  *     "cob_cd": "0001",
	  *		"nopen": "No. Pendaftaran",
	  *		"user": "Pengguna ID",
	  *		"user_nik": "NIK Pengguna",	  
	  *		"status": "0 = false (Belum Final) | 1 = true (Final)",
	  *		"kirim": "0 = false (Belum Kirim) | 1 = true (Sudah Kirim)",
	  *		"type" : "tipe inacbg"
	  *	} 
	  * @return
	  */
	
	function grouper($data = array()) {
		$data = is_array($data) ? $data : (array) $data;
		$response = array();
		$hasilGrouping = $this->hasilGrouping->load(array("NOPEN" => $data["nopen"]));
		
		$result = array(
			"metaData" => array(
				"code" => "500",
				"message" => "Gagal Grouping",
			),
			"response" => array(
				"Grouper" => array(
					"Drug" => array(
						"Deskripsi" => null,
						"Kode" => "None",
						"Tarif" => 0
					),	
					"Investigation" => array(
						"Deskripsi" => null,
						"Kode" => "None",
						"Tarif" => 0	
					),	
					"Procedure" => array(
						"Deskripsi" => null,
						"Kode" => "None",
						"Tarif" => 0	
					),	
					"Prosthesis" => array(
						"Deskripsi" => null,
						"Kode" => "None",
						"Tarif" => 0
					),	
					"SubAcute" => array(
						"Deskripsi" => null,
						"Kode" => "None",
						"Tarif" => 0
					),
					"Chronic" => array(
						"Deskripsi" => null,
						"Kode" => "None",
						"Tarif" => 0
					),
					"deskripsi" => "",
					"kodeInacbg" => "",
					"noSep" => $data["no_sep"],
					"tarifGruper" => 0,
					"totalTarif" => 0,
					"tarifKelas1" => 0,
					"tarifKelas2" => 0,
					"tarifKelas3" => 0
				),
				"kirimKlaim" => array(
					"DC_KEMKES" => 0,
					"DC_BPJS" => 0
				)
			)
		);		
		/* buat klaim baru */
		if(count($hasilGrouping) == 0) {
			$klaimBaru = $this->klaimBaru(array(
				"nomor_kartu" => $data["no_peserta"],
				"nomor_sep" => $data["no_sep"],
				"nomor_rm" => $data["norm"],
				"nama_pasien" => $data["nm_pasien"],
				"tgl_lahir" => $data["tgl_lahir"],
				"gender" => $data["jns_kelamin"]
			));
			
			$response["klaimBaru"] = $klaimBaru;
		} else {
			/* jika sebelumnya final maka reFinalKlaim */
			if($hasilGrouping[0]["STATUS"] == 1) {
				$reFinalKlaim = $this->reEditKlaim(array(
					"nomor_sep" => $data["no_sep"]
				));
				$response["reFinalKlaim"] = $reFinalKlaim;
			}			
			
			/* update data pasien */
			$updateDataPasien = $this->updateDataPasien(array(
				"nomor_kartu" => $data["no_peserta"],
				"nomor_rm" => $data["norm"],
				"nama_pasien" => $data["nm_pasien"],
				"tgl_lahir" => $data["tgl_lahir"],
				"gender" => $data["jns_kelamin"]
			));
			
			$response["updateDataPasien"] = $updateDataPasien;
		}
				
		//file_put_contents("logs/grouper_post_data.txt", json_encode($data));
		$diags = array();
		$notIsSetCount = 0;
		for($i = 1; $i <= 30; $i++) {
			$diag = "diag".$i;
			if(isset($data[$diag])) $diags[] = $data[$diag];
			else $notIsSetCount++;
			
			if($notIsSetCount > 1) break;
		}
		$diags = implode("#", $diags);
		
		$procs = array();
		$notIsSetCount = 0;
		for($i = 1; $i <= 30; $i++) {
			$proc = "proc".$i;
			if(isset($data[$proc])) $procs[] = $data[$proc];
			else $notIsSetCount++;
			
			if($notIsSetCount > 1) break;
		}
		$procs = implode("#", $procs);
		
		$tarifRs = $data["tarif_rs"];
		$dataUpdateKlaim = array(
			"nomor_sep" => $data["no_sep"],
			"nomor_kartu" => $data["no_peserta"],
			"tgl_masuk" => $data["tgl_masuk"],
			"tgl_pulang" => $data["tgl_keluar"],
			"jenis_rawat" => $data["jns_perawatan"],
			"kelas_rawat" => $data["kls_perawatan"],
			
			"adl_sub_acute" => $data["adl_sub_acute"],
			"adl_chronic" => $data["adl_chronic"],
			"icu_indikator" => isset($data["icu_indikator"]) ? $data["icu_indikator"] : 0,
			"icu_los" => isset($data["icu_los"]) ? isset($data["icu_los"]) : 0,
			"ventilator_hour" => isset($data["ventilator_hour"]) ? $data["ventilator_hour"] : 0,
			
			"upgrade_class_ind" => isset($data["upgrade_class_ind"]) ? $data["upgrade_class_ind"] : 0,
			"upgrade_class_class" => isset($data["upgrade_class_class"]) ? $data["upgrade_class_class"] : "",
			"upgrade_class_los" => isset($data["upgrade_class_los"]) ? $data["upgrade_class_los"] : 0,
			
			"birth_weight" => $data["berat_lahir"],
			"discharge_status" => $data["cara_keluar"],
			"diagnosa" => $diags,
			"procedure" => $procs == '' ? '#' : $procs,
			
			"tarif_rs" => $data["tarif_rs"],
			"tarif_poli_eks" => $data["tarif_poli_eks"],
			"nama_dokter" => $data["dpjp"],
			
			"kode_tarif" => $this->config["kode_tarif"],
			"payor_id" => isset($data["jns_pbyrn"]) ? $data["jns_pbyrn"] : 3,
			"payor_cd" => isset($data["jns_pbyrn"]) ? ($data["jns_pbyrn"] == 3 ? "JKN" : $data["jns_pbyrn"]) : "JKN",		   
			"coder_nik" => $data["user_nik"]
		);			
		
		// Jika ada distribusi tarif maka ganti tarif menjadi distribusi tarif inacbg 5.2
		if($this->config["version"]["minor"] >= 2) {
		    if(isset($data["cob_cd"])) $dataUpdateKlaim["cob_cd"] = $data["cob_cd"];
			$dataUpdateKlaim["tarif_rs"] = array(
				"prosedur_non_bedah" => isset($data["prosedur_non_bedah"]) ?  $data["prosedur_non_bedah"] : 0,
				"prosedur_bedah" => isset($data["prosedur_bedah"]) ?  $data["prosedur_bedah"] : 0,
				"konsultasi" => isset($data["konsultasi"]) ?  $data["konsultasi"] : 0,
				"tenaga_ahli" => isset($data["tenaga_ahli"]) ?  $data["tenaga_ahli"] : 0,
				"keperawatan" => isset($data["keperawatan"]) ?  $data["keperawatan"] : 0,
				"penunjang" => isset($data["penunjang"]) ?  $data["penunjang"] : 0,
				"radiologi" => isset($data["radiologi"]) ?  $data["radiologi"] : 0,
				"laboratorium" => isset($data["laboratorium"]) ?  $data["laboratorium"] : 0,
				"pelayanan_darah" => isset($data["pelayanan_darah"]) ?  $data["pelayanan_darah"] : 0,
				"rehabilitasi" => isset($data["rehabilitasi"]) ?  $data["rehabilitasi"] : 0,
				"kamar" => isset($data["kamar"]) ?  $data["kamar"] : 0,
				"rawat_intensif" => isset($data["rawat_intensif"]) ?  $data["rawat_intensif"] : 0,
				"obat" => isset($data["obat"]) ?  $data["obat"] : 0,
			    "obat_kronis" => isset($data["obat_kronis"]) ?  $data["obat_kronis"] : 0,
			    "obat_kemoterapi" => isset($data["obat_kemoterapi"]) ?  $data["obat_kemoterapi"] : 0,
				"alkes" => isset($data["alkes"]) ?  $data["alkes"] : 0,
				"bmhp" => isset($data["bmhp"]) ?  $data["bmhp"] : 0,
				"sewa_alat" => isset($data["sewa_alat"]) ?  $data["sewa_alat"] : 0
			);			
		}
		
		file_put_contents("logs/grouper_post_data.txt", json_encode($dataUpdateKlaim));
		/* step 3 update klaim */
		$updateDataKlaim = $this->updateDataKlaim($dataUpdateKlaim);
		//file_put_contents("updateDataKlaim.txt", json_encode($updateDataKlaim));
		$response["updateDataKlaim"] = $updateDataKlaim;
		if($response["updateDataKlaim"]->metadata->code != 200) {
			$result["metaData"]["code"] = $response["updateDataKlaim"]->metadata->code;
			$result["metaData"]["message"] = $response["updateDataKlaim"]->metadata->message;
			
			file_put_contents("logs/grouper.txt", json_encode($response));
			return json_decode(json_encode($result));
		}
				
		/* grouping stage 1 */
		$dataGrouping = array(
			"nomor_sep" => $data["no_sep"]
		);
		$grouping = $this->grouping($dataGrouping);
		$response["grouping_stage_1"] = $grouping;
		
		$scmgs = array();			
		if($response["grouping_stage_1"]->metadata->code == 200) {
			$cmgs = isset($response["grouping_stage_1"]->special_cmg_option) ? (count($response["grouping_stage_1"]->special_cmg_option) > 0 ? $response["grouping_stage_1"]->special_cmg_option : false) : false;
			$specdr = $data["spec_dr"];
			$data["spec_dr"] = null;
			if($cmgs) {
				foreach($cmgs as $cmg) {
					if($cmg->type == "Special Procedure") $data["spec_proc"] = $cmg->code;
					if($cmg->type == "Special Prosthesis") $data["spec_prosth"] = $cmg->code;
					if($cmg->type == "Special Investigation") $data["spec_inv"] = $cmg->code;
					if($cmg->type == "Special Drug") {
						if($specdr == '') continue;
						$data["spec_dr"] = $cmg->code;						
					}
					if($cmg->type == "Special SubAcute") $data["adl_sub_acute"] = $cmg->code;
					if($cmg->type == "Special Chronic") $data["adl_chronic"] = $cmg->code;
					$scmgs[] = $cmg->code;
				}													
			}
			if($data["spec_dr"] == null) {
				$data["spec_dr"] = $specdr;
				if($specdr != '') $scmgs[] = $specdr;
			}
			
			if(count($scmgs) > 0) {
				$dataGrouping["special_cmg"] = implode("#", $scmgs);
				
				/* grouping stage 2 */
				$grouping = $this->grouping($dataGrouping);
				$response["grouping_stage_2"] = $grouping;
			}
		}			
		
		if($data["status"]) {				
			/* final grouping */
			$finalKlaim = $this->finalKlaim(array(
				"nomor_sep" => $data["no_sep"],
				"coder_nik" => $data["user_nik"]
			));
			$response["finalKlaim"] = $finalKlaim;
		}
		
		/* step 7 kirim klaim */
		if($data["kirim"]) {
			$kirimKlaim = $this->kirimKlaimIndividual(array(
				"nomor_sep" => $data["no_sep"]
			));
			$response["kirimKlaim"] = $kirimKlaim;
		}			
		
		$dcKEMKES = $dcBPJS = 0;
		
		if(isset($grouping)) {
		    file_put_contents("logs/grouper_result_01.txt", json_encode((array) $grouping));
			$result["metaData"]["code"] = $grouping->metadata->code;
			$result["metaData"]["message"] = $grouping->metadata->message;
			
			$total = 0;
			
			$cbg = isset($grouping->response->cbg) ? $grouping->response->cbg : null;
			$result["response"]["Grouper"]["kodeInacbg"] = isset($cbg->code) ? $cbg->code : "";
			$result["response"]["Grouper"]["deskripsi"] = isset($cbg->description) ? $cbg->description : "";
			$total = $result["response"]["Grouper"]["tarifGruper"] = isset($cbg->tariff) ? $cbg->tariff : 0;
			
			if(isset($grouping->response->sub_acute)) {
				$sa = $grouping->response->sub_acute;
				$result["response"]["Grouper"]["SubAcute"]["Kode"] = $sa->code;
				$result["response"]["Grouper"]["SubAcute"]["Deskripsi"] = $sa->description;
				$result["response"]["Grouper"]["SubAcute"]["Tarif"] = $sa->tariff;
			}
			
			if(isset($grouping->response->chronic)) {
				$chr = $grouping->response->chronic;
				$result["response"]["Grouper"]["Chronic"]["Kode"] = $chr->code;
				$result["response"]["Grouper"]["Chronic"]["Deskripsi"] = $chr->description;
				$result["response"]["Grouper"]["Chronic"]["Tarif"] = $chr->tariff;
			}
			
			if(isset($grouping->tarif_alt)) {
				foreach($grouping->tarif_alt as $trf) {
					if($trf->kelas == "kelas_1") $result["response"]["Grouper"]["tarifKelas1"] = $trf->tarif_inacbg ? $trf->tarif_inacbg : 0;
					if($trf->kelas == "kelas_2") $result["response"]["Grouper"]["tarifKelas2"] = $trf->tarif_inacbg ? $trf->tarif_inacbg : 0;
					if($trf->kelas == "kelas_3") $result["response"]["Grouper"]["tarifKelas3"] = $trf->tarif_inacbg ? $trf->tarif_inacbg : 0;
				}
			}
							
			if(isset($grouping->response->special_cmg)) {
				foreach($grouping->response->special_cmg as $cmg) {
					if($cmg->type == "Special Drug") {
						$result["response"]["Grouper"]["Drug"]["Kode"] = $cmg->code;
						$result["response"]["Grouper"]["Drug"]["Deskripsi"] = $cmg->description;
						$result["response"]["Grouper"]["Drug"]["Tarif"] = $cmg->tariff;
					} 
					if($cmg->type == "Special Prosthesis") {
						$result["response"]["Grouper"]["Prosthesis"]["Kode"] = $cmg->code;
						$result["response"]["Grouper"]["Prosthesis"]["Deskripsi"] = $cmg->description;
						$result["response"]["Grouper"]["Prosthesis"]["Tarif"] = $cmg->tariff;
					} 
					if($cmg->type == "Special Procedure") {
						$result["response"]["Grouper"]["Procedure"]["Kode"] = $cmg->code;
						$result["response"]["Grouper"]["Procedure"]["Deskripsi"] = $cmg->description;
						$result["response"]["Grouper"]["Procedure"]["Tarif"] = $cmg->tariff;
					} 
					if($cmg->type == "Special Investigation") {
						$result["response"]["Grouper"]["Investigation"]["Kode"] = $cmg->code;
						$result["response"]["Grouper"]["Investigation"]["Deskripsi"] = $cmg->description;
						$result["response"]["Grouper"]["Investigation"]["Tarif"] = $cmg->tariff;
					}
				}
			}
		}
		
		if(isset($response["kirimKlaim"])) {
			$list = array();
			if($response["kirimKlaim"]->metadata->code == 200) {
				$list = $response["kirimKlaim"]->response->data;				
			}
			if($list) {
				foreach($list as $l) {
					$dcKEMKES = $l->kemkes_dc_status == 'sent' ? 1 : 0;
					$dcBPJS = $l->bpjs_dc_status == 'sent' ? 1 : 0;
					$result["kirimKlaim"]["DC_KEMKES"] = $dcKEMKES;
					$result["kirimKlaim"]["DC_BPJS"] = $dcBPJS;
				}
			}
		}
				
		$result = json_decode(json_encode($result));
		file_put_contents("logs/grouper.txt", json_encode($response));
		file_put_contents("logs/grouper_result.txt", json_encode($result));
		$grouper = $result->response->Grouper;
		if($result->metaData->code == 200) {
			$tot = $grouper->Procedure->Tarif + $grouper->Drug->Tarif + $grouper->Investigation->Tarif + $grouper->Prosthesis->Tarif + $grouper->SubAcute->Tarif + $grouper->Chronic->Tarif;
			$grouper->totalTarif = $grouper->tarifGruper + $tot;
		}
		$this->hasilGrouping->simpan(array(
			'NOPEN' => $data['nopen']
			, 'NOSEP' => $data['no_sep']
			, 'CODECBG' => $grouper->kodeInacbg
			, 'TARIFCBG' => $grouper->tarifGruper
			, 'TARIFSP' => $grouper->Procedure->Tarif
			, 'TARIFSR' => $grouper->Prosthesis->Tarif
			, 'TARIFSI' => $grouper->Investigation->Tarif
			, 'TARIFSD' => $grouper->Drug->Tarif
			, 'TARIFSA' => $grouper->SubAcute->Tarif
			, 'TARIFSC' => $grouper->Chronic->Tarif
			, 'TARIFKLS1' => $grouper->tarifKelas1
			, 'TARIFKLS2' => $grouper->tarifKelas2
			, 'TARIFKLS3' => $grouper->tarifKelas3
			, 'TOTALTARIF' => $grouper->totalTarif
			, 'TARIFRS' => $tarifRs
			, 'UNUSR' => $grouper->Prosthesis->Kode
			, 'UNUSI' => $grouper->Investigation->Kode
			, 'UNUSP' => $grouper->Procedure->Kode
			, 'UNUSD' => $grouper->Drug->Kode
			, 'UNUSA' => $grouper->SubAcute->Kode
			, 'UNUSC' => $grouper->Chronic->Kode
			, 'TANGGAL' => new \Zend\Db\Sql\Expression('NOW()')
			, 'USER' => $data["user"]
			, 'STATUS' => ($data['status'] ? "1" : "0")
			, 'TIPE' => $data["tipe"]
			, 'DC_KEMKES' => $dcKEMKES
			, 'DC_BPJS' => $dcBPJS
			, 'RESPONSE' => json_encode($response)
		));
		
		return $result;
	}
}