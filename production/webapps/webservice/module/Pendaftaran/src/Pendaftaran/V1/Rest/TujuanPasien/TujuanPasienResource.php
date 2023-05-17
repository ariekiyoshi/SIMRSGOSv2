<?php
namespace Pendaftaran\V1\Rest\TujuanPasien;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class TujuanPasienResource extends Resource
{
	public function __construct() {
		parent::__construct();
		$this->service = new TujuanPasienService();
		$this->service->setPrivilage(true);
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        return new ApiProblem(405, 'The POST method has not been defined');
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
	
		$data = $this->service->load(array('NOPEN'=>$id));	
		
		return array(
			"success" => count($data) > 0 ? true : false,
			"data" => count($data) > 0 ? $data[0] : null
		);
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
		$order = array("p.TANGGAL ASC");
		if (isset($params->sort)) {
			$orders = json_decode($params->sort);
			if(is_array($orders)) {
			} else {
				$orders->direction = strtoupper($orders->direction);
				$orders->property = $orders->property == "TANGGAL" ? "p.TANGGAL" : $orders->property;
				$order = array($orders->property." ".($orders->direction == "ASC" || $orders->direction == "DESC" ? $orders->direction : ""));
			}
			unset($params->sort);
		}
		$this->service->setUser($this->user);
        $total = $this->service->getRowCount($params);
		$data = array();
		if($total > 0) $data = $this->service->load($params, array('*'), $order);
		
		return array(
			"success" => $total > 0 ? true : false,
			"total" => $total,
			"data" => $data
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
		$status = isset($data->STATUS) ? $data->STATUS : 1;
		if($status != 0) {
			if($this->isAllowPrivilage('100703')) {
				return $this->service->simpan($data);
			} else return new ApiProblem(405, 'Anda tidak memiliki hak untuk melakukan perubahan tujuan pasien');
		} else {
			if($this->isAllowPrivilage('11080101')) {
				return $this->service->simpan($data);
			} else return new ApiProblem(405, 'Anda tidak memiliki hak untuk melakukan pembatalan tujuan pasien');
		}
    }
}
