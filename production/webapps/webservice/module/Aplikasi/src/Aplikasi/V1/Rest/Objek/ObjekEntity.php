<?php
namespace Aplikasi\V1\Rest\Objek;

use DBService\SystemArrayObject;

class ObjekEntity extends SystemArrayObject
{
	protected $fields = array("ID"=>1, "TABEL"=>1, "ENTITY"=>1, "SERVICE"=>1, "STATUS"=>1);
}