<?php
namespace General\V1\Rest\KontakPasien;
use DBService\SystemArrayObject;

class KontakPasienEntity extends SystemArrayObject
{
	protected $fields = array('JENIS'=>0, 'NORM'=>1, 'NOMOR'=>2);
	
	public function getNorm() {
		return isset($this->storage['NORM']) ? $this->storage['NORM'] : 0;
	}
}

