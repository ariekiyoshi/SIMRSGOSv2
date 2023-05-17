<?php
namespace General\V1\Rest\TarifO2;

class TarifO2ResourceFactory
{
    public function __invoke($services)
    {
		$obj = new TarifO2Resource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
