<?php
namespace Pembayaran\V1\Rest\Deposit;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;
use Pembayaran\V1\Rest\Tagihan\TagihanService;

class DepositResource extends Resource
{
	public function __construct() {
		parent::__construct();
		$this->service = new DepositService();
		$this->tagihan = new TagihanService(false);
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		if(isset($data->TAGIHAN)) {
			$statusTagihan = $this->tagihan->getStatus($data->TAGIHAN);
			if($statusTagihan > -1) {
				$status = $statusTagihan == 2 ? 'difinalkan' : ($statusTagihan == 0 ? 'dibatalkan' : '');
				if($statusTagihan != 1) {
					return new ApiProblem(405, 'Tagihan sudah '.$status.', anda tidak dapat melakukan transaksi deposit');
				}
			}
		}
		$data->OLEH = $this->user;
		return $this->service->simpan($data);
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
		$total = $this->service->getRowCount($params);
		$data = $this->service->load($params, array('*'));	
		
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
		$dt = $this->service->load(array('ID' => $id));
        if(isset($dt[0]['TAGIHAN'])) {
			$statusTagihan = $this->tagihan->getStatus($dt[0]['TAGIHAN']);
			if($statusTagihan > -1) {
				$status = $statusTagihan == 2 ? 'difinalkan' : ($statusTagihan == 0 ? 'dibatalkan' : '');
				if($statusTagihan != 1) {
					return new ApiProblem(405, 'Tagihan sudah '.$status.', anda tidak dapat melakukan transaksi deposit');
				}
			}
		}
		$data->OLEH = $this->user;
		return $this->service->simpan($data);
    }
}
