<?php
namespace Cetakan\V1\Rest\KwitansiPembayaran;
use DBService\SystemArrayObject;

class KwitansiPembayaranEntity extends SystemArrayObject
{
	protected $fields = array('TAGIHAN'=>1, 'TANGGAL'=>1, 'NOMOR'=>1, 'TUNAI'=>1, 'NAMA'=>1,'OLEH'=>1, 'STATUS'=>1);
}