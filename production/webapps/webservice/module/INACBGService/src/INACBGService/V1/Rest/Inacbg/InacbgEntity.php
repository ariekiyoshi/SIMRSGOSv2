<?php
namespace INACBGService\V1\Rest\Inacbg;
use DBService\SystemArrayObject;

class InacbgEntity extends SystemArrayObject
{
	protected $fields = array('JENIS'=>1, 'KODE'=>1, 'DESKRIPSI'=>1);
}
