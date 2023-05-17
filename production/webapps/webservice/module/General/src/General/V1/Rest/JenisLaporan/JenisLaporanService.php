<?php
namespace General\V1\Rest\JenisLaporan;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Select;
use DBService\System;
use DBService\Service;

class JenisLaporanService extends Service
{
	protected $limit = 1000;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("jenis_laporan", "master"));
		$this->entity = new JenisLaporanEntity();
    }
}
