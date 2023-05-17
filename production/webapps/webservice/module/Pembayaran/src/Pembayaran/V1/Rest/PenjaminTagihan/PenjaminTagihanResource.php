<?php
namespace Pembayaran\V1\Rest\PenjaminTagihan;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class PenjaminTagihanResource extends Resource
{
    public function __construct() {
        parent::__construct();
        $this->service = new PenjaminTagihanService();
    }
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $result = array(
            "status" => 422,
            "success" => false,
            "detail" => "Gagal menyimpan Penjamin Tagihan"
        );
        
        $result["data"] = null;
        
        $params = array("TAGIHAN" => $data->TAGIHAN, "PENJAMIN" => $data->PENJAMIN);
        
        $records = $this->service->load($params);
        $canUpdate = count($records) > 0;
        
        $verify = false;
        if($canUpdate) {
            if($records[0]["KE"] == 1) {
                $result["detail"] = "Total biaya penjamin ini tidak dapat di ubah";
                $verify = true;
            }
        } else {
            $data->KE = count($records) + 1;
        }
        
        if(!$verify) {
            $success = $this->service->simpan($data, !$canUpdate);
            if($success) {
                $result["status"] = 200;
                $result["success"] = true;
                $result["data"] = $success[0];
                $result["detail"] = "Berhasil menyimpan Penjamin Tagihan";
            }
        }
        
        if(!$result["success"]) return new ApiProblem($result["status"], $result["detail"], null, null, array("success" => false));
        
        return $result;
    }
    
    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        $result = array(
            "status" => 422,
            "success" => false,
            "data" => null,
            "detail" => "Gagal hapus data Penjamin Tagihan"
        );
        
        $ids = explode("-", $id);
        
        $params = array("TAGIHAN" => $ids[0], "PENJAMIN" => $ids[1]);
        
        $records = $this->service->load($params);
        $found = count($records) > 0;
        
        if($found) {
            $success = $this->service->hapus($params);
            if($success) {
                $result["status"] = 200;
                $result["success"] = true;
                $result["data"] = $success[0];
                $result["detail"] = "Berhasil hapus data Penjamin Tagihan";
            }
        }
        
        if(!$result["success"]) return new ApiProblem($result["status"], $result["detail"], null, null, array("success" => false));
        
        return $result["status"] == 200;
    }
    
    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }
    
    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        $ids = explode("-", $id);
        $tagihan = $ids[0];
        $penjamin = $ids[1];
        $params = array("TAGIHAN" => $tagihan, "PENJAMIN" => $penjamin);
        $data = $this->service->load($params);
        
        $result =  array(
            "status" => count($data) > 0 ? 200 : 404,
            "success" => count($data) > 0 ? true : false,
            "total" => count($data),
            "data" => count($data) ? $data[0] : null,
            "detail" => count($data) > 0 ? "Penjamin Tagihan ditemukan" : "Penjamin Tagihan tidak ditemukan"
        );
        
        return $result;
    }
    
    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        parent::fetchAll($params);
        $order = array("TAGIHAN", "PENJAMIN", "KE");
        $data = null;
        if (isset($params->sort)) {
            $orders = json_decode($params->sort);
            if(is_array($orders)) {
            } else {
                $orders->direction = strtoupper($orders->direction);
                $order = array($orders->property." ".($orders->direction == "ASC" || $orders->direction == "DESC" ? $orders->direction : ""));
            }
            unset($params->sort);
        }
        
        $total = $this->service->getRowCount($params);
        if($total > 0) $data = $this->service->load($params, array('*'), $order);
        
        return array(
            "status" => $total > 0 ? 200 : 404,
            "success" => $total > 0 ? true : false,
            "total" => $total,
            "data" => $data,
            "detail" => $total > 0 ? "Penjamin Tagihan ditemukan" : "Penjamin Tagihan tidak ditemukan"
        );
    }
    
    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }
    
    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }
    
    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        $result = array(
            "status" => 422,
            "success" => false,
            "data" => null,
            "detail" => "Gagal merubah data Penjamin Tagihan"
        );
        
        $ids = explode("-", $id);
        
        $data->TAGIHAN = $ids[0];
        $data->PENJAMIN = $ids[1];
        
        $params = array("TAGIHAN" => $data->TAGIHAN, "PENJAMIN" => $data->PENJAMIN);
        
        $records = $this->service->load($params);
        $canUpdate = count($records) > 0;
        
        if($canUpdate) {
            $success = $this->service->simpan($data);
            if($success) {
                $result["status"] = 200;
                $result["success"] = true;
                $result["data"] = $success[0];
                $result["detail"] = "Berhasil merubah data Penjamin Tagihan";
            }
        }
        
        if(!$result["success"]) return new ApiProblem($result["status"], $result["detail"], null, null, array("success" => false));
        
        return $result;
    }
}
