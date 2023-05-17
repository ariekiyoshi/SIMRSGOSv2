<?php
namespace Pendaftaran\V1\Rest\PenanggungJawabPasien;
use DBService\SystemArrayObject;

class PenanggungJawabPasienEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>0, 'NOPEN'=>1, 'REF'=>2, 'SHDK'=>3, 'JENIS_KELAMIN'=>4, 'NAMA'=>5, 'ALAMAT'=>6
		, 'PENDIDIKAN'=>7, 'PEKERJAAN'=>8);
}
