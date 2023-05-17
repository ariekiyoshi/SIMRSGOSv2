<?php
namespace Layanan\V1\Rest\PetugasTindakanMedis;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class PetugasTindakanMedisResource extends Resource
{
	public function __construct() {
		parent::__construct();
		$this->service = new PetugasTindakanMedisService();
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		unset($data->id);
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
        $data = $this->service->load(array('TINDAKAN_MEDIS'=>$id));	
		
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
        $total = $this->service->getRowCount($params);
		$data = $this->service->load($params, array('*'), array('JENIS ASC', 'KE ASC'));	
		
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
		$keys = explode('-', $id);
        $data->TINDAKAN_MEDIS = $keys[0];
		$data->JENIS = $keys[1];
		$data->MEDIS = $keys[2];
		unset($data->id);
		return $this->service->simpan($data);
    }
}
