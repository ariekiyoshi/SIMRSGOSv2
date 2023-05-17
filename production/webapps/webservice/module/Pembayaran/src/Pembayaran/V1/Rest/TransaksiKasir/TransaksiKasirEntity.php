<?php
namespace Pembayaran\V1\Rest\TransaksiKasir;
use DBService\SystemArrayObject;

class TransaksiKasirEntity extends SystemArrayObject
{
	protected $fields = array('NOMOR'=>1, 'KASIR'=>1, 'BUKA'=>1, 'TUTUP'=>1, 'STATUS'=>1);
}
