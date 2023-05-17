<?php
namespace Layanan\V1\Rest\TindakanMedis;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class TindakanMedisResource extends Resource
{
	public function __construct() {
		parent::__construct();
		$this->service = new TindakanMedisService();
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		if($this->isAllowPrivilage('1102')) {
			$data->OLEH = $this->user;
			
			return $this->service->simpan($data);
			
		}else return new ApiProblem(405, 'Anda tidak memiliki akses Penginputan Tindakan');	
		
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
        $data = $this->service->load(array('tindakan_medis.ID'=>$id));	
		
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
        $total = $this->service->getRowCount($params);
		$data = array();
		if($total > 0) $data = $this->service->load($params, array('*'), array('TANGGAL DESC'));
		
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
		if($status = 0) {
			if(!$this->isAllowPrivilage('110803')) {
				return new ApiProblem(405, 'Anda tidak memiliki akses Pembatalan Tindakan');
			}			
		}

		$data->ID = $id;
		$data->OLEH = $this->user;
		return $this->service->simpan($data);
    }
}
