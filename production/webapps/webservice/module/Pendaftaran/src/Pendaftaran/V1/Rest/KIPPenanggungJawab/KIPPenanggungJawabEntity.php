<?php
namespace Pendaftaran\V1\Rest\KIPPenanggungJawab;
use DBService\SystemArrayObject;

class KIPPenanggungJawabEntity extends SystemArrayObject
{
	protected $fields = array('JENIS'=>0, 'ID'=>1, 'NOMOR'=>2, 'ALAMAT'=>3, 'RT'=>4, 'RW'=>5, 'KODEPOS'=>6, 'WILAYAH'=>7);
}
