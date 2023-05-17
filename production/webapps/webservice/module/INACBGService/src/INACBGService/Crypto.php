<?php
namespace INACBGService;

use Zend\Math\Rand;

class Crypto {
	public static function encrypt($data, $key) {
		// make binary representasion of $key
		$key = hex2bin($key);
		
		// check key length, mush be 256 bit or 32 bytes
		if(mb_strlen($key, "8bit") !== 32) {
			throw new \Exception("Needs a 256-bit key!");				
		}
		
		// create initialization vector
		$iv_size = openssl_cipher_iv_length("aes-256-cbc");
		//$iv = random_bytes($iv_size);
		//$iv = openssl_random_pseudo_bytes($iv_size);
		$iv = Rand::getBytes($iv_size, true);
		
		// encrypt
		$encrypted = openssl_encrypt($data, "aes-256-cbc", $key, OPENSSL_RAW_DATA, $iv);
		
		// create signature, against padding oracle attacks
		$signature = mb_substr(hash_hmac("sha256", $encrypted, $key, true), 0, 10, "8bit");
		
		$encoded = chunk_split(base64_encode($signature.$iv.$encrypted));
		
		return $encoded;
		
	}
	
	public static function decrypt($str, $key) {
		// make binary representasion of $key
		$key = hex2bin($key);
		
		// check key length, mush be 256 bit or 32 bytes
		if(mb_strlen($key, "8bit") !== 32) {
			throw new \Exception("Needs a 256-bit key!");				
		}
		
		// calculate iv size
		$iv_size = openssl_cipher_iv_length("aes-256-cbc");
		
		// breakdown parts
		$str = str_replace("----BEGIN ENCRYPTED DATA----", "", $str);
		$str = str_replace("----END ENCRYPTED DATA----", "", $str);
		$decoded = base64_decode($str);
		
		$signature = mb_substr($decoded, 0, 10, "8bit");
		$iv = mb_substr($decoded, 10, $iv_size, "8bit");
		$encrypted = mb_substr($decoded, $iv_size+10, null, "8bit");
		
		// check signature, against padding oracle attack
		$calc_signature = mb_substr(hash_hmac("sha256", $encrypted, $key, true), 0, 10, "8bit");
		
		if(!self::compare($signature, $calc_signature)) {
			return "SIGNATURE_NOT_MATCH"; // signature doesn't match
		}
		
		$decrypted = openssl_decrypt($encrypted, "aes-256-cbc", $key, OPENSSL_RAW_DATA, $iv);
		
		return $decrypted;
	}
	
	public static function compare($a, $b) {
		// compare individually to prevent timing attacks
		
		// compare length
		if(strlen($a) !== strlen($b)) return false;
		
		// compare individual
		$result = 0;
		for($i = 0; $i < strlen($a); $i++) {
			$result |= ord($a[$i]) ^ ord($b[$i]);
		}
		
		return $result == 0;
	}
}
?>