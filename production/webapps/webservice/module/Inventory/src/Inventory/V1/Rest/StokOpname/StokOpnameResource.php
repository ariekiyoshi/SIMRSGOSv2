<?php
namespace Inventory\V1\Rest\StokOpname;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class StokOpnameResource extends Resource
{
	public function __construct() {
		parent::__construct();
		$this->service = new StokOpnameService();
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		$data->OLEH = $this->user;
		
		if(!isset($data->TANGGAL) || $data->TANGGAL == null) {
		    return new ApiProblem(422, "Tanggal Periode harus di isi", null, null, array("success" => false));
		}
		$result = $this->service->simpan($data);
		if(!$result["success"]) {
		    return new ApiProblem(422, "Gagal membuat periode stock opname, silahkan cek apakah ada periode stock opname yang masih di proses", null, null, array("success" => false));
		}
		
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
		$total = $this->service->getRowCount($params);
		$data = $this->service->load($params, array('*'), array('TANGGAL DESC', 'TANGGAL_DIBUAT DESC'));	
		
		return array(
			"success" => $total > 0 ? true : false,
			"total" => $total,
			"data" => $data
		);
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
		return $this->service->simpan($data);
        //return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
