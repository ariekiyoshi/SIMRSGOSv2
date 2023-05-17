<?php
namespace Pendaftaran\V1\Rest\Kunjungan;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;
use Layanan\V1\Rest\TindakanMedis\TindakanMedisService;
use Layanan\V1\Rest\Farmasi\FarmasiService;
use Layanan\V1\Rest\OrderResep\OrderResepService;

class KunjunganResource extends Resource
{
	private $ruangan;
	
	public function __construct() {
		parent::__construct();
		$this->service = new KunjunganService();
		$this->service->setPrivilage(true);
		$this->tindakanMedis = new TindakanMedisService();
		$this->farmasi = new FarmasiService();
		$this->orderfarmasi = new OrderResepService(true, array(
		    'Ruangan' => false,
		    'Referensi' => false,
		    'Dokter' => false,
		    'OrderDetil' => false,
		    'Kunjungan' => true
	    ));
	}
	
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		$data->OLEH = $this->user;
		$ref = isset($data->REF) ? "= '".$data->REF."'" : "IS NULL";
		
		if(isset($data->REF)){
			if(substr($data->REF, 0, 2) == 10){
				if(!$this->isAllowPrivilage('110102')) return new ApiProblem(405, 'Anda tidak memiliki akses penerimaan konsul');	
				
			} else if(substr($data->REF, 0, 2) == 11){
				if(!$this->isAllowPrivilage('110103')) return new ApiProblem(405, 'Anda tidak memiliki akses penerimaan mutasi');	

			} else if(substr($data->REF, 0, 2) == 12){
				if(!$this->isAllowPrivilage('110104')) return new ApiProblem(405, 'Anda tidak memiliki akses penerimaan order laboratorium');	

			} else if(substr($data->REF, 0, 2) == 13){
				if(!$this->isAllowPrivilage('110105')) return new ApiProblem(405, 'Anda tidak memiliki akses penerimaan order radiologi');	

			} else if(substr($data->REF, 0, 2) == 14){
				if(!$this->isAllowPrivilage('110106')) return new ApiProblem(405, 'Anda tidak memiliki akses penerimaan order resep');	
			}
		} else {
			if(!$this->isAllowPrivilage('110101')) return new ApiProblem(405, 'Anda tidak memiliki akses penerimaan pendaftaran kunjungan');				
		}
		
		$find = $this->service->load(array('NOPEN' => $data->NOPEN, 'RUANGAN' => $data->RUANGAN, new \Zend\Db\Sql\Predicate\Expression("REF ".$ref)));
		if(count($find) > 0) {
			return new ApiProblem(405, 'penerimaan pendaftaran kunjungan ini sudah diterima');	
		}
		
		$data = $this->service->simpan($data);
		
		return array(
			"success" => true,
			"data" => $data
		);
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
		$data = $this->service->load(array('NOMOR'=>$id));	
		
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
		$this->service->setUser($this->user);		
		$total = $this->service->getRowCount($params);
		$data = array();
		if($total > 0) {
			$data = $this->service->load($params, array('*'), array('MASUK DESC'));			
			
			foreach($data as &$entity) {
				if(isset($params['JENIS_KUNJUNGAN'])) {
					if($params['JENIS_KUNJUNGAN'] == 11) {
						$orderfarmasi = $this->orderfarmasi->load(array('NOMOR' => $entity['REF']));
						if(count($orderfarmasi) > 0) $entity['REFERENSI']['ASAL'] = $orderfarmasi[0];
					}
				}
			}
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
		if(isset($data->STATUS)) {
			if($data->STATUS == 0) {
				if(!$this->isAllowPrivilage('110802')) {
					return new ApiProblem(405, 'Anda tidak memiliki akses untuk melakukan pembatalan');
				} else {
					$rows = $this->tindakanMedis->load(array('KUNJUNGAN' => $id, 'STATUS' => 1));
					if(count($rows) > 0) {
						return new ApiProblem(405, 'Anda tidak dapat melakukan pembatalan kunjungan ini karena kunjungan ini sudah memiliki layanan tindakan');
					} else {
						$rows = $this->farmasi->load(array('KUNJUNGAN' => $id, 'STATUS' => 2));
						if(count($rows) > 0) {
							return new ApiProblem(405, 'Anda tidak dapat melakukan pembatalan kunjungan ini karena kunjungan ini sudah memiliki layanan farmasi');
						}
					}
				}
			}
		}
		$data = $this->service->simpan($data);
		return array(
			"success" => true,
			"data" => $data
		);
    }
}
