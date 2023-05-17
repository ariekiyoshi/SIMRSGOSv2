<?php
namespace General\V1\Rest\PaketDetil;
use DBService\SystemArrayObject;

class PaketDetilEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'PAKET'=>1, 'JENIS'=>1, 'ITEM'=>1, 'QTY'=>1, 'STATUS'=>1);
}
