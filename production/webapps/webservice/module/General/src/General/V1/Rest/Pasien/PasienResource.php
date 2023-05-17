<?php
namespace General\V1\Rest\Pasien;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class PasienResource extends Resource
{
	public function __construct() {
		parent::__construct();
		$this->service = new PasienService();
	}

    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		if($this->isAllowPrivilage('1001')) {
			$data->OLEH = $this->user;
			return $this->service->simpan($data);
		} else return new ApiProblem(405, 'Anda tidak memiliki hak untuk membuat pasien baru');
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
		$data = $this->service->load(array('NORM'=>$id));	
		
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
        $order = array("TANGGAL DESC");
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
			"detail" => $total > 0 ? "Pasien ditemukan" : "Pasien tidak ditemukan"
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
		if($this->isAllowPrivilage('100701')) {
			$data->NORM = $id;
			$data->OLEH = $this->user;
			return $this->service->simpan($data);
		} else return new ApiProblem(405, 'Anda tidak memiliki hak untuk melakukan perubahan data pasien');
		
    }
}
