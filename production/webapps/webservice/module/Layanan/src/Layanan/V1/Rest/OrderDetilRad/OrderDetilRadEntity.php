<?php
namespace Layanan\V1\Rest\OrderDetilRad;
use DBService\SystemArrayObject;

class OrderDetilRadEntity extends SystemArrayObject
{
	protected $fields = array('ORDER_ID'=>1, 'TINDAKAN'=>2, 'REF'=>3);
}
