<?php
namespace General\V1\Rest\RuanganKelas;

class RuanganKelasResourceFactory
{
    public function __invoke($services)
    {
		$obj = new RuanganKelasResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
