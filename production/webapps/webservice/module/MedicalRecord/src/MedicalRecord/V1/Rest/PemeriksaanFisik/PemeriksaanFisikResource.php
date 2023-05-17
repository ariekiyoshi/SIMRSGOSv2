<?php
namespace MedicalRecord\V1\Rest\PemeriksaanFisik;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class PemeriksaanFisikResource extends Resource
{
	public function __construct() {
		parent::__construct();
		$this->service = new Service();
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
			"detail" => "Gagal menyimpan data pemeriksaan fisik"
		);
		
		$result["data"] = null;
		$data->OLEH = $this->user;
		
		$success = $this->service->simpan($data, true);			
		if($success) {
			$result["status"] = 200;
			$result["success"] = true;
			$result["data"] = $success[0];
			$result["detail"] = "Berhasil menyimpan data pemeriksaan fisik";
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
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
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
        $params = array("ID" => $id);
        $data = $this->service->load($params);
		
		$result =  array(
			"status" => count($data) > 0 ? 200 : 404,
			"success" => count($data) > 0 ? true : false,
			"total" => count($data),
			"data" => count($data) ? $data[0] : null,
			"detail" => count($data) > 0 ? "Pemeriksaan Fisik ditemukan" : "Pemeriksaan Fisik tidak ditemukan"
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
        $order = array("ID DESC");
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
		
		$params = is_array($params) ? $params : (array) $params;
		if (isset($params["NOT"])) {
			$nots = (array) json_decode($params["NOT"]);
			foreach($nots as $key => $val) {
				$params[] = new \Zend\Db\Sql\Predicate\Expression("(NOT pemeriksaan_fisik.".$key." = ".$val.")");
			}
			unset($params["NOT"]);
		}
		
		$total = $this->service->getRowCount($params);
		if($total > 0) $data = $this->service->load($params, array('*'), $order);
		
		return array(
			"status" => $total > 0 ? 200 : 404,
			"success" => $total > 0 ? true : false,
			"total" => $total,
			"data" => $data,
			"detail" => $total > 0 ? "Pemeriksaan Fisik ditemukan" : "Pemeriksaan Fisik tidak ditemukan"
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
			"detail" => "Gagal merubah data pemeriksaan fisik"
		);
		
		$data->ID = $id;
		$data->OLEH = $this->user;
		
		$params = array("ID" => $id);
		
		$records = $this->service->load($params);
		$canUpdate = count($records) > 0;
		
		if($canUpdate) {
			$success = $this->service->simpan($data);
			if($success) {
				$result["status"] = 200;
				$result["success"] = true;
				$result["data"] = $success[0];
				$result["detail"] = "Berhasil merubah data pemeriksaan fisik";
			}
		}
		
		if(!$result["success"]) return new ApiProblem($result["status"], $result["detail"], null, null, array("success" => false)); 
		
		return $result;
    }
}
