<?php
namespace General\V1\Rest\TarifRuangRawat;

class TarifRuangRawatResourceFactory
{
    public function __invoke($services)
    {
		$obj = new TarifRuangRawatResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
