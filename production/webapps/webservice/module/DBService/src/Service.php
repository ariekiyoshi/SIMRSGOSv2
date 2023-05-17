<?php
namespace DBService;

use Zend\Db\Sql\Select;

class Service
{
	protected $table;
	protected $entity;
	protected $columns;
	protected $limit = 100;
	
	protected $includeReferences = true;
	protected $references = array();
	protected $privilage = false;
	protected $user = '';
	protected $writeLog = true;
	
	/* data ini adalah data sebelum di lakukan perubahan pada fungsi create update delete / first load */
	protected $data;
	protected $firstLoad = true;
	
	protected $config = [
	    "entityName" => "Entity",
	    "entityId" => "ID",
	    "autoIncrement" => true
	];
	
	public function getRowCount($params = array()) {
		$params = (array) $params;
		if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {
			unset($params['start']);
			unset($params['limit']);
		}
		$result = $this->query(array('*'), $params, true);
		return (int) $result[0]['rows'];
	}
	
	public function setReferences($references = array(), $replace = false) {
		if($replace) {
			foreach($this->references as $key => $val) {
				$this->references[$key] = false;
			}
		}
		
		foreach($references as $key => $val) {
			$this->references[$key] = $val;
		}
	}
	
	public function setColumns($columns = array()) {
		$this->columns = $this->entity->getFilterFields($columns);
	}
	
	public function setPrivilage($privilage) {
		$this->privilage = $privilage;
	}
	
	public function getEntity() {
		$entity = isset($this->entity) ? $this->entity : null;
		$entity = ($entity instanceof SystemArrayObject) ? $entity : null;
		return $entity;
	}
	
	public function getTable() {
		return $this->table;
	}
	
	public function getData() {
		return $this->data;
	}
	
	public function isWriteLog() {
		return $this->writeLog;
	}
	
	public function setUser($user) {
		$this->user = $user;
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		if(isset($this->columns)) $columns = $this->columns;
		$result = $this->query($columns, $params, false, $orders); 
		if($this->firstLoad) {
			$this->data = $result;
			$this->firstLoad = false;
		}
		return $result;
	}
	
	public function simpan($data) {}
	
	public function simpanData($data, $isCreated = false, $loaded = true) {
	    $data = is_array($data) ? $data : (array) $data;
	    $entity = $this->config["entityName"];
	    $key = $this->config["entityId"];
	    $this->entity = new $entity();
	    $this->entity->exchangeArray($data);
	    
	    if($isCreated) {
	        $this->table->insert($this->entity->getArrayCopy());
	        if($this->config["autoIncrement"]) $id = $this->table->getLastInsertValue();
	        else $id = $id = $this->entity->get($key);
	    } else {
	        $id = $this->entity->get($key);
	        $this->table->update($this->entity->getArrayCopy(), [$key => $id]);
	    }
	    
	    if($loaded) return $this->load([$key => $id]);
	    return $id;
	}
	
	// data:application/vnd.ms-excel
	public function getFileContentFromPost($data, $validasiFormat = array(), $msg = "Salah upload tipe file") {
	    $data = base64_decode($data);
	    $datas = explode(";", $data);
	    $tipes = explode(":", $datas[0]);
	    $isVf = true;
	    foreach($validasiFormat as $vf) {
	        if($tipes[1] != $vf) {
	            $isVf = false;
	            break;
	        }
	    }
	    if(!$isVf) return new ApiProblem(422, $msg);
	    
	    $content = str_replace("base64,", "", $datas[1]);
	    
	    return array(
	        "tipe" => $tipes[1],
	        "content" => (base64_decode($content))
	    );
	}
	
	public function getFileContentFromBlob($data, $type) {
		return "data:".$type.";base64,".base64_encode($data);
	}
	
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				if(!$isCount) $select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
	
	public function execute($sql, $params = array()) {
	    $adapter = $this->table->getAdapter();
	    $stmt = $adapter->query($sql);
	    $data = array();
	    try {
	        $result = $stmt->execute($params);
	        foreach($result as $rst) {
	            $data[] = $rst;
	        }
	    } catch(\Exception $e) {}
	    return $data;
	}
	
	public function parseReferensi(&$entity) {
	    $table = $this->table->getTable();
	    $tableName = is_object($table) ? $table->getTable() : $table;
	    $data = [];
	    foreach($entity as $key => $val) {
	        $pos = strpos($key, $tableName);
	        if(!is_bool($pos) && $pos >= 0) {
	            $data[str_replace($tableName."_", "", $key)] = $val;
	            unset($entity[$key]);
	        }
	    }
	    
	    return $data;
	}
}