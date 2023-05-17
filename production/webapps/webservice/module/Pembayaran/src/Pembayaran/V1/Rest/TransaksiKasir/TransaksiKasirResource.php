<?php
namespace Pembayaran\V1\Rest\TransaksiKasir;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class TransaksiKasirResource extends Resource
{
	public function __construct(){
		parent::__construct();
		$this->service = new TransaksiKasirService();
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		/* cek apakah masih open maka return */
		$isOpenKasir = $this->service->load(array(
			'start' => 0,
			'limit' => 1,
			'KASIR' => $this->user,
			'STATUS' => 1
		), array('*'), array('BUKA DESC'));
		
		if(count($isOpenKasir) > 0) {
			return array(
				'success' => true,
				'data' => $isOpenKasir
			);
		}
		$data->KASIR = $this->user;
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
		$data = $this->service->load($params, array('*'), array('BUKA DESC'));	
		
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
		$isCloseKasir = $this->service->load(array(
			'NOMOR' => $id,
			'STATUS' => 2
		));
		if(count($isCloseKasir) > 0) {
			return array(
				'success' => true,
				'data' => $isCloseKasir
			);
		}
		$data->KASIR = $this->user;
		return $this->service->simpan($data);
        //return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
