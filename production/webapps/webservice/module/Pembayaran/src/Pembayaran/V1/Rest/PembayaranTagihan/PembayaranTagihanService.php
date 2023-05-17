<?php
namespace Pembayaran\V1\Rest\PembayaranTagihan;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Zend\Db\Sql\TableIdentifier;

class PembayaranTagihanService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pembayaran_tagihan", "pembayaran"));
		$this->entity = new PembayaranTagihanEntity();
    }
	
	public function simpan($data, $isCreated = false) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity = new PembayaranTagihanEntity();
		$this->entity->exchangeArray($data);
		
		$params = [
			"TAGIHAN" => $this->entity->get("TAGIHAN"),
			"JENIS" => $this->entity->get("JENIS")			
		];
		
		if($isCreated) {
			$this->entity->set('TANGGAL', new \Zend\Db\Sql\Expression('NOW()'));
			$this->table->insert($this->entity->getArrayCopy());
		} else {
			$params["TANGGAL"] = $this->entity->get("TANGGAL");
			$this->table->update($this->entity->getArrayCopy(), $params);
		}
		
		$params["STATUS"] = 1;
		
		return array(
			'success' => true,
			'data' => $this->load($params)
		);
	}
	
	public function masihAdaKunjunganBlmFinal($id) {
		$adapter = $this->table->getAdapter();
		$conn = $adapter->getDriver()->getConnection();
		$results = $conn->execute('CALL pembayaran.masihAdaKunjunganBlmFinal('.$id.')');
		$stmt2 = $results->getResource();
		$resultset = $stmt2->fetchAll(\PDO::FETCH_OBJ);
		$found = false;
		foreach($resultset as $data) {
			$found .= $data->RUANGAN."<br/>";
		}
		return $found;
	}
	
	public function masihAdaOrderKonsulMutasiYgBlmDiterima($id) {
		$adapter = $this->table->getAdapter();
		$conn = $adapter->getDriver()->getConnection();
		$results = $conn->execute('CALL pembayaran.masihAdaOrderKonsulMutasiYgBlmDiterima('.$id.')');
		$stmt2 = $results->getResource();
		$resultset = $stmt2->fetchAll(\PDO::FETCH_OBJ);
		$found = false;
		foreach($resultset as $data) {
			$found .= $data->DESKRIPSI."<br/>";
		}
		return $found;
	}
	
	public function getTanggalTerakhirPembayaran($tagihan, $jenis) {
		$adapter = $this->table->getAdapter();
		$conn = $adapter->getDriver()->getConnection();
		$results = $conn->execute('SELECT pembayaran.getTanggalTerakhirPembayaran('.$tagihan.','.$jenis.') TANGGAL');
		$stmt2 = $results->getResource();
		$resultset = $stmt2->fetchAll(\PDO::FETCH_OBJ);
		$tanggal = null;
		foreach($resultset as $data) {
			$tanggal = $data->TANGGAL;
		}
		return $tanggal;
	}
}
