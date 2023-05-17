<?php
namespace Aplikasi\V1\Rest\Teams;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class TeamsResource extends Resource
{
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
        return array(
			"status" => 200,
			"success" => true,
			"total" => 22,
			"data" => array(
				array("ID" => 1, "NAMA" => "Poentoro, S.Si., M.Kom", "ROLE" => "Project Manager"),
				
				array("ID" => 2, "NAMA" => "Hariansyah Erwin Noer, S.Kom", "ROLE" => "Development Team"),
				array("ID" => 3, "NAMA" => "Asrang", "ROLE" => "Development Team"),
				array("ID" => 4, "NAMA" => "Munawir, S.Kom", "ROLE" => "Development Team"),
				array("ID" => 5, "NAMA" => "Budirman, A.Md.Kom", "ROLE" => "Development Team"),
				array("ID" => 6, "NAMA" => "Zaldi, A.Md.Kom", "ROLE" => "Development Team"),
				array("ID" => 7, "NAMA" => "Asmadi, A.Md.Kom", "ROLE" => "Development Team"),
				array("ID" => 8, "NAMA" => "Muhammad Rizal Ibrahim, A.Md.Kom", "ROLE" => "Development Team"),
				array("ID" => 9, "NAMA" => "Andi Muhammad Ridwan Ahmad, A.Md.Kom", "ROLE" => "Development Team"),
				
				array("ID" => 10, "NAMA" => "Muchtamar, S.Sos", "ROLE" => "Bussiness Requirement Team"),
				array("ID" => 11, "NAMA" => "Aris Munandar Arsyad, SKM", "ROLE" => "Bussiness Requirement Team"),
				array("ID" => 13, "NAMA" => "Faizal, S.Sos", "ROLE" => "Bussiness Requirement Team"),
				
				array("ID" => 12, "NAMA" => "Nur Alamsyah, SE", "ROLE" => "Quality Assurance Team"),
				array("ID" => 14, "NAMA" => "Saddam Hussain, S.Pd", "ROLE" => "Quality Assurance Team"),
				array("ID" => 15, "NAMA" => "Achmad Zarkasyi, A.Md.Kom", "ROLE" => "Quality Assurance Team"),
				
				array("ID" => 16, "NAMA" => "Putry Dwi Sulistyarini, S.Si,Apt", "ROLE" => "Finance"),
				
				array("ID" => 17, "NAMA" => "Nur Alam", "ROLE" => "Support Team"),
				array("ID" => 18, "NAMA" => "Muhammad Abdi, S.Kom", "ROLE" => "Support Team"),
				
				//array("ID" => 19, "NAMA" => "Widya Mellysa", "ROLE" => "Implementator Team Palu"),
				array("ID" => 20, "NAMA" => "Amar Muhaimin", "ROLE" => "Implementator Team Palu"),
				
				array("ID" => 21, "NAMA" => "Eko Fitrianto, ST", "ROLE" => "Implementator Team Makassar"),
				array("ID" => 22, "NAMA" => "Nurul Baeti, S.Kom", "ROLE" => "Implementator Team Makassar"),
			),
			"detail" => "Teams ditemukan"
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
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
