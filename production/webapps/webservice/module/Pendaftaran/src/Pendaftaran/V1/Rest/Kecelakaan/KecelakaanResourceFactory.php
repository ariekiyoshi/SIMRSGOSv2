<?php
namespace Pendaftaran\V1\Rest\Kecelakaan;

class KecelakaanResourceFactory
{
    public function __invoke($services)
    {
		$obj = new KecelakaanResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
