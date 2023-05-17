<?php
namespace LISService\winacom\dbservice;

use LISService\lis\DriverInterface;
use LISService\winacom\dbservice\demographics\Service as DemographicsService;
use LISService\winacom\dbservice\orderitem\Service as OrderedItemService;
use LISService\winacom\dbservice\registration\Service as RegistrationService;
use LISService\winacom\dbservice\resultbridgelis\Service as ResultBridgeLisService;

/**
 * @author admin
 * @version 1.0
 * @created 26-Mar-2016 17.43.00
 */
class Driver implements DriverInterface
{

	private $demographics;
	private $orderedItem;
	private $registration;
	private $resultBridgeLis;
	
	public function __construct()
	{
		$this->demographics = new DemographicsService();
		$this->orderedItem = new OrderedItemService();
		$this->registration = new RegistrationService();
		$this->resultBridgeLis = new ResultBridgeLisService();
	}

	public function getResult()
	{
		$data = array();
		$result = $this->resultBridgeLis->load(array("transfer_flag" => 1));
		
		foreach($result as $row) {
			$data[] = array(
				'LIS_NO'=>$row['lis_reg_no'],
				'HIS_NO_LAB'=>$row['his_reg_no'],
				'LIS_KODE_TEST'=>$row['lis_test_id'],
				'LIS_NAMA_TEST'=>$row['test_name'],
				'LIS_HASIL'=>$row['result'],
				'LIS_CATATAN'=>$row['result_comment']."-".$row['reference_note'],
				'LIS_NILAI_NORMAL'=>$row['reference_value'],
				'LIS_FLAG'=>$row['test_flag_sign'],
				'LIS_SATUAN'=>$row['test_unit_name'],
				'LIS_NAMA_INSTRUMENT'=>$row['instrument_name'],
				'LIS_TANGGAL'=>$row['authorization_date'],
				'LIS_USER'=>$row['authorization_user'],
				'HIS_KODE_TEST'=>$row['his_test_id_order'],
				'REF'=>'-',
				'VENDOR_LIS'=>$this->getVendorId(),
				'LIS_LOKASI'=>"-"
			);
		}
		
		return $data;
	}
	
	public function updateStatusResult($data)
	{
		$this->resultBridgeLis->simpan(array(
			"lis_reg_no" => $data["LIS_NO"],
			"lis_test_id" => $data["LIS_KODE_TEST"],
			"transfer_flag" => 2
		));
	}

	public function order($params=array())
	{
		if(isset($params) && count($params) > 0) {
			$data = json_decode(json_encode($params));
			$kunjungan = $data->REFERENSI->KUNJUNGAN;
			$pendaftaran = $kunjungan->REFERENSI->PENDAFTARAN;
			$pasien = $pendaftaran->REFERENSI->PASIEN;
			$perujuk = isset($kunjungan->REFERENSI->PERUJUK) ? $kunjungan->REFERENSI->PERUJUK : null;
			$order = isset($data->REFERENSI->ORDER_LAB) ? $data->REFERENSI->ORDER_LAB : false;
			
			$this->demographics->simpan(array(
				'patient_id'=>$pasien->NORM, 
				'gender_id'=>$pasien->JENIS_KELAMIN == 2 ? 0 : 1, 
				'gender_name'=>$pasien->REFERENSI->JENISKELAMIN->DESKRIPSI, 
				'date_of_birth'=>$pasien->TANGGAL_LAHIR, 
				'patient_name'=>$pasien->NAMA, 
				'patient_address'=>$pasien->ALAMAT, 
				'city_id'=>$pasien->WILAYAH, 
				'city_name'=>isset($pasien->REFERENSI->WILAYAH) ? $pasien->REFERENSI->WILAYAH->DESKRIPSI : null,
				'phone_number'=>null, 
				'fax_number'=>null,
				'mobile_number'=>null, 
				'email'=>null
			));
			
			$classId = $pendaftaran->PENJAMIN->KELAS;
			$className = $pendaftaran->PENJAMIN->REFERENSI->KELAS->DESKRIPSI;
			$roomId = $roomName = $bedId = $bedName = $wardId = $wardName = $regUserId = $regUserName = null;
			if($order) {
				if(isset($order->REFERENSI->KUNJUNGAN->REFERENSI->RUANG_KAMAR_TIDUR)) {
					$ruangKamarTidur = $order->REFERENSI->KUNJUNGAN->REFERENSI->RUANG_KAMAR_TIDUR;
					$ruangKamar = $ruangKamarTidur->REFERENSI->RUANG_KAMAR;
					$classId = $ruangKamar->REFERENSI->KELAS->ID;
					$className = $ruangKamar->REFERENSI->KELAS->DESKRIPSI;
					
					$roomId = $ruangKamar->ID;
					$roomName = $ruangKamar->KAMAR;
					$bedId = $ruangKamarTidur->ID;
					$bedName = $ruangKamarTidur->TEMPAT_TIDUR;
				}
				
				$wardId = $order->REFERENSI->KUNJUNGAN->REFERENSI->RUANGAN->ID;
				$wardName = $order->REFERENSI->KUNJUNGAN->REFERENSI->RUANGAN->DESKRIPSI;				
				$regUserId = $order->REFERENSI->PENGGUNA->ID;
				$regUserName = $order->REFERENSI->PENGGUNA->NAMA;
			} else {
				$wardId = $kunjungan->REFERENSI->RUANGAN->ID;
				$wardName = $kunjungan->REFERENSI->RUANGAN->DESKRIPSI;				
				$regUserId = $data->OLEH;
				$regUserName = $order->REFERENSI->PENGGUNA->NAMA;
			}
			
			$this->registration->simpan(array(
				'order_number'=>$kunjungan->NOMOR, 
				'visit_number'=>$kunjungan->NOPEN, 
				'patient_id'=>$pasien->NORM,
				'order_datetime'=>$kunjungan->MASUK,
				'service_unit_id'=>$kunjungan->REFERENSI->RUANGAN->ID,
				'service_unit_name'=>$kunjungan->REFERENSI->RUANGAN->DESKRIPSI,
				'guarantor_id'=>$pendaftaran->PENJAMIN->JENIS,
				'guarantor_name'=>$pendaftaran->PENJAMIN->REFERENSI->KAP->REFERENSI->PENJAMIN->DESKRIPSI, 
				'agreement_id'=>$pendaftaran->PENJAMIN->KELAS, 
				'agreement_name'=>$pendaftaran->PENJAMIN->REFERENSI->KELAS->DESKRIPSI, 
				'doctor_id'=>$perujuk ? $perujuk->DOKTER_ASAL : null, //tidak ada id dokter rujukan, apalagi bila dr luar 
				'doctor_name'=>$perujuk ? $perujuk->REFERENSI->DOKTER_ASAL->NAMA : null, 
				'class_id'=>$classId, 
				'class_name'=>$className, 
				'ward_id'=>$wardId, 
				'ward_name'=>$wardName,
				'room_id'=>$roomId,
				'room_name'=>$roomName,
				'bed_id'=>$bedId,
				'bed_name'=>$bedName,
				'reg_user_id'=>$regUserId,
				'reg_user_name'=>$regUserName
			));
			
			$this->orderedItem->simpan(array(
				'order_number'=>$kunjungan->NOMOR, 
				'order_item_id'=>$data->TINDAKAN, 
				'order_item_name'=>$data->TINDAKAN_DESKRIPSI, 
				'order_item_datetime'=>$data->TANGGAL
			));
		}
	}
	
	public function getVendorId(){
		return 1;
	}
}
?>