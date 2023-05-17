<?php
namespace Layanan\V1\Rest\PetugasTindakanMedis;
use DBService\SystemArrayObject;

class PetugasTindakanMedisEntity extends SystemArrayObject
{
	protected $fields = array('TINDAKAN_MEDIS'=>0, 'JENIS'=>1, 'MEDIS'=>2, 'STATUS'=>3);
}
