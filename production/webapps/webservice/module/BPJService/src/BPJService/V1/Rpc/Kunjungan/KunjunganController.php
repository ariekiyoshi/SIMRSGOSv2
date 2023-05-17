<?php
namespace BPJService\V1\Rpc\Kunjungan;

use DBService\RPCResource;

use Zend\View\Model\JsonModel;
use Zend\Json\Json;

class KunjunganController extends RPCResource
{
	private $service;
	
	public function __construct($service) 
	{
		$this->service = $service;
		$this->config = $this->service->getConfig();
	}

	public function getList()
    {
		$this->response->setStatusCode(405); 
		return new JsonModel(array(
			'data' => array(
				'metadata' => array(
					'message' => 'Method Not Allowed',
					'code' => 405,
					'requestId' => $this->config['koders']
				)
			)
		));
    }
	
	public function create($data)
    {
		$data["create"] = true;
		$data["ip"] = $_SERVER["REMOTE_ADDR"];
		$data = $this->service->generateNoSEP($data);
		
		return new JsonModel(array(
            'data' => $data
        ));
    }
	
	public function updateSEPAction()
    {
		$data = $this->getPostData($this->getRequest());
		$data = $this->service->updateSEP($data);
		
		return new JsonModel(array(
            'data' => $data
        ));
    }
	
	public function mappingDataTransaksiAction()
    {
		$data = $this->getPostData($this->getRequest());
		$data = $this->service->mappingDataTransaksi($data);
		
		return new JsonModel(array(
            'data' => $data
        ));
    }
	
	public function updateTanggalPulangAction()
    {        
		$data = $this->getPostData($this->getRequest());
		$data = $this->service->updateTanggalPulang($data);
		
		return new JsonModel(array(
            'data' => $data
        ));
    }
	
	public function cekSEPAction()
    {        
		$data = $this->getPostData($this->getRequest());
		$data = $this->service->cekSEP($data["noSEP"]);
		
		return new JsonModel(array(
            'data' => $data
        ));
    }
	
	public function batalkanSEPAction()
    {       
		$data = $this->getPostData($this->getRequest());		
		$data = $this->service->batalkanSEP(count($data) > 1 ? $data : $data["noSEP"]);
		
		return new JsonModel(array(
            'data' => $data
        ));
    }
	
	public function pengajuanSEPAction()
    {
		$data = $this->getPostData($this->getRequest());
		$data = $this->service->pengajuanSEP($data);
		
		return new JsonModel(array(
            'data' => $data
        ));
    }
	
	public function aprovalPengajuanSEPAction()
    {
		$data = $this->getPostData($this->getRequest());
		$data = $this->service->aprovalPengajuanSEP($data);
		
		return new JsonModel(array(
            'data' => $data
        ));
    }
	
	public function daftarPengajuanAction()
    {        		
		$params = (array) $this->getRequest()->getQuery();
		$pengajuan = $this->service->getPengajuan();
		
		$data = null;
		$count = $pengajuan->getRowCount($params);
		if($count > 0) $data = $pengajuan->load($params, array('*'), array('tgl'));
		
		return new JsonModel(array(
            'data' => array(
				"metadata" => array(
					"code" => 200,
					"message" => "Sukses"
				),
				"response" => array(
					"list" => $data,
					"count" => $count
				)
			)
        ));
    }
	
	public function batalPengajuanSEPAction()
    {
		$data = $this->getPostData($this->getRequest());
		$pengajuan = $this->service->getPengajuan();
		$data = is_array($data) ? $data : (array) $data;
		if(isset($data["user"])) {
			$data["userAprove"] = $data["user"];
			$data["tglAprove"] = new \Zend\Db\Sql\Expression('NOW()');
			unset($data["user"]);
		}
		$data["status"] = 0;
		$pengajuan->simpan($data);
		
		return new JsonModel(array(
            'data' => array(
				"metadata" => array(
					"code" => 200,
					"message" => "Sukses"
				)
			)
        ));
    }
	
	public function kunjunganPesertaAction()
    {       
		$data = $this->getPostData($this->getRequest());
		$data = $this->service->kunjunganPeserta($data["noSEP"]);
		
		return new JsonModel(array(
            'data' => $data
        ));
    }
	
	public function monitoringVerifikasiKlaimAction()
    {       
		$data = $this->getPostData($this->getRequest());
		$data = $this->service->monitoringVerifikasiKlaim($data);
		
		return new JsonModel(array(
            'data' => $data
        ));
    }
	
	public function riwayatPelayananPesertaAction()
    {       
		$data = $this->getPostData($this->getRequest());
		$data = $this->service->riwayatPelayananPeserta($data["noKartu"]);
		
		return new JsonModel(array(
            'data' => $data
        ));
    }
	
	public function inacbgAction()
    {       
		$data = $this->getPostData($this->getRequest());
		$data = $this->service->inacbg($data["noSEP"]);
		
		return new JsonModel(array(
            'data' => $data
        ));
    }
}
