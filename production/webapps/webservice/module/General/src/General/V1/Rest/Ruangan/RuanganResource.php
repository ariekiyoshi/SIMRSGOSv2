<?php
namespace General\V1\Rest\Ruangan;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class RuanganResource extends Resource
{
    public function __construct() {
        parent::__construct();
        $this->service = new RuanganService();
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
        $data = $this->service->load(array('ID' => $id));
        return array(
            "success" => count($data) > 0 ? true : false,
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
        $params = is_array($params) ? $params : (array) $params;
        if(count($params) == 0 || (isset($params['TREE']) && !empty($params['TREE']))) {
            if(isset($params['TREE'])) unset($params['TREE']);
            if(isset($params['RUANGAN_AKSES'])) unset($params['RUANGAN_AKSES']);
            $collection = $this->service->loadTree($params);
            return array(
                "children" => $collection['data']
            );
        } else {
            if(isset($params['TREE'])) unset($params['TREE']);
            $ruanganAkses = false;
            $pengguna = '0';
            if(isset($params['RUANGAN_AKSES'])) {
                $ruanganAkses = true;
                unset($params['RUANGAN_AKSES']);
            }
            if(isset($params['PENGGUNA'])) {
                $pengguna = $params['PENGGUNA'];
                unset($params['PENGGUNA']);
            }
            if(isset($params['JENIS_KUNJUNGAN'])) {
                if(strpos($params['JENIS_KUNJUNGAN'], "]") > 0) {
                    $params['JENIS_KUNJUNGAN'] = (array) json_decode($params['JENIS_KUNJUNGAN']);
                }
            }
            if(isset($params['NOT'])) {
                $nots = (array) json_decode($params["NOT"]);
                foreach($nots as $key => $val) {
                    $params[] = new \Zend\Db\Sql\Predicate\NotIn($key, $val);
                }
                unset($params["NOT"]);
            }
            
            $total = $this->service->getRowCount($params);
            $columns = array('*', 'checked' => new \Zend\Db\Sql\Expression('true'));
            if($ruanganAkses) {
                $columns = array('*', 'checked' => new \Zend\Db\Sql\Expression("aplikasi.isSelectedPenggunaAksesRuangan(".$pengguna.", ruangan.ID)"));
            }
            
            $data = $this->service->load($params, $columns, array('ID ASC'));
            
            return array(
                "success" => $total > 0 ? true : false,
                "total" => $total,
                "data" => $data
            );
        }
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
        $data->ID = $id;
        return $this->service->simpan($data);
    }
}
