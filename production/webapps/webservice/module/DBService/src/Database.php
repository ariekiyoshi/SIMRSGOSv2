<?php
namespace DBService;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\TableGateway\TableGateway;

class Database
{	
	private $adapter;
	
	public function __construct(AdapterInterface $adapter) {
        $this->adapter = $adapter;
    }
	
    public function get($name = '') {
        return new TableGateway($name, $this->adapter);
    }
	
	public function getAdapter() {
		return $this->adapter;
	}
}
