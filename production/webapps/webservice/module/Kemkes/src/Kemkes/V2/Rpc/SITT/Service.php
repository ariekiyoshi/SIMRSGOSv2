<?php
namespace Kemkes\V2\Rpc\SITT;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

use General\V1\Rest\Referensi\ReferensiService;

class Service extends DBService
{
    private $referensi;
    
    protected $references = array(
        "Referensi" => true
    );
    
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("sitt", "kemkes"));
        $this->entity = new Entity();
        
        $this->setReferences($references);
        
        $this->includeReferences = $includeReferences;
        
        if($includeReferences) {
            if($this->references['Referensi']) $this->referensi = new ReferensiService();
        }
    }
    
    public function load($params = array(), $columns = array('*'), $orders = array()) {
        $data = parent::load($params, $columns, $orders);
        
        if($this->includeReferences) {
            foreach($data as &$entity) {
                if($this->references["Referensi"]) {
                    $referensi = $this->referensi->load(array("JENIS" => 2, "ID" => $entity["jenis_kelamin"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['jenis_kelamin'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 92, "ID" => $entity["nama_rujukan"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['nama_rujukan'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 93, "ID" => $entity["tipe_diagnosis"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['tipe_diagnosis'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 94, "ID" => $entity["klasifikasi_lokasi_anatomi"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['klasifikasi_lokasi_anatomi'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 95, "ID" => $entity["klasifikasi_riwayat_pengobatan"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['klasifikasi_riwayat_pengobatan'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 96, "ID" => $entity["klasifikasi_status_hiv"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['klasifikasi_status_hiv'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 97, "ID" => $entity["total_skoring_anak"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['total_skoring_anak'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 98, "ID" => $entity["konfirmasiSkoring5"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['konfirmasiSkoring5'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 99, "ID" => $entity["konfirmasiSkoring6"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['konfirmasiSkoring6'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 100, "ID" => $entity["paduan_oat"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['paduan_oat'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 101, "ID" => $entity["sumber_obat"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['sumber_obat'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 103, "ID" => $entity["sebelum_pengobatan_hasil_mikroskopis"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['sebelum_pengobatan_hasil_mikroskopis'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 104, "ID" => $entity["sebelum_pengobatan_hasil_tes_cepat"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['sebelum_pengobatan_hasil_tes_cepat'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 105, "ID" => $entity["sebelum_pengobatan_hasil_biakan"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['sebelum_pengobatan_hasil_biakan'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 106, "ID" => $entity["hasil_mikroskopis_bulan_2"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['hasil_mikroskopis_bulan_2'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 107, "ID" => $entity["hasil_mikroskopis_bulan_3"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['hasil_mikroskopis_bulan_3'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 108, "ID" => $entity["hasil_mikroskopis_bulan_5"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['hasil_mikroskopis_bulan_5'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 109, "ID" => $entity["akhir_pengobatan_hasil_mikroskopis"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['akhir_pengobatan_hasil_mikroskopis'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 110, "ID" => $entity["hasil_akhir_pengobatan"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['hasil_akhir_pengobatan'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 111, "ID" => $entity["hasil_tes_hiv"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['hasil_tes_hiv'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 112, "ID" => $entity["ppk"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['ppk'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 113, "ID" => $entity["art"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['art'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 114, "ID" => $entity["tb_dm"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['tb_dm'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 115, "ID" => $entity["terapi_dm"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['terapi_dm'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 117, "ID" => $entity["status_pengobatan"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['status_pengobatan'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 102, "ID" => $entity["foto_toraks"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['foto_toraks'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 118, "ID" => $entity["toraks_tdk_dilakukan"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['toraks_tdk_dilakukan'] = $referensi[0];
                    
                    $referensi = $this->referensi->load(array("JENIS" => 116, "ID" => $entity["pindah_ro"]));
                    if(count($referensi) > 0) $entity['REFERENSI']['pindah_ro'] = $referensi[0];
                }
            }
        }
        
        return $data;
    }
    
    public function simpan($data, $isCreated = false, $loaded = true) {
        $data = is_array($data) ? $data : (array) $data;
        $this->entity = new Entity();
        $this->entity->exchangeArray($data);
        
        if($isCreated) {
            $this->table->insert($this->entity->getArrayCopy());
            $id = $this->table->getLastInsertValue();
        } else {
            $id = $this->entity->get("ID");
            $this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
        }
        
        if($loaded) return $this->load(array("ID" => $id));
        return $id;
    }
}