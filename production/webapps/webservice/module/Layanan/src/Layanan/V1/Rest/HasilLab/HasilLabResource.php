<?php
namespace Layanan\V1\Rest\HasilLab;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class HasilLabResource extends Resource
{
	public function __construct() {
		parent::__construct();
		$this->service = new HasilLabService();
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		if($this->isAllowPrivilage('110402')) {
			$data->OLEH = $this->user;
			return $this->service->simpan($data);
		} else return new ApiProblem(405, 'Anda tidak memiliki hak untuk input hasil lab');
        //return new ApiProblem(405, 'The POST method has not been defined');
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
        if($this->isAllowPrivilage('110502')) {
			$params = array("ID" => $id);
			$data = $this->service->load($params);
			
			$result =  array(
				"status" => count($data) > 0 ? 200 : 404,
				"success" => count($data) > 0 ? true : false,
				"total" => count($data),
				"data" => count($data) ? $data[0] : null,
				"detail" => count($data) > 0 ? "Hasil lab ditemukan" : "Hasil lab tidak ditemukan"
			);
			
			return $result;
		} else return new ApiProblem(405, 'Anda tidak memiliki hak untuk melihat hasil lab');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
		if($this->isAllowPrivilage('110502')) {
			$total = $this->service->getRowCount($params);
			$data = $this->service->load($params, array('*'));	
			
			return array(
				"success" => $total > 0 ? true : false,
				"total" => $total,
				"data" => $data
			);
		} else return new ApiProblem(405, 'Anda tidak memiliki hak untuk melihat hasil lab');
		
        //return new ApiProblem(405, 'The GET method has not been defined for collections');
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
		if($this->isAllowPrivilage('110402')) {
			$data->ID = $id;
			return $this->service->simpan($data);
		} else return new ApiProblem(405, 'Anda tidak memiliki hak untuk input hasil lab');
        //return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
