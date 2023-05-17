<?php
namespace Layanan\V1\Rest\OrderRad;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class OrderRadResource extends Resource
{
	public function __construct() {
		parent::__construct();
		$this->service = new OrderRadService();
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
		if($this->isAllowPrivilage('110304')) {
			$data->OLEH = $this->user;
			
			$cek = $this->service->load(array(
				'KUNJUNGAN' => $data->KUNJUNGAN, 
				'TANGGAL' => array(
					"VALUE" => $data->TANGGAL
				)			
			));
			if(count($cek) > 0) return new ApiProblem(405, 'Order Radiologi sudah terkirim');
			
			$data = $this->service->simpan($data);
			
			return array(
				"success" => true,
				"data" => $data
			);	
		}else return new ApiProblem(405, 'Anda tidak memiliki akses order radiologi');	
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
		$order = array("order_rad.TANGGAL ASC");
		if (isset($params->sort)) {
			$orders = json_decode($params->sort);
			if(is_array($orders)) {
			} else {
				$orders->direction = strtoupper($orders->direction);
				$orders->property = $orders->property == "TANGGAL" ? "order_rad.TANGGAL" : $orders->property;
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
		$status = isset($data->STATUS) ? $data->STATUS : 1;
		if($status != 0) {
			// untuk update
		} else {
			if($this->isAllowPrivilage('11080105')) {
				$data->OLEH = $this->user;
				$data = $this->service->simpan($data);
			
				return array(
					"success" => true,
					"data" => $data
				);
			}else return new ApiProblem(405, 'Anda tidak memiliki akses pembataraln radiologi');
		}
		
       
    }
}
