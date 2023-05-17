<?php
namespace General\V1\Rest\TenagaMedis;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;
use General\V1\Rest\Dokter\DokterResource;
use General\V1\Rest\Perawat\PerawatResource;
use General\V1\Rest\Pegawai\PegawaiResource;

class TenagaMedisResource extends Resource
{
	private $dokter;
	private $tenagamedis = array();
	
	public function __construct() {
		parent::__construct();
		$this->tenagamedis[1] = new DokterResource();	
		$this->tenagamedis[2] = new DokterResource();
		$this->tenagamedis[3] = new PerawatResource();
		$this->tenagamedis[6] = new PegawaiResource();
		$this->tenagamedis[7] = new PegawaiResource();
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        return new ApiProblem(405, 'The POST method has not been defined');
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
		$jenis = $params['JENIS'];
		if(isset($jenis)) {
			unset($params['JENIS']);
			if($jenis > 3) {
				unset($params["RUANGAN"]);
				$maProfesi = array(
					6 => 2,
					7 => 10
				);
				$params["PROFESI"] = $maProfesi[$jenis];
			}
			//return $this->dokter->fetchAll($params);
			return $this->tenagamedis[$jenis]->fetchAll($params);
		}
		
		return array();
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
