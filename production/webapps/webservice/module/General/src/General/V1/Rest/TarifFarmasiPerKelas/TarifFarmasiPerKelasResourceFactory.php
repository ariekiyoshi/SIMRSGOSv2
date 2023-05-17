<?php
namespace General\V1\Rest\TarifFarmasiPerKelas;

class TarifFarmasiPerKelasResourceFactory
{
    public function __invoke($services)
    {
		$obj = new TarifFarmasiPerKelasResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
