<?php
namespace General\V1\Rest\TarifTindakan;

class TarifTindakanResourceFactory
{
    public function __invoke($services)
    {
		$obj = new TarifTindakanResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
