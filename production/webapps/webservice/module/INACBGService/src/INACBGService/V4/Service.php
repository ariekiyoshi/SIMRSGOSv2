<?php
/**
 * INACBGService
 * @author hariansyah
 * 
 */
 
namespace INACBGService\V4;
	
use Zend\Json\Json;
use Zend\Db\Adapter\Adapter;
use Zend\Stdlib\Parameters;
use DBService\DatabaseService;

class Service {
	private $config;
	private $adapter;
	private $params;	
	
	function __construct($config, $adapter) {
		$this->config = $config;
		$this->adapter = $adapter;
		$this->params = new Parameters();
	}	
	
	private function sendRequest($action = "", $method = "GET", $data = "", $contenType = "application/json", $url = "") {
		$curl = curl_init();
		
		$url = ($url == '' ? $this->config["url"] : $url);
		
		$headers = array();
		curl_setopt($curl, CURLOPT_URL, $url."/".$action);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$headers[count($headers)] = "Content-type: ".$contenType;
		$headers[count($headers)] = "Content-length: ".strlen($data);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		
		$result = curl_exec($curl);
		
		curl_close($curl);
		//file_put_contents('inacbg.txt', $url."/".$action);
		return $result;
	}
			
	public function getParameterValue($value, $tipe) {
		if(!isset($value)) {
			if($tipe == "int") $val = 0;
			if($tipe == "string") $val = "NULL";
			if($tipe == "date") $val = "NULL";
			if($tipe == "boolean") $val = 0;
		} else {
			if($tipe == "int") $val = $value;
			if($tipe == "string") $val = "'".str_replace("'", "''", $value)."'";
			if($tipe == "date") {
				$val = "'".$value."'";
				if($value == "NOW()" || $value == "SYSDATE()") $val = $value;
			}
			if($tipe == "boolean") $val = ($value == "true" ? 1 : 0);
		}
		
		return $val;
	}
	
	/*$user_nm,		$user_pw,			$norm,				$nm_pasien, 
	$jns_kelamin, 	$tgl_lahir,			$jns_pbyrn,			$no_peserta,
	$no_sep,		$jns_perawatan,		$kls_perawatan,		$tgl_masuk,
	$tgl_keluar,	$cara_keluar,		$dpjp,				$berat_lahir,
	$tarif_rs,		$srt_rujukan,		$bhp,				$severity3,
	$diag1,			$diag2,				$diag3,				$diag4,
	$diag5,			$diag6,				$diag7,				$diag8,
	$diag9,			$diag10,			$diag11,			$diag12,
	$diag13,		$diag14,			$diag15,			$diag16,
	$diag17,		$diag18,			$diag19,			$diag20,
	$diag21,		$diag22,			$diag23,			$diag24,
	$diag25,		$diag26,			$diag27,			$diag28,
	$diag29,		$diag30,		    $proc1,			    $proc2,	
	$proc3,			$proc4,				$proc5,		    	$proc6,				
	$proc7,			$proc8,				$proc9,		    	$proc10,
	$proc11,		$proc12,			$proc13,		    $proc14,	
	$proc15,		$proc16,			$proc17,		    $proc18,
	$proc19,		$proc20,			$proc21,		    $proc22,
	$proc23,		$proc24,			$proc25,		    $proc26,		
	$proc27,		$proc28,			$proc29,		    $proc30,	
	$adl,			$spec_proc,			$spec_dr,			$spec_inv,
	$spec_prosth,	$nopen,             $user				$status
	$type*/

	public function grouper(array $data = null) {
		if($data) {
			$status = $data['status'];
			unset($data['status']);
			
			$this->params->fromArray($data);
			$prms = $this->params->toString();
			$url = 'simrsGrouping';
			if($status == 1) $url.='Final';
			try {
				$results = $this->sendRequest($url.".php?user_nm=".$this->config["user"]."&user_pw=".$this->config["pwd"]."&".$prms);
				$results = Json::decode(str_replace(")", "", str_replace("(", "", $results)));		
				$this->saveResultGrouper($data, $results, $status);
			} catch(\Exception $e) {}
			
			return $results;
		}
		return null;
	}
	
	private function saveResultGrouper($data, $result, $isFinal) {
		$totalTarif = 0;
		if($result->status != 0) {
			$result->cbg_code =  $result->status_msg;
			$result->tariff = $result->tariff_sp = $result->tariff_sr = $result->tariff_si = $result->tariff_sd = $result->tariff_sa = 0;
			$result->kelas_1 = $result->kelas_2 = $result->kelas_3 = 0;
			$result->sp = $result->sr = $result->si = $result->sd = $result->sa = '';
		} else {				
			$p = $result->tariff_sp + $result->tariff_sr + $result->tariff_si + $result->tariff_sd + $result->tariff_sa;
			$totalTarif = $result->tariff + $p;
		}
		
		$statement = $this->adapter->query(
			"SELECT *
				FROM inacbg.hasil_grouping
			   WHERE NOPEN = '".$data['nopen']."'"
		);                  
		
		$rGrouping = $statement->execute();
		if(count($rGrouping) > 0) {
			$this->adapter->query(
				"UPDATE inacbg.hasil_grouping 
					SET NOSEP = ".$this->getParameterValue($data['no_sep'], 'string')
						.", CODECBG = ".$this->getParameterValue($result->cbg_code, 'string')
						.", TARIFCBG = ".$this->getParameterValue($result->tariff, 'int')
						.", TARIFSP = ".$this->getParameterValue($result->tariff_sp, 'int')
						.", TARIFSR = ".$this->getParameterValue($result->tariff_sr, 'int')
						.", TARIFSI = ".$this->getParameterValue($result->tariff_si, 'int')
						.", TARIFSD = ".$this->getParameterValue($result->tariff_sd, 'int')
						.", TARIFSA = ".$this->getParameterValue($result->tariff_sa, 'int')
						.", TARIFKLS1 = ".$this->getParameterValue($result->kelas_1, 'int')
						.", TARIFKLS2 = ".$this->getParameterValue($result->kelas_2, 'int')
						.", TARIFKLS3 = ".$this->getParameterValue($result->kelas_3, 'int')
						.", TOTALTARIF = ".$this->getParameterValue($totalTarif, 'int')
						.", TARIFRS = ".$this->getParameterValue($data['tarif_rs'], 'int')
						.", UNUSP = ".$this->getParameterValue($result->sp, 'string')
						.", UNUSR = ".$this->getParameterValue($result->sr, 'string')
						.", UNUSI = ".$this->getParameterValue($result->si, 'string')
						.", UNUSD = ".$this->getParameterValue($result->sd, 'string')
						.", UNUSA = ".$this->getParameterValue($result->sa, 'string')						
						.", TANGGAL = NOW(), USER = ".$this->getParameterValue($data['user'], 'string')
						.", STATUS = ".$isFinal
						.", TIPE = ".$this->getParameterValue($data['tipe'], 'int')
				." WHERE NOPEN = ".$this->getParameterValue($data['nopen'], 'string'),
				Adapter::QUERY_MODE_EXECUTE
			);
		} else {			
			$this->adapter->query(
				"INSERT INTO inacbg.hasil_grouping (`NOSEP`, `NOPEN`, `CODECBG`, `TARIFCBG`, `TARIFSP`, `TARIFSR`, `TARIFSI`, `TARIFSD`, `TARIFSA`, `TARIFKLS1`, `TARIFKLS2`, `TARIFKLS3`, `TOTALTARIF`,`TARIFRS`, `UNUSP`, `UNUSR`, `UNUSI`, `UNUSD`, `UNUSA`, `TANGGAL`, `USER`, `STATUS`, `TIPE`)
					   VALUES (".$this->getParameterValue($data['no_sep'], 'string').",".$this->getParameterValue($data['nopen'], 'string').",".$this->getParameterValue($result->cbg_code, 'string').","
							.$this->getParameterValue($result->tariff, 'int').",".$this->getParameterValue($result->tariff_sp, 'int').","
							.$this->getParameterValue($result->tariff_sr, 'int').",".$this->getParameterValue($result->tariff_si, 'int').","
							.$this->getParameterValue($result->tariff_sd, 'int').",".$this->getParameterValue($result->tariff_sa, 'int').","
							.$this->getParameterValue($result->kelas_1, 'int').",".$this->getParameterValue($result->kelas_2, 'int').",".$this->getParameterValue($result->kelas_3, 'int').","
							.$this->getParameterValue($totalTarif, 'int').",".$this->getParameterValue($data['tarif_rs'], 'int').","
							.$this->getParameterValue($result->sp, 'string').",".$this->getParameterValue($result->sr, 'string').","
							.$this->getParameterValue($result->si, 'string').",".$this->getParameterValue($result->sd, 'string').","
							.$this->getParameterValue($result->sa, 'string')
							.",NOW(),".$this->getParameterValue($data['user'], 'string').","
							.$isFinal.","
							.$this->getParameterValue($data['tipe'], 'int').")",
				Adapter::QUERY_MODE_EXECUTE
			);			
		}
	}
}