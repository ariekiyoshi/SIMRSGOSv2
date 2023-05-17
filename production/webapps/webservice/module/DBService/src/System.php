<?php
namespace DBService;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Stdlib\Hydrator;

abstract class System
{
    public static function getSysDate(AdapterInterface $adapter, $datetime = true, $format = false) {
		$sql = "SELECT NOW() TANGGAL";
        if(!$datetime && !$format) $sql = "SELECT DATE(NOW()) TANGGAL";
		if(!$datetime && $format) $sql = "SELECT DATE_FORMAT(NOW(), '%Y-%m-%d') TANGGAL";
		if($datetime && $format) $sql = "SELECT DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s') TANGGAL";
        $result = $adapter->query($sql, array());
        $data = $result->toArray();
        if(!$data) {
            return false;
        }
        
        return $data[0]['TANGGAL'];
    }
	
	public static function getPassword(AdapterInterface $adapter, $value) {
		$sql = "SELECT PASSWORD(?) KATA_SANDI";        
        $result = $adapter->query($sql, array($value));
        $data = $result->toArray();
        if(!$data) {
            return false;
        }
        return $data[0]['KATA_SANDI'];
    }
    
    public static function getKey($data=array()) {
        $result = array();
        foreach ($data as $key => $val) {
            $result[count($result)] = $key;
        }
        
        return $result;
    }
    
    public static function getValues($data=array()) {
        $result = array();
        foreach ($data as $key => $val) {
            $result[count($result)] = $val;
        }
        return $result;
    }
	
	public static function objectToArray($object) {
		$hydrator = new Hydrator\ArraySerializable();
		return $hydrator->extract($object);
	}
	
	public static function isNull($params, $nama) {		
		return !isset($params[$nama]) || is_null($params[$nama]);
		//return !isset($params[$nama]) || empty($params[$nama]) || is_null($params[$nama]);
	}
}
