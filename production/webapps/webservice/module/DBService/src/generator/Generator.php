<?php
namespace DBService\generator;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\TableGateway\TableGateway;

class Generator
{
    private static $adapter;

    public static function initialize(AdapterInterface $adapter) {
        self::$adapter = $adapter;
    }

    public static function getAdapter() {
        return self::$adapter;
    }
    
    public static function get($name = '') {
        return new TableGateway($name, self::$adapter);
    }
	
	public static function generateIdKeluargaPasien($shdk, $norm, $jeniskelamin) {
		$stmt = self::$adapter->query('SELECT generator.generateIdKeluargaPasien(?,?,?) ID');
		$rst = $stmt->execute(array($shdk, $norm, $jeniskelamin));
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoPendaftaran() {
		$stmt = self::$adapter->query('SELECT generator.generateNoPendaftaran(DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateIdTindakanMedis() {
		$stmt = self::$adapter->query('SELECT generator.generateIdTindakanMedis(DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoKunjungan($ruangan) {
		$stmt = self::$adapter->query('SELECT generator.generateNoKunjungan('.$ruangan.', DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoKonsul($ruangan) {
		$stmt = self::$adapter->query('SELECT generator.generateNoKonsul('.$ruangan.', DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoMutasi($ruangan) {
		$stmt = self::$adapter->query('SELECT generator.generateNoMutasi('.$ruangan.', DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoOrderLab($ruangan) {
		$stmt = self::$adapter->query('SELECT generator.generateNoOrderLab('.$ruangan.', DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoOrderRad($ruangan) {
		$stmt = self::$adapter->query('SELECT generator.generateNoOrderRad('.$ruangan.', DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateIdParameterTindakanLab($tindakan) {
		$stmt = self::$adapter->query('SELECT generator.generateIdParameterTindakanLab('.$tindakan.') ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoOrderResep($ruangan) {
		$stmt = self::$adapter->query('SELECT generator.generateNoOrderResep('.$ruangan.', DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateIdReturFarmasi() {
		$stmt = self::$adapter->query('SELECT generator.generateIdReturFarmasi(DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateIdHasilLab() {
		$stmt = self::$adapter->query('SELECT generator.generateIdHasilLab(DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoReservasi() {
		$stmt = self::$adapter->query('SELECT generator.generateNoReservasi() ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateIdFarmasi() {
		$stmt = self::$adapter->query('SELECT generator.generateIdFarmasi(DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateIdO2() {
		$stmt = self::$adapter->query('SELECT generator.generateIdO2(DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateIdKarcis() {
		$stmt = self::$adapter->query('SELECT generator.generateIdKarcis(DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateIdKartu() {
		$stmt = self::$adapter->query('SELECT generator.generateIdKartu(DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoTagihan() {
		$stmt = self::$adapter->query('SELECT generator.generateNoTagihan(DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoPenerimaan($ruangan) {
		$stmt = self::$adapter->query('SELECT generator.generateNoPenerimaan('.$ruangan.', DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoPermintaan($ruangan) {
		$stmt = self::$adapter->query('SELECT generator.generateNoPermintaan('.$ruangan.', DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoPengiriman($ruangan) {
		$stmt = self::$adapter->query('SELECT generator.generateNoPengiriman('.$ruangan.', DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoMaterialRequest() {
		$stmt = self::$adapter->query('SELECT generator.generateNoMaterialRequest(DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoSuratPenawaranHarga() {
		$stmt = self::$adapter->query('SELECT generator.generateNoSuratPenawaranHarga(DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoKejadian() {
		$stmt = self::$adapter->query('SELECT generator.generateNoKejadian(YEAR(NOW()), MONTH(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateIdPenggunaAksesLog() {
		$stmt = self::$adapter->query('SELECT generator.generateIdPenggunaAksesLog(DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoPurchaseOrder() {
		$stmt = self::$adapter->query('SELECT generator.generateNoPurchaseOrder(DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public static function generateNoRujukan() {
		$stmt = self::$adapter->query('SELECT generator.generateNoRujukan(DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}

	public static function generateIdBridgeLog() {
		$stmt = self::$adapter->query('SELECT generator.generateIdBridgeLog(DATE(NOW())) ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
}
