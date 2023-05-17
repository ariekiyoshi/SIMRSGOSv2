<?php
namespace BPJService\V1\Rpc\Monitoring;

use DBService\RPCResource;

use Zend\View\Model\JsonModel;
use Zend\Json\Json;

class MonitoringController extends RPCResource
{
	private $service;
	
	public function __construct($service) 
	{
		$this->service = $service;
		$this->config = $this->service->getConfig();
	}
		
	public function kunjunganAction()
    {        		
		$params = (array) $this->getRequest()->getQuery();
		$data = $this->service->monitoringKunjungan($params);
		
		return new JsonModel(array(
            'data' => $data
        ));
    }
	
	public function klaimAction()
    {
        $params = (array) $this->getRequest()->getQuery();
        $loadFromBPJS = true;
        $data = [];
        if(isset($params["jenisPengambilanData"])) $loadFromBPJS = $params["jenisPengambilanData"] == 1;
        if($loadFromBPJS) $data = $this->service->monitoringKlaim($params);
        $service = $this->service->getKlaim();
        
        $prms = [];
        if(isset($params["tanggal"])) $prms["tglPulang"] = $params["tanggal"];
        if(isset($params["jenis"])) $prms["jenisPelayanan"] = $params["jenis"];
        if(isset($params["status"])) $prms["status_id"] = $params["status"];
        if(isset($params["start"])) $prms["start"] = $params["start"];
        if(isset($params["limit"])) $prms["limit"] = $params["limit"];
        
        $total = $service->getRowCount($prms);
        $klaim = $service->load($prms);
        
        if($loadFromBPJS) {
            if($data->metadata->code == 200) {
                $data->response->list = $klaim;
                $data->response->count = $total;
            }
        } else {
            $data = [
                "metadata" => [
                    "code" => 200,
                    "message" => "Sukses"
                ],
                "response" => [
                    "list" => $klaim,
                    "count" => $total
                ]
            ];
        }
        
        return new JsonModel(array(
            'data' => $data
        ));
    }
    
    public function historiPelayananAction()
    {
        $params = (array) $this->getRequest()->getQuery();
        $data = $this->service->monitoringHistoriPelayanan($params);
        
        return new JsonModel(array(
            'data' => $data
        ));
    }
    
    public function klaimJaminanJasaRaharjaAction()
    {
        $params = (array) $this->getRequest()->getQuery();
        $data = $this->service->monitoringKlaimJaminanJasaRaharja($params);
        
        return new JsonModel(array(
            'data' => $data
        ));
    }
}
