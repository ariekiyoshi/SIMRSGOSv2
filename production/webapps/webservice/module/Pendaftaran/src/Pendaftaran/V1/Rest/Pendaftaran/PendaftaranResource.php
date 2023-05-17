<?php
namespace Pendaftaran\V1\Rest\Pendaftaran;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;
use General\V1\Rest\Ruangan\RuanganService;

class PendaftaranResource extends Resource
{
	private $kunjungan;
	private $ruangan;
	
	public function __construct() {
		parent::__construct();
		$this->service = new PendaftaranService();
		
		$this->ruangan = new RuanganService();
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		$ruangan = $this->ruangan->load(array('ID' => $data->TUJUAN['RUANGAN']));
		$jenis = $ruangan[0]['JENIS_KUNJUNGAN'];
		
		if($jenis == 1){
			if(!$this->isAllowPrivilage('1002')) {
				return new ApiProblem(405, 'Anda tidak memiliki akses rawat jalan');
			}
		} else if($jenis == 2){
			if(!$this->isAllowPrivilage('1003')) {
				return new ApiProblem(405, 'Anda tidak memiliki akses rawat darurat');
			}
		} else if($jenis == 3){
			if(!$this->isAllowPrivilage('1004')) {
				return new ApiProblem(405, 'Anda tidak memiliki akses rawat inap');
			}
		}
		
		$data->OLEH = $this->user;
		$result = $this->service->simpan($data);
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
        $data = $this->service->load(array('NOMOR'=>$id));	
		
		return array(
			"success" => count($data) > 0 ? true : false,
			"total" => count($data),
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
		if($this->isAllowPrivilage('1007')) {
			$data->OLEH = $this->user;
			return $this->service->simpan($data);
			
		}else return new ApiProblem(405, 'Anda tidak memiliki akses merubah data');	
		
        //return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
