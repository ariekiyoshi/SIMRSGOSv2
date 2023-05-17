<?php
namespace Pembayaran\V1\Rest\PembayaranTagihan;

use ZF\ApiProblem\ApiProblem;
use DBService\Resource;

class PembayaranTagihanResource extends Resource
{
	public function __construct(){
		parent::__construct();
		$this->service = new PembayaranTagihanService();
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		$found = $this->service->masihAdaKunjunganBlmFinal($data->TAGIHAN);
		if(is_string($found)) {
			return new ApiProblem(428, '<b>Kunjungan:</b><br/> '.$found.' <b>Belum di Finalkan</b>');
		}
		
		$found = $this->service->masihAdaOrderKonsulMutasiYgBlmDiterima($data->TAGIHAN);
		if(is_string($found)) {
			return new ApiProblem(428, '<b>'.$found.'Status: Belum Diterima</b>');
		}
		
		$data->OLEH = $this->user;
		
		$isCreated = true;
		
		$cek = $this->service->load(array(
		    'TAGIHAN' => $data->TAGIHAN,
		    'JENIS' => $data->JENIS,
		    'STATUS' => 1
		));
		
		if(count($cek) > 0) return new ApiProblem(406, 'Tagihan ini sdh di finalkan');
		
		if($data->JENIS == 1) {
			$tanggal = $this->service->getTanggalTerakhirPembayaran($data->TAGIHAN, $data->JENIS);
			if($tanggal != null) {
				$cek = $this->service->load(array(
					'TAGIHAN' => $data->TAGIHAN,
					'TANGGAL' => $tanggal,
					'JENIS' => $data->JENIS
				));
				
				if(count($cek) > 0) {
					$total = $data->TOTAL;
					$data = $cek[0];
					$data["STATUS"] = 1;
					$data["TOTAL"] = $total;
					$isCreated = false;
				}							
			}
		}
		
		return $this->service->simpan($data, $isCreated);
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
		$total = $this->service->getRowCount($params);
		$data = $this->service->load($params);	
		
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
