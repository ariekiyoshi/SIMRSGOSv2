<?php
namespace General\V1\Rest\Tindakan;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class TindakanResource extends Resource
{
	public function __construct() {
		parent::__construct();
		$this->service = new TindakanService();
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        return $this->service->simpan($data);
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
        $data = $this->service->load(array('ID' => $id));
        return array(
            "success" => count($data) > 0 ? true : false,
            "data" => $data
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
        if(isset($params['RUANGAN'])) {
            $params["t.STATUS"] = 1;
			$collection = $this->service->cariTindakan($params);
			return array(
				"success" => count($collection) > 0 ? true : false,
				"total" => count($collection),
				"data" => $collection
			);
		} else {
			$collection = $this->service->load($params);
			return array(
				"success" => count($collection) > 0 ? true : false,
				"total" => count($collection),
				"data" => $collection
			);
		}
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
		return $this->service->simpan($data);
        //return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
