<?php
namespace General\V1\Rest\Ruangan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\PenjaminRuangan\PenjaminRuanganService;
use General\V1\Rest\RuanganKelas\Service as RuanganKelasService;

class RuanganService extends Service
{
    private $referensi;
    private $penjaminRuangan;
    private $ruanganKelas;
    
    protected $references = array(
        'Referensi' => true,
        'PenjaminRuangan' => true,
        'RuanganKelas' => true
    );
    
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("ruangan", "master"));
        $this->entity = new RuanganEntity();
        $this->limit = 1000;
        
        $this->setReferences($references);
        
        $this->includeReferences = $includeReferences;
        
        if($includeReferences) {
            if($this->references['Referensi']) $this->referensi = new ReferensiService();
            if($this->references['PenjaminRuangan']) $this->penjaminRuangan = new PenjaminRuanganService();
            if($this->references['RuanganKelas']) $this->ruanganKelas = new RuanganKelasService(false);
        }
    }
    
    public function loadTree($params = array()) {
        $params['JENIS'] = 1;
        $data =  $this->queryRekursif($params);
        return array(
            'total' =>count($data),
            'data'=>$data
        );
    }
    
    public function load($params = array(), $columns = array('*'), $orders = array()) {
        $data = parent::load($params, $columns, $orders);
        
        if($this->includeReferences) {
            foreach($data as &$entity) {
                if($this->references['Referensi']) {
                    $referensi = $this->referensi->load(array('JENIS' => 15,'ID' => $entity['JENIS_KUNJUNGAN']));
                    if(count($referensi) > 0) $entity['REFERENSI']['JENIS_KUNJUNGAN'] = $referensi[0];
                }
                
                if($this->references['PenjaminRuangan']) {
                    $penjaminRuangan = $this->penjaminRuangan->load(array('RUANGAN_RS' => $entity['ID'], 'STATUS' => 1));
                    if(count($penjaminRuangan) > 0) $entity['REFERENSI']['PENJAMIN_RUANGAN'] = $penjaminRuangan;
                }
                
                if($this->references['RuanganKelas']) {
                    $ruanganKelas = $this->ruanganKelas->load(array('RUANGAN' => $entity['ID'], 'STATUS' => 1));
                    if(count($ruanganKelas) > 0) $entity['REFERENSI']['RUANGAN_KELAS'] = $ruanganKelas[0];
                }
            }
        }
        
        return $data;
    }
    
    public function simpan($data) {
        $data = is_array($data) ? $data : (array) $data;
        $this->entity->exchangeArray($data);
        //var_dump($data);
        $id = (int) $this->entity->get('ID');
        
        if($id == 0) {
            if(isset($data['PARENT'])) {
                $data['PARENT']['JENIS']++;
                $data['PARENT']['start'] = 0;
                $data['PARENT']['limit'] = 1;
                $cari = $this->query(array('*'), $data['PARENT'], false, array("ID DESC"));
                
                if(count($cari) > 0){
                    $this->entity->set('ID', $data['PARENT']['ID'].str_pad((intval(substr($cari[0]['ID'],-2))+1), 2, 0, STR_PAD_LEFT));
                } else {
                    $this->entity->set('ID', $data['PARENT']['ID'].'01');
                }
                $id = $this->entity->get('ID');
                $data = $this->entity->getArrayCopy();
                $this->table->insert($data);
            }
        } else {
            $this->table->update($this->entity->getArrayCopy(), array('ID' => $id));
        }
        
        return array(
            'success' => true,
            'data' => $this->load(array('ID' => $id))
        );
    }
    
    protected function query($columns, $params, $isCount = false, $orders = array()) {
        $params = is_array($params) ? $params : (array) $params;
        $select = $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
            if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
            else if(!$isCount) $select->columns($columns);
            
            if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {
                $select->offset((int) $params['start'])->limit((int) $params['limit']);
                unset($params['start']);
                unset($params['limit']);
            } else $select->offset(0)->limit($this->limit);
            
            if(isset($params['ID'])) {
                $select->where->like('ruangan.ID', $params['ID'].'%');
                unset($params['ID']);
            }
            if(isset($params['DESKRIPSI'])) {
                $select->where->like('DESKRIPSI', $params['DESKRIPSI'].'%');
                unset($params['DESKRIPSI']);
            }
            if(isset($params['PELAYANAN'])) {
                $select->where('(NOT JENIS_KUNJUNGAN = 0)');
                unset($params['PELAYANAN']);
            }
            
            /*$select->join(
             array('p' => new TableIdentifier('pengguna_akses_ruangan', 'aplikasi')),
             'p.RUANGAN LIKE CONCAT(ruangan.ID, ''%'')',
             array()
             );*/
            
            if($this->privilage) {
                //$select->where("EXISTS(SELECT 1 FROM aplikasi.pengguna_akses_ruangan par WHERE par.RUANGAN LIKE CONCAT(ruangan.ID, '%') AND par.STATUS = 1)");
            }
            
            $select->where($params);
            $select->order($orders);
            
            //echo $select->getSqlString();
        });
            return $select->toArray();
    }
    
    private function queryRekursif($params = array()){
        $row = $this->query(array('*'), $params, true);
        $row = (int) $row[0]['rows'];
        $data = $this->query(array('*'), $params, false, array('ID ASC'));
        
        if(count($data) == 0 && $params['JENIS'] < 5) {
            $params['JENIS'] = $params['JENIS'] + 1;
            return $this->queryRekursif($params);
        }
        
        $a=0;
        foreach($data as $dt){
            $params['ID'] = $dt['ID'];
            $params['JENIS'] = $dt['JENIS']+1;
            
            $referensi = $this->referensi->load(array('JENIS' => 15,'ID' => $dt['JENIS_KUNJUNGAN']));
            if(count($referensi) > 0) $data[$a]['REFERENSI']['JENIS_KUNJUNGAN'] = $referensi[0];
            
            $childs = $this->queryRekursif($params);
            if(count($childs) > 0){
                $data[$a]['children'] = $childs;
                $data[$a]['leaf'] = false;
                $data[$a]['cls'] = 'folder';
            }else{
                $data[$a]['leaf'] = true;
                $data[$a]['cls'] = 'file';
            }
            
            $a++;
        }
        
        return $data;
    }
}
