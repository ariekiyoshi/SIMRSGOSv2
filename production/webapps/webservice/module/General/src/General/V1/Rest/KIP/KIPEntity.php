<?php
namespace General\V1\Rest\KIP;
use DBService\SystemArrayObject;

class KIPEntity extends SystemArrayObject
{
	protected $fields = array('JENIS'=>0, 'NORM'=>1, 'NOMOR'=>2, 'ALAMAT'=>3, 'RT'=>4, 'RW'=>5, 'KODEPOS'=>6, 'WILAYAH'=>7);
	
	public function getNorm() {
		return isset($this->storage['NORM']) ? $this->storage['NORM'] : 0;
	}
}

