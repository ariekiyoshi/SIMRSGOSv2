<?php
namespace Pembayaran\V1\Rest\TagihanPendaftaran;
use DBService\SystemArrayObject;

class TagihanPendaftaranEntity extends SystemArrayObject
{
	protected $fields = array('TAGIHAN'=>1, 'PENDAFTARAN'=>1, 'UTAMA'=>1, 'STATUS'=>1);
}
