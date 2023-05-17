<?php
namespace Pendaftaran\V1\Rest\PembatalanKunjungan;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class PembatalanKunjunganResource extends Resource
{
	public function __construct() {
		parent::__construct();
		$this->service = new PembatalanKunjunganService();
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		if(!$this->isAllowPrivilage('110804')) {
			return new ApiProblem(405, 'Anda tidak memiliki akses untuk melakukan pembatalan final kunjungan');
		}
				
		$kunjungan = $this->service->getKunjungan();
		/* get kunjungan untuk mengambil nopen */
		$kjgns = $kunjungan->load(array('NOMOR' => $data->KUNJUNGAN));
		if(count($kjgns) > 0) {
			$kjgn = $kjgns[0];
			/* get pendaftaran */
			$ref = $kjgn['REFERENSI'];
			$pdftrn = $ref['PENDAFTARAN'];
			/* jika status pendaftaran selesai(sudah bayar) = 2 maka tolak */
			if($pdftrn['STATUS'] == 2) {
				return new ApiProblem(405, 'Pendaftaran untuk kunjungan ini sudah selesai / sudah bayar');
			}
		}
		
		$data->OLEH = $this->user;
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
		$prms = array_merge(array(), (array) $params);
        $total = $this->service->getRowCount($params);		
		$data = $this->service->load($prms);	
		
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
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
