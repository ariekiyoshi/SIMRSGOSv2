<?php
namespace Layanan\V1\Rest\Kelahiran;

class KelahiranResourceFactory
{
    public function __invoke($services)
    {
		$obj = new KelahiranResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
