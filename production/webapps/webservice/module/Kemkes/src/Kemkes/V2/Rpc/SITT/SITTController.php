<?php
namespace Kemkes\V2\Rpc\SITT;

use ZF\ApiProblem\ApiProblem;
use DBService\RPCResource;
use \DateTime;
use \DateTimeZone;
use Zend\Json\Json;

class SITTController extends RPCResource
{
    public function __construct($controller) {        
        $this->authType = self::AUTH_TYPE_IP_OR_LOGIN;
        $this->service = new Service();
        
        $this->config = $controller->get('Config');
        $this->config = $this->config['services']['KemkesService'];
        $this->headers = array(
            "X-rs-id: ".$this->config["id"],
			"X-pass: ".md5($this->config["key"])
        );
    }
    
    private function generateTimestamp() {
        $dt = new DateTime(null, new DateTimeZone("UTC"));
        $timestamp = $dt->getTimestamp();
        $this->headers[] = "X-Timestamp: ".$timestamp;
    }
    
    /**
     * Create a new resource
     *
     * @param  mixed $data
     * @return mixed
     */
	public function create($data) {
	    $result = array(
	        "status" => 422,
	        "success" => false,
	        "detail" => "Gagal menyimpan sitt"
	    );
	    
	    $result["data"] = null;
	    $data->oleh = $this->user;
	    
	    $success = $this->service->simpan($data, true);
	    if($success) {
	        $result["status"] = 200;
	        $result["success"] = true;
	        $result["data"] = $success[0];
	        $result["detail"] = "Berhasil menyimpan sitt";
	    }
	    
	    $this->response->setStatusCode($result["status"]);
	    return $result;
	}
	
	/**
	 * Update an existing resource
	 *
	 * @param  mixed $id
	 * @param  mixed $data
	 * @return mixed
	 */
	public function update($id, $data)
	{
	    $result = array(
	        "status" => 422,
	        "success" => false,
	        "data" => null,
	        "detail" => "Gagal merubah data sitt"
	    );
	    
	    $data->id = $id;
	    $data->oleh = $this->user;
	    
	    $params = array("id" => $id);
	    
	    $records = $this->service->load($params);
	    $canUpdate = count($records) > 0;
	    
	    if($canUpdate) {
	        $success = $this->service->simpan($data);
	        if($success) {
	            $result["status"] = 200;
	            $result["success"] = true;
	            $result["data"] = $success[0];
	            $result["detail"] = "Berhasil merubah data sitt";
	        }
	    }
	    
	    $this->response->setStatusCode($result["status"]);	    
	    return $result;
	}
		
	public function getList() {
	    $queries = (array) $this->request->getQuery();
	    $params = [];
	    if(isset($queries["limit"])) $params["limit"] = $queries["limit"];
	    if(isset($queries["start"])) $params["start"] = $queries["start"];
	    if(isset($queries["nourut_pasien"])) $params["nourut_pasien"] = $queries["norm"];
	    
	    $total = $this->service->getRowCount($params);
	    $data = [];
	    if($total > 0) $data = $this->service->load($params, array('*'), array("tanggal_buat_laporan DESC"));
	    
	    return [
	        "status" => $total > 0 ? 200 : 404,
	        "success" => $total > 0 ? true : false,
	        "total" => $total,
	        "data" => $data,
	        "detail" => $total > 0 ? "SITT ditemukan" : "SITT tidak ditemukan"
	    ];
	}
	
	public function kirimAction() {
	    $this->generateTimestamp();
	    /* ambil data sitt */
	    $params = [
	        "start" => 0,
	        "limit" => 10
	    ];
	    $params[] = "kirim = 1";
	    
	    $froms = $service->load($params, array('*'), array('tanggal_updated ASC'));
	    foreach($froms as &$row) {
	        $params = $row;
	        
	        unset($params["id"]);
	        unset($params["tanggal_updated"]);
	        unset($params["oleh"]);
	        unset($params["kirim"]);
	        
	        $uri = "sitb/sitb/senddata";
	       
	        /* kirim data sitt */
	        $response = $this->sendRequestData([
	            "action" => $uri,
	            "method" => "POST",
	            "data" => $params
	        ]);
	        
	        $result = $this->getResultRequest($response);
	        if(isset($result["status"])) {
	            if($result["status"] == "sukses") {
	                $row["kirim"] = 0;
	                if(isset($result["id_tb_03"])) {
	                    $row["id_tb_03"] = $result["id_tb_03"];
	                }
	                $service->simpan($row);
	            }
	        }
	    }
	    
	    return [
	        "data" => $froms
	    ];
	}
}
