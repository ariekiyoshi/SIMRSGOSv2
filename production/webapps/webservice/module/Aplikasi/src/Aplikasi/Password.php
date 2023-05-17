<?php
namespace Aplikasi;

use DBService\DatabaseService;
use DBService\System;

class Password
{
	CONST TYPE_ENCRYPT_MD5_WITH_KEY = 1;
	CONST TYPE_ENCRYPT_MD5_ONLY = 2;
	CONST TYPE_ENCRYPT_MYSQL_PASS = 3;
	private static $private_key = "KDFLDMSTHBWWSGCBH";
    public static function encrypt($key, $type = Password::TYPE_ENCRYPT_MD5_WITH_KEY) {
		if($type == Password::TYPE_ENCRYPT_MD5_ONLY) {
			return md5($key);
		}
		if($type == Password::TYPE_ENCRYPT_MYSQL_PASS) {			
			$db = DatabaseService::get("SIMpel");			
			$adapter = $db->getAdapter();			
			return System::getPassword($adapter, $key);
		}
		return md5(self::$private_key.md5($key).self::$private_key);
	}
}
