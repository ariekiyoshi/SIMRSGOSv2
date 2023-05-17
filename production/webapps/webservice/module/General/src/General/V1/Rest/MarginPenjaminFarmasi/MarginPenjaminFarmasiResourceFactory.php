<?php
namespace General\V1\Rest\MarginPenjaminFarmasi;

class MarginPenjaminFarmasiResourceFactory
{
    public function __invoke($services)
    {
		$obj = new MarginPenjaminFarmasiResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
