<?php
namespace Aplikasi;

use DBService\DatabaseService;
use \DateTime;
use \DateTimeZone;
use \Exception;

class Signature
{    
	private $xId;
	private $xTimestamp;
	private $xSign;
	private $xRef;
	
	public function __construct($xid, $xtimestamp, $xsign) {
		$this->xId = $xid;
		$this->xTimestamp = $xtimestamp;
		$this->xSign = $xsign;			
	}
	
	public function getXId() {
		return $this->xId;
	}
	
	public function getXRef() {
		return $this->xRef;
	}
	
	public function isValidSignature() {
		if(!$this->xId || !$this->xTimestamp || !$this->xSign) {
			throw new Exception("Signature Required", 428);
		}
		
		$this->xId = $this->xId->getFieldValue();
		$this->xTimestamp = $this->xTimestamp->getFieldValue();
		$this->xSign = $this->xSign->getFieldValue();
		
		$ts = $this->getTimestamp();
		//var_dump($ts."-".$this->xTimestamp."=".($ts - $this->xTimestamp));
		if(($ts - $this->xTimestamp) > 3600) throw new Exception("Signature is timeout", 408);		
		$data = $this->getSignature($this->xId);
		if($data) {
			$this->xRef = $data["X_REF"];
			if($data["STATUS"] == 0) throw new Exception("Signature is not found / registered", 401);
			$sign = $this->generateSign($data, $this->xTimestamp);
			//var_dump($sign);
			if($sign === $this->xSign) return true;
			throw new Exception("Invalid Signature", 401);
		} else {
			throw new Exception("Signature is not found / registered", 401);
		}
		
		return true;
	}
	
	private function getSignature($xid) {
		$db = DatabaseService::get("SIMpel");
		$adapter = $db->getAdapter();
		
		$stmt = $adapter->query('
			SELECT *
			  FROM aplikasi.signature
			 WHERE X_ID = ?');
		$results = $stmt->execute(array($xid));
		return $results->current();
	}
	
	public function getTimestamp() {
		$dt = new DateTime(null, new DateTimeZone("UTC"));
		return $dt->getTimestamp();
	}
	
	public function generateSign($data, $xtimestamp) {
		$key = $data["X_ID"]."&".$xtimestamp;					
		return base64_encode(hash_hmac("sha256", utf8_encode($key), utf8_encode($data["X_PASS"]), true));
	}
}
