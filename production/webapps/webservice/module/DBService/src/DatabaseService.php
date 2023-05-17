<?php
namespace DBService;

class DatabaseService
{      
	const SERIAL_PATH = '/serial/';
	private static $serviceManager;

    public static function initialize($sm) {		
        self::$serviceManager = $sm;
		$dir = realpath('.').self::SERIAL_PATH;
		if(!file_exists($dir) && !is_dir($dir)) {
			mkdir($dir);
		}
    }
    
    public static function get($name = "") {		
		return self::getInstanceDatabase($name);
    }
	
	private static function getInstanceDatabase($name) {		
		$name[0] = strtoupper($name[0]);
		$adapterName = strpos($name, "Adapter") > -1 ? $name : $name."Adapter";
		//$fileName = realpath('.').self::SERIAL_PATH.$name;
		//if(file_exists($fileName)) {
		//	$file = file_get_contents($fileName);
		//	return unserialize($file);
		//} else {
		$db = new Database(self::$serviceManager->get($adapterName));
		//	$serial = serialize($db);
		//	file_put_contents($fileName, $serial);
		return $db;
		//}
	}
}
