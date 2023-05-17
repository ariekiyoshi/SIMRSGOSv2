<?php
namespace Pasien\V1\Rest\Pasien;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;
use General\V1\Rest\Pasien\PasienService;

class PasienResource extends Resource
{
	public function __construct() {
	    parent::__construct();
	    $this->authType = self::AUTH_TYPE_SIGNATURE_OR_LOGIN;
		$this->service = new PasienService();
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
        /*$data = $this->service->load(array("NORM" => $id)
			, array("NORM", "NAMA", "TEMPAT_LAHIR", "TANGGAL_LAHIR", "JENIS_KELAMIN", "ALAMAT")
		);
		if(count($data) > 0) {
			if(isset($data[0]["REFERENSI"])) unset($data[0]["REFERENSI"]);
			if(isset($data[0]["KARTUIDENTITAS"])) {
				foreach($data[0]["KARTUIDENTITAS"] as &$entity) {
					unset($entity["NORM"]);
					unset($entity["RT"]);
					unset($entity["RW"]);
					unset($entity["KODEPOS"]);
					unset($entity["WILAYAH"]);
					if(isset($entity["REFERENSI"])) {						
						unset($entity["REFERENSI"]);
					}
				}
			}
			if(isset($data[0]["KARTUASURANSI"])) {
				foreach($data[0]["KARTUASURANSI"] as &$entity) {
					unset($entity["NORM"]);
					if(isset($entity["REFERENSI"])) {				
						unset($entity["REFERENSI"]);
					}
				}
			}
			if(isset($data[0]["KONTAK"])) {
				foreach($data[0]["KONTAK"] as &$entity) {
					unset($entity["NORM"]);
				}
			}
		}*/
		$data = $this->service->load(array("NORM" => $id));
		$result =  array(
			"status" => count($data) > 0 ? 200 : 404,
			"data" => count($data) ? $data[0] : null,
			"detail" => count($data) > 0 ? "Pasien ditemukan" : "Pasien tidak ditemukan"
		);
		
		return $result;
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        return new ApiProblem(405, 'The GET method has not been defined for collections');
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
