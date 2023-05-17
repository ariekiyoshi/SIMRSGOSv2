<?php
namespace Informasi\V1\Rest\InfromasiRuangKamarTidur;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;
use DBService\DatabaseService;

use General\V1\Rest\Ruangan\RuanganService;

class InfromasiRuangKamarTidurResource extends Resource
{
	private $ruanganService;
	private $dbs;
	
	public function __construct() {
		parent::__construct();
		$this->ruanganService = new RuanganService();
		
		$this->dbs = DatabaseService::get("SIMpel");
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
        $RUANGAN = isset($params->RUANGAN) ? $params->RUANGAN : '';
        $KAMAR = isset($params->KAMAR) ? $params->KAMAR : '0';
		$STATUS = isset($params->STATUS) ? $params->STATUS : '0';
		$PASIEN = isset($params->PASIEN) ? $params->PASIEN : '';		
		$adapter = $this->dbs->getAdapter();
		$stmt = $adapter->query("CALL informasi.listRuangKamarTidur(?,?,?,?)");
		$info = $stmt->execute(array($RUANGAN, $KAMAR, $STATUS, $PASIEN));
		$data = array();
		foreach ($info as $row) {			
			$data[] = $row;
		}		
		try {
			$info->getResource()->closeCursor();
		} catch(\Exception $e) {
		}
        foreach ($data as &$d) {
			$ruangan = $this->ruanganService->load(array('ID' => $d['RUANGAN_ID']));
			if(count($ruangan) > 0) $d['REFERENSI']['RUANGAN'] = $ruangan[0];
		}
		
        return array(
			'success' => true,
			'total' => count($data),
			'data' => $data,
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
