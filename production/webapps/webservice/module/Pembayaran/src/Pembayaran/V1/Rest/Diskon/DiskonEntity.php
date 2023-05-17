<?php
namespace Pembayaran\V1\Rest\Diskon;
use DBService\SystemArrayObject;

class DiskonEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'TAGIHAN'=>1, 'TANGGAL'=>1, 'ADMINISTRASI'=>1, 'AKOMODASI'=>1, 'SARANA_NON_AKOMODASI'=>1, 'PARAMEDIS'=>1, 'OLEH'=>1, 'STATUS'=>1);
}
