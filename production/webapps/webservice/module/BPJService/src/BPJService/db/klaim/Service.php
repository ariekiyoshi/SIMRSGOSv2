<?php
namespace BPJService\db\klaim;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService {
	public function __construct() {
	    $this->config["entityName"] = "\\BPJService\\db\\klaim\\Entity";
	    $this->config["entityId"] = "noSEP";
	    $this->config["autoIncrement"] = false;
        $this->table = DatabaseService::get('BPJS')->get(new TableIdentifier("klaim", "bpjs"));
    }
    
    public function load($params = array(), $columns = array('*'), $orders = array()) {
        $data = parent::load($params, $columns, $orders);
        
        foreach($data as &$entity) {
            $entity["Inacbg"] = [
                "kode" => $entity["inacbg_kode"],
                "nama" => $entity["inacbg_nama"],
            ];
            
            $entity["peserta"] = [
                "noKartu" => $entity["peserta_noKartu"],
                "nama" => $entity["peserta_nama"],
                "noMR" => $entity["peserta_noMR"]
            ];
            
            $entity["biaya"] = [
                "byPengajuan" => $entity["biaya_byPengajuan"],
                "bySetujui" => $entity["biaya_bySetujui"],
                "byTarifGruper" => $entity["biaya_byTarifGruper"],
                "byTarifRS" => $entity["biaya_byTarifRS"],
                "byTopup" => $entity["biaya_byTopup"]
            ];
            
            unset($entity["inacbg_kode"]);
            unset($entity["inacbg_nama"]);
            
            unset($entity["peserta_noKartu"]);
            unset($entity["peserta_nama"]);
            unset($entity["peserta_noMR"]);
            
            unset($entity["byPengajuan"]);
            unset($entity["bySetujui"]);
            unset($entity["byTarifGruper"]);
            unset($entity["byTarifRS"]);
            unset($entity["byTopup"]);
        }
        
        return $data;
    }
}