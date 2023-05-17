<?php
namespace Pendaftaran\V1\Rest\Mutasi;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class MutasiResource extends Resource
{
	public function __construct() {
		parent::__construct();
		$this->service = new MutasiService();
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
		if($this->isAllowPrivilage('110302')) {
			$data->OLEH = $this->user;
			return $this->service->simpan($data);
		}else return new ApiProblem(405, 'Anda tidak memiliki akses layanan mutasi');	
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
        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
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
		$order = array("mutasi.TANGGAL ASC");
		if (isset($params->sort)) {
			$orders = json_decode($params->sort);
			if(is_array($orders)) {
			} else {
				$orders->direction = strtoupper($orders->direction);
				$orders->property = $orders->property == "TANGGAL" ? "mutasi.TANGGAL" : $orders->property;
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
			// untuk update
		} else {
			if($this->isAllowPrivilage('11080102')) {
				$data->OLEH = $this->user;
				return $this->service->simpan($data);
			}else return new ApiProblem(405, 'Anda tidak memiliki akses pembatalan mutasi');
		}
    }
}
