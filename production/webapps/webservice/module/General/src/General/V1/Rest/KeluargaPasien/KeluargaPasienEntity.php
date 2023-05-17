<?php
namespace General\V1\Rest\KeluargaPasien;
use DBService\SystemArrayObject;

class KeluargaPasienEntity extends SystemArrayObject
{
	protected $fields = array('SHDK'=>0, 'NORM'=>1, 'JENIS_KELAMIN'=>2, 'ID'=>3, 'NAMA'=>4, 'ALAMAT'=>5, 'PENDIDIKAN'=>6, 'PEKERJAAN'=>7);	
}
