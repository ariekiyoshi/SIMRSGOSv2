<?php
namespace BPJService\db\pengajuan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

use BPJService\db\peserta\Service as PesertaService;

class Service extends DBService {
	private $peserta;
	
	public function __construct() {
        $this->table = DatabaseService::get('BPJS')->get(new TableIdentifier("pengajuan", "bpjs"));
		$this->peserta = new PesertaService();
    }

    public function simpan($data, $isCreated = false, $loaded = false) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity = new Entity();
		$this->entity->exchangeArray($data);
		$params = array(
			"noKartu" => $data["noKartu"],
			"tglSep" => $data["tglSep"],
			"jnsPelayanan" => $data["jnsPelayanan"]
		);
		if($isCreated) {
			$this->table->insert($this->entity->getArrayCopy());
		} else {
			$this->table->update($this->entity->getArrayCopy(), $params);
		}
		
		if($loaded) return $this->load($params);
		return true;
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			$peserta = $this->peserta->load(array('noKartu' => $entity['noKartu']));
			if(count($peserta) > 0) $entity['REFERENSI']['PESERTA'] = $peserta[0];
		}
		
		return $data;
	}
}