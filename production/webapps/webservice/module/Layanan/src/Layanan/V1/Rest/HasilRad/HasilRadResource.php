<?php
namespace Layanan\V1\Rest\HasilRad;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class HasilRadResource extends Resource
{
	public function __construct() {
		parent::__construct();
		$this->service = new HasilRadService();
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		if($this->isAllowPrivilage('110403')) {
			$data->OLEH = $this->user;
			return $this->service->simpan($data);
		} else return new ApiProblem(405, 'Anda tidak memiliki hak untuk menginput hasil radiologi');
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
		if($this->isAllowPrivilage('110503')) {
			$data = $this->service->load(array('TINDAKAN_MEDIS' => $id));
			return array(
				"success" => count($data) > 0 ? true : false,
				"data" => count($data) > 0 ? $data[0] : null
			);
		} else return new ApiProblem(405, 'Anda tidak memiliki hak untuk melihat hasil radiologi');
        //return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
		if($this->isAllowPrivilage('110503')) {
			$total = $this->service->getRowCount($params);
			$data = $this->service->load($params, array('*'));	
			
			return array(
				"success" => $total > 0 ? true : false,
				"total" => $total,
				"data" => $data
			);
		} else return new ApiProblem(405, 'Anda tidak memiliki hak untuk melihat hasil radiologi');
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
		$data->OLEH = $this->user;
        //if($this->isAllowPrivilage('110403')) {
			return $this->service->simpan($data);
		//} else return new ApiProblem(405, 'Anda tidak memiliki hak untuk merubah hasil radiologi');
    }
}
