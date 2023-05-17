<?php
namespace General\V1\Rest\DistribusiTarifTindakan;
use DBService\SystemArrayObject;

class DistribusiTarifTindakanEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'TARIF_TINDAKAN'=>1, 'JENIS'=>1, 'TARIF'=>1);
}
