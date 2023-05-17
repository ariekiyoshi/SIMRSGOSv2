<?php
namespace Layanan\V1\Rest\ViewerRad;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class ViewerRadResource extends Resource
{
	private $api;
	
	public function __construct() {
		parent::__construct();
		$this->service = new Service();
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
		$requestData = isset($params["DATA"]);
		if($requestData) unset($params["DATA"]);
		$total = $this->service->getRowCount($params);
		$data = null;
		if($total > 0) $data = $this->service->load($params);
		
		if($requestData) {	
			return array(
				"status" => $total > 0 ? 200 : 404,
				"success" => $total > 0 ? true : false,
				"total" => $total,
				"data" => $data,
				"detail" => $total > 0 ? "Data viewer ditemukan" : "Data viewer tidak ditemukan"
			);
		}
		
		$config = $this->serviceManager->get("Config");
		$pacs = $config["services"]["PACService"];
		$this->api = $pacs["api"];
		if($total > 0) {
			$series = $this->getSeriesId($data[0]["PATIENT_ID"], $data[0]["STUDY_UID"], $data[0]["SERIES_UID"]);
			if(count($series) > 0) {
				header("Location: ".($this->api["url"].$this->api["viewer"])."?series=".$series[0]);
			}
		}
		
		exit;
			
        //return new ApiProblem(405, 'The GET method has not been defined for collections');
    }
	
	private function getSeriesId($patientID, $studyUID, $seriesUID) {
		$options = array(
			"url" => $this->api["url"]."tools/find",
			"method" => "POST",
			"data" => array(
				"Level" => "Series",
				"Query" => array(
					"0010,0020" => $patientID,
					"0020,000d" => $studyUID,
					"0020,000e" => $seriesUID
				)
			),
			"header" => array(
				"Authorization: Basic ".base64_encode($this->api["username"].":".$this->api["password"])
			)
		);
		return $this->sendRequest($options);
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
