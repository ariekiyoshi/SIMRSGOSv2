<?php
namespace LISService\novanet\dbservice;

use LISService\lis\DriverInterface;
use LISService\novanet\dbservice\result\Service as ResultService;
use \Exception;

/**
 * @author admin
 * @version 1.0
 * @created 26-Mar-2016 17.43.00
 */
class Driver implements DriverInterface
{	
	private $result;
	
	public function __construct()
	{		
		$this->result = new ResultService();
	}

	public function getResult()
	{
		$data = array();
		// $result = $this->result->load(array("result.status" => array("F","W","J"))); or		
		$result = $this->result->load(array("result.status IN ('F','W','J') AND NOT o.InstrID = 'Novanet'"), 
			array("_OID", "InstrTestID", "InstrTestName", "RValue", "NatureAnormalTest", "HL_Limit", "AHL_Limit", "ANormalFlag", "Unit",
				"TestEndDate", "OperatiorID")
		);
		
		foreach($result as $row) {
			$data[] = array(
				'LIS_NO'=>$row['_OID'],
				'HIS_NO_LAB'=>'',
				'LIS_KODE_TEST'=>$row['InstrTestID'],
				'LIS_NAMA_TEST'=>($row['InstrTestName'] == '' || $row['InstrTestName'] == null ? $row['InstrTestID'] : $row['InstrTestName']),
				'LIS_HASIL'=>$row['RValue'],
				'LIS_CATATAN'=>$row['NatureAnormalTest'],
				'LIS_NILAI_NORMAL'=>trim($row['HL_Limit']." ~ ".$row['AHL_Limit']),
				'LIS_FLAG'=>$row['ANormalFlag'],
				'LIS_SATUAN'=>$row['Unit'], 
				'LIS_NAMA_INSTRUMENT'=>$row['InstrID'], 
				'LIS_TANGGAL'=>$row['TestEndDate'], 
				'LIS_USER'=>$row['OperatiorID'], 
				'HIS_KODE_TEST'=>'',
				'REF'=>$row['Lab_PatientID'], 
				'VENDOR_LIS'=>$this->getVendorId(),
				'LIS_LOKASI'=>($row['InstrID'] == 'Novanet' ? $row['Location'] : $row['DeviceID'])
			);
		}
		
		return $data;
	}
	
	public function updateStatusResult($data)
	{
		$this->resultBridgeLis->simpan(array(
			"_OID" => $data["LIS_NO"],
			"InstrTestID" => $data["LIS_KODE_TEST"],
			"Status" => "R"
		));
	}

	public function order($params=array())
	{
		throw new \Exception(
			'Order Not Support'
		);
	}
	
	public function getVendorId(){
		return 2;
	}
}
?>