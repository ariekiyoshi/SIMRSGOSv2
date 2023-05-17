<?php
namespace General\V1\Rest\Videos;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\Service;

class VideosService extends Service
{
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("videos", "master"));
		$this->entity = new VideosEntity();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('ID');
		$cek = $this->table->select(array("ID" => $id))->toArray();
		
		if(count($cek) > 0) {
			$this->table->update($this->entity->getArrayCopy(), array('ID' => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}
}
