<?php
namespace General\V1\Rest\KAP;

use General\V1\Rest\Referensi\ReferensiService;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;

use BPJService\db\peserta\Service as PesertaBPJService;
use General\V1\Rest\PPK\PPKService;

class KAPService extends Service
{
	private $referensi;
	private $peserta = [];
	private $ppk;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("kartu_asuransi_pasien", "master"));
		$this->entity = new KAPEntity();
		
		$this->referensi = new ReferensiService();
		$this->peserta[2] = new PesertaBPJService();
		$this->ppk = new PPKService();
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);		
		foreach($data as &$entity) {
			$penjamin = $this->referensi->load(array('JENIS' => 10, 'ID' => $entity['JENIS']));
			if(count($penjamin) > 0) $entity['REFERENSI']['PENJAMIN'] = $penjamin[0];
			
			if(isset($this->peserta[$entity['JENIS']])) {
			    if($entity['JENIS'] == 2) {
			        $psrt = $this->peserta[$entity['JENIS']]->load(["noKartu" => $entity['NOMOR']]);
			        if(count($psrt) > 0) {
			            $ppk = $this->ppk->load(["BPJS" => $psrt[0]["kdProvider"]]);
			            if(count($ppk) > 0) $entity['REFERENSI']['PPK'] = $ppk[0];
			            //$entity['REFERENSI']['PESERTA'] = $psrt[0];
			        }
			    }
			}
		}
		
		return $data;
	}
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$norm = $this->entity->get('NORM');
		$jenis = $this->entity->get('JENIS');
		$cek = $this->table->select(array("NORM" => $norm, "JENIS" => $jenis))->toArray();
		if(count($cek) > 0) {
			$this->table->update($this->entity->getArrayCopy(), array('NORM' => $norm, "JENIS" => $jenis));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}
}
