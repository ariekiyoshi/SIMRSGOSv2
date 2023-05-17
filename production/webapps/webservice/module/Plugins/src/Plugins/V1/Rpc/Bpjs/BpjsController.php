<?php
namespace Plugins\V1\Rpc\Bpjs;

use DBService\RPCResource;
use Zend\Json\Json;
use ZF\ApiProblem\ApiProblem;

class BpjsController extends RPCResource
{
	public function __construct($controller) {
	    $this->config = $controller->get('Config');
		$this->config = $this->config['services']['SIMpelService'];
		$this->config = $this->config['plugins']['BPJS'];
	}
	
	private function validateRequest($result) {
		try {
			$result = (array) Json::decode($result);			
			$data = isset($result['data']) ? $result['data'] : null;
			$api = null;
			if($data) {
				if($data->metadata) {					
					if($data->metadata->code == 200 && (trim($data->metadata->message) == '200' || trim($data->metadata->message) == 'OK' || trim($data->metadata->message) == 'Sukses')) {						
						$data = (array) $data->response;
					} else {
						$api = new ApiProblem(412, $data->metadata->message.'('.$data->metadata->code.')');
						$data = $api->toArray();
					}
				} else {
					$api = new ApiProblem(503, 'Metadata is null');
					$data = $api->toArray();
				}
			} else { // if data is null
				$api = new ApiProblem(503, 'Data is null');
				$data = $api->toArray();
			}
			
			if($api) $this->response->setStatusCode($api->__get('status'));
		} catch(\Exception $e) {
			$api = new ApiProblem(500, $result);
			$data = $api->toArray();
			$this->response->setStatusCode($api->__get('status'));
		}
		
		return $data;
	}
	
    public function getList()
    {
		return $this->validateRequest($this->sendRequest('peserta'));
    }
	
	/* Kepesertaan */
	/* cari peserta */
	public function get($id) 
	{
		$params = (array) $this->getRequest()->getQuery();
		$tgl = isset($params["tglSEP"]) ? $params["tglSEP"] : "";
		
		$data = $this->validateRequest($this->sendRequest('peserta/'.$id."?tglSEP=".$tgl));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data['peserta']
		);
	}
	
	public function nikAction() 
	{
		$id = $this->params()->fromRoute('id', 0);
		$params = (array) $this->getRequest()->getQuery();
		$tgl = isset($params["tglSEP"]) ? $params["tglSEP"] : "";
		$data = $this->sendRequest('peserta/nik/'.$id."?tglSEP=".$tgl);
		$data = $this->validateRequest($data);
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data['peserta']
		);
    }
	
	/* Kunjungan */
	/* buat SEP */
	public function create($data)
    {        
		$data = $this->validateRequest($this->sendRequest('kunjungan', 'POST', Json::encode($data)));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function updateSEPAction()
    {       
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('kunjungan/updateSEP', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function mappingDataTransaksiAction()
    {       
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('kunjungan/mappingDataTransaksi', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function updateTanggalPulangAction()
    {       
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('kunjungan/updateTanggalPulang', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function cekSEPAction()
    {    
		$id = $this->params()->fromRoute('id', 0);
		$data = Json::encode(array("noSEP"=>$id));
		$data = $this->validateRequest($this->sendRequest('kunjungan/cekSEP', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function batalkanSEPAction()
    {       
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('kunjungan/batalkanSEP', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function pengajuanSEPAction()
    {       
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('kunjungan/pengajuanSEP', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function aprovalPengajuanSEPAction()
    {       
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('kunjungan/aprovalPengajuanSEP', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function daftarPengajuanAction()
    {     
		$params = (array) $this->getRequest()->getQuery();		
		$noKartu = isset($params["noKartu"]) ? $params["noKartu"] : "";
		$start = isset($params["start"]) ? $params["start"] : 0;
		$limit = isset($params["limit"]) ? $params["limit"] : 25;
		$status = isset($params["status"]) ? $params["status"] : 1;
		
		$query = "?start=".$start."&limit=".$limit.($noKartu == "" ? "" : "&noKartu=".$noKartu).($status == "" ? "" : "&status=".$status);
		$data = $this->validateRequest($this->sendRequest('kunjungan/daftarPengajuan'.$query));
		if(isset($data['status'])) return $data;
		$list = isset($data["list"]) ? $data["list"] : null;
		$total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
		$data = $total > 0 ? $list : null;
		return array(
			'success' => true
			, 'total' => $total
			, 'data' => $data
		);
    }
	
	public function batalPengajuanSEPAction()
    {       
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('kunjungan/batalPengajuanSEP', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function kunjunganPesertaAction()
    {       
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('kunjungan/kunjunganPeserta', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function monitoringVerifikasiKlaimAction()
    {       
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('kunjungan/monitoringVerifikasiKlaim', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function riwayatPelayananPesertaAction()
    {       
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('kunjungan/riwayatPelayananPeserta', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function inacbgAction()
    {       
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('kunjungan/inacbg', 'POST', $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	/* Rujukan */
	public function cariRujukanDgnNoRujukanAction() 
	{				
		$id = $this->params()->fromRoute('id', 0);
		$data = $this->validateRequest($this->sendRequest('rujukan/cariNoRujukan/'.$id));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function cariRujukanDgnNoKartuBPJSAction() 
	{
		$id = $this->params()->fromRoute('id', 0);
		$data = $this->validateRequest($this->sendRequest('rujukan/cariNoKartuBPJS/'.$id));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function insertRujukanAction() 
	{
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('rujukan', "POST", $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function updateRujukanAction() 
	{
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('rujukan/'.$data->noRujukan, "PUT", $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function deleteRujukanAction() 
	{
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('rujukan/delete', "POST", $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	public function simpanRujukanAction() 
	{
		$data = Json::encode($this->getPostData($this->getRequest()));
		$data = $this->validateRequest($this->sendRequest('rujukan/simpan', "POST", $data));
		if(isset($data['status'])) return $data;
		return array(
			'success' => true,
			'data' => $data
		);
    }
	
	/* Referensi */
	public function poliAction() {
		$params = (array) $this->getRequest()->getQuery();
		$query = isset($params["query"]) ? "?query=".$params["query"] : "";
		
		$data = $this->validateRequest($this->sendRequest('referensi/poli'.$query));
		if(isset($data['status'])) return $data;
		$list = isset($data["list"]) ? $data["list"] : null;
		$total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
		$data = $total > 0 ? $list : null;
		return array(
			'success' => true
			, 'total' => $total
			, 'data' => $data
		);
	}
	
	public function diagnosaAction() {
		$params = (array) $this->getRequest()->getQuery();
		$query = isset($params["query"]) ? "?query=".$params["query"] : "";
		
		$data = $this->validateRequest($this->sendRequest('referensi/diagnosa'.$query));		
		if(isset($data['status'])) return $data;
		$list = isset($data["list"]) ? $data["list"] : null;
		$total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
		$data = $total > 0 ? $list : null;
		return array(
			'success' => true
			, 'total' => $total
			, 'data' => $data
		);
	}
	
	public function procedureAction() {
		$params = (array) $this->getRequest()->getQuery();
		$query = isset($params["query"]) ? $params["query"] : "";
		
		$data = $this->validateRequest($this->sendRequest('referensi/procedure?query='.$query));
		if(isset($data['status'])) return $data;
		$list = isset($data["list"]) ? $data["list"] : null;
		$total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
		$data = $total > 0 ? $list : null;
		return array(
			'success' => true
			, 'total' => $total
			, 'data' => $data
		);
	}
	
	public function kelasRawatAction() {
		$data = $this->validateRequest($this->sendRequest('referensi/kelasRawat'));
		if(isset($data['status'])) return $data;
		$list = isset($data["list"]) ? $data["list"] : null;
		$total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
		$data = $total > 0 ? $list : null;
		return array(
			'success' => true
			, 'total' => $total
			, 'data' => $data
		);
	}
	
	public function dokterAction() {
		$params = (array) $this->getRequest()->getQuery();
		$query = isset($params["query"]) ? $params["query"] : "";
		
		$data = $this->validateRequest($this->sendRequest('referensi/dokter?query='.$query));
		if(isset($data['status'])) return $data;
		$list = isset($data["list"]) ? $data["list"] : null;
		$total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
		$data = $total > 0 ? $list : null;
		return array(
			'success' => true
			, 'total' => $total
			, 'data' => $data
		);
	}
	
	public function spesialistikAction() {
		$data = $this->validateRequest($this->sendRequest('referensi/spesialistik'));
		if(isset($data['status'])) return $data;
		$list = isset($data["list"]) ? $data["list"] : null;
		$total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
		$data = $total > 0 ? $list : null;
		return array(
			'success' => true
			, 'total' => $total
			, 'data' => $data
		);
	}
	
	public function ruangRawatAction() {
		$data = $this->validateRequest($this->sendRequest('referensi/ruangRawat'));
		if(isset($data['status'])) return $data;
		$list = isset($data["list"]) ? $data["list"] : null;
		$total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
		$data = $total > 0 ? $list : null;
		return array(
			'success' => true
			, 'total' => $total
			, 'data' => $data
		);
	}
	
	public function caraKeluarAction() {
		$data = $this->validateRequest($this->sendRequest('referensi/caraKeluar'));
		if(isset($data['status'])) return $data;
		$list = isset($data["list"]) ? $data["list"] : null;
		$total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
		$data = $total > 0 ? $list : null;
		return array(
			'success' => true
			, 'total' => $total
			, 'data' => $data
		);
	}
	
	public function pascaPulangAction() {
		$data = $this->validateRequest($this->sendRequest('referensi/pascaPulang'));
		if(isset($data['status'])) return $data;
		$list = isset($data["list"]) ? $data["list"] : null;
		$total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
		$data = $total > 0 ? $list : null;
		return array(
			'success' => true
			, 'total' => $total
			, 'data' => $data
		);
	}
	
	public function faskesAction() {
		$params = (array) $this->getRequest()->getQuery();
		$nama = isset($params["nama"]) ? $params["nama"] : "";
		$jenis = isset($params["jenis"]) ? $params["jenis"] : "";
		$start = isset($params["start"]) ? $params["start"] : 0;
		$limit = isset($params["limit"]) ? $params["limit"] : 0;
		$data = $this->validateRequest($this->sendRequest('referensi/faskes?nama='.$nama.'&jenis='.$jenis.'&start='.$start.'&limit='.$limit));
		if(isset($data['status'])) return $data;
		$list = isset($data["list"]) ? $data["list"] : null;
		$total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
		$data = $total > 0 ? $list : null;
		return array(
			'success' => true
			, 'total' => $total
			, 'data' => $data
		);
	}
	
	/* Pencarian data DPJP
	 * @parameter
	 *   $params array(
	 *		"jenisPelayanan" => value | Jenis Pelayanan (1. Rawat Inap, 2. Rawat Jalan)
	 *		, "tglPelayanan" => value | Tgl.Pelayanan/SEP (yyyy-mm-dd)
	 *		, "kodeSpesialis" => value | Kode Spesialis/Subspesialis see referensi spesialistik
	 * )
	 */
	public function dpjpAction() {
	    $params = (array) $this->getRequest()->getQuery();
	    $query = count($params) > 0 ? "?".http_build_query($params) : "";
	    $data = $this->validateRequest($this->sendRequest("referensi/dpjp".$query));
	    if(isset($data['status'])) return $data;
	    $list = isset($data["list"]) ? $data["list"] : null;
	    $total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
	    $data = $total > 0 ? $list : null;
	    return array(
	        'success' => true
	        , 'total' => $total
	        , 'data' => $data
	    );
	}
	
	/* 
	 * Pencarian data Propinsi
	 */
	public function propinsiAction() {
	    $data = $this->validateRequest($this->sendRequest("referensi/propinsi"));
	    if(isset($data['status'])) return $data;
	    $list = isset($data["list"]) ? $data["list"] : null;
	    $total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
	    $data = $total > 0 ? $list : null;
	    return array(
	        'success' => true
	        , 'total' => $total
	        , 'data' => $data
	    );
	}
	
	/* Pencarian data Kabupaten
	 * @parameter
	 * $params array(
	 *		"kodePropinsi" => value
	 * )
	 */
	public function kabupatenAction() {
	    $params = (array) $this->getRequest()->getQuery();
	    $query = count($params) > 0 ? "?".http_build_query($params) : "";
	    $data = $this->validateRequest($this->sendRequest("referensi/kabupaten".$query));
	    if(isset($data['status'])) return $data;
	    $list = isset($data["list"]) ? $data["list"] : null;
	    $total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
	    $data = $total > 0 ? $list : null;
	    return array(
	        'success' => true
	        , 'total' => $total
	        , 'data' => $data
	    );
	}
	
	/* Pencarian data Kecamatan
	 * @parameter
	 * $params array(
	 *		"kodekabupaten" => value
	 * )
	 */
	public function kecamatanAction() {
	    $params = (array) $this->getRequest()->getQuery();
	    $query = count($params) > 0 ? "?".http_build_query($params) : "";
	    $data = $this->validateRequest($this->sendRequest("referensi/kecamatan".$query));
	    if(isset($data['status'])) return $data;
	    $list = isset($data["list"]) ? $data["list"] : null;
	    $total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
	    $data = $total > 0 ? $list : null;
	    return array(
	        'success' => true
	        , 'total' => $total
	        , 'data' => $data
	    );
	}
	
	/* Pencarian data Wilayah
	 * @parameter
	 * $params array(
	 *		"sesuai dgn parameternya masing2" => value,
	 *      "tipe" => value | 1. Propinsi, 2. Kabupaten, 3. Kecamatan
	 * )
	 */
	public function wilayahAction() {
	    $uris = [
	        1 => "referensi/propinsi",
	        2 => "referensi/kabupaten",
	        3 => "referensi/kecamatan"
	    ];
	    $params = (array) $this->getRequest()->getQuery();
	    $uri = $uris[1];
	    if(isset($params["tipe"])) {
            if(isset($uris[$params["tipe"]])) $uri = $uris[$params["tipe"]];
	        unset($params["tipe"]);
	    }
	    $query = count($params) > 0 ? "?".http_build_query($params) : "";
	    $data = $this->validateRequest($this->sendRequest($uri.$query));
	    if(isset($data['status'])) return $data;
	    $list = isset($data["list"]) ? $data["list"] : null;
	    $total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
	    $data = $total > 0 ? $list : null;
	    return array(
	        'success' => true
	        , 'total' => $total
	        , 'data' => $data
	    );
	}
	
	/* Monitoring */
	public function monitoringKunjunganAction()
    {     
		$params = (array) $this->getRequest()->getQuery();
		$tanggal = isset($params["tanggal"]) ? $params["tanggal"] : "";
		$jenis = isset($params["jenis"]) ? $params["jenis"] : "";
		$data = $this->validateRequest($this->sendRequest('monitoring/kunjungan?tanggal='.$tanggal.'&jenis='.$jenis));
		if(isset($data['status'])) return $data;
		$list = isset($data["list"]) ? $data["list"] : null;
		$total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
		$data = $total > 0 ? $list : null;
		return array(
			'success' => true
			, 'total' => $total
			, 'data' => $data
		);
    }
	
	public function monitoringKlaimAction()
    {     
		$params = (array) $this->getRequest()->getQuery();
		$tanggal = isset($params["tanggal"]) ? $params["tanggal"] : "";
		$jenis = isset($params["jenis"]) ? $params["jenis"] : "";
		$status = isset($params["status"]) ? $params["status"] : "";
		$data = $this->validateRequest($this->sendRequest('monitoring/klaim?tanggal='.$tanggal.'&jenis='.$jenis.'&status='.$status));
		if(isset($data['status'])) return $data;
		$list = isset($data["list"]) ? $data["list"] : null;
		$total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
		$data = $total > 0 ? $list : null;
		return array(
			'success' => true
			, 'total' => $total
			, 'data' => $data
		);
    }
    
    /* Monitoring Data Histori Pelayanan Peserta
     * @parameter
     *   $params array(
     *		"noKartu" => value | No.Kartu Peserta
     *		"tglMulai" => value | Tgl Mulai Pencarian (yyyy-mm-dd)
     *		"tglAkhir" => value | Tgl Akhir Pencarian (yyyy-mm-dd)
     *)
     *   $uri string
     */
    public function monitoringHistoriPelayananAction() {
        $params = (array) $this->getRequest()->getQuery();
        $query = count($params) > 0 ? "?".http_build_query($params) : "";
        $data = $this->validateRequest($this->sendRequest("monitoring/historiPelayanan".$query));
        if(isset($data['status'])) return $data;
        $list = isset($data["list"]) ? $data["list"] : null;
        $total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
        $data = $total > 0 ? $list : null;
        return array(
            'success' => true
            , 'total' => $total
            , 'data' => $data
        );
    }
    
    /* Monitoring Data Klaim Jaminan Jasa Raharja
	 * @parameter
	 *   $params array(
	 *		"tglMulai" => value | Tgl Mulai Pencarian (yyyy-mm-dd)
	 *		"tglAkhir" => value | Tgl Akhir Pencarian (yyyy-mm-dd)
	 *)
	 *   $uri string
     */
    public function monitoringKlaimJaminanJasaRaharjaAction() {
        $params = (array) $this->getRequest()->getQuery();
        $query = count($params) > 0 ? "?".http_build_query($params) : "";
        $data = $this->validateRequest($this->sendRequest("monitoring/klaimJaminanJasaRaharja".$query));
        if(isset($data['status'])) return $data;
        $list = isset($data["list"]) ? $data["list"] : null;
        $total = isset($data["count"]) ? $data["count"] : (isset($list) ? $list : 0);
        $data = $total > 0 ? $list : null;
        return array(
            'success' => true
            , 'total' => $total
            , 'data' => $data
        );
    }
}