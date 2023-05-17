<?php
namespace Aplikasi\V1\Rest\Pengguna;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;
use Aplikasi\Password;

class PenggunaResource extends Resource
{
	public function __construct(){
		parent::__construct();
		$this->service = new PenggunaService();
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		$this->service->simpan($data);
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
		$columns = array('ID', 'LOGIN', 'NAMA', 'NIP', 'NIK', 'JENIS', 'STATUS');
		$pwd = null;
		if(isset($params['PASSWORD'])) {
			$columns[] = 'PASSWORD';
			$pwd = $params['PASSWORD'];
			unset($params['PASSWORD']);
		}
		
		$total = $this->service->getRowCount($params);		
		$data = $this->service->load($params, $columns, array('NAMA ASC'));		
		if($pwd) {
			$results = array();
			foreach($data as $row) {				
				if($row["PASSWORD"] == Password::encrypt($pwd, Password::TYPE_ENCRYPT_MD5_WITH_KEY)) {
					unset($row["PASSWORD"]);
					$results[] = $row;					
					break;
				}
				if($row["PASSWORD"] == Password::encrypt($pwd, Password::TYPE_ENCRYPT_MD5_ONLY)) {
					unset($row["PASSWORD"]);
					$results[] = $row;
					break;
				}
				if($row["PASSWORD"] == Password::encrypt($pwd, Password::TYPE_ENCRYPT_MYSQL_PASS)) {
					unset($row["PASSWORD"]);
					$results[] = $row;
					break;
				}
			}
			$total = count($results);
			$data = $results;
		}
		
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
		$this->service->simpan($data);
        //return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
