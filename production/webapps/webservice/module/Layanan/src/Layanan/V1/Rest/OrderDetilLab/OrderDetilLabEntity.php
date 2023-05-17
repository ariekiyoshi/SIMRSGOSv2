<?php
namespace Layanan\V1\Rest\OrderDetilLab;
use DBService\SystemArrayObject;

class OrderDetilLabEntity extends SystemArrayObject
{
	protected $fields = array('ORDER_ID'=>1, 'TINDAKAN'=>2, 'REF'=>3);
}
