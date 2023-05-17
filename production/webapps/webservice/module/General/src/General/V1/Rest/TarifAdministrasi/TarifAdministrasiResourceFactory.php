<?php
namespace General\V1\Rest\TarifAdministrasi;

class TarifAdministrasiResourceFactory
{
    public function __invoke($services)
    {
		$obj = new TarifAdministrasiResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
