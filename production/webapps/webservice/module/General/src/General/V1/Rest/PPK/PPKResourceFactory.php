<?php
namespace General\V1\Rest\PPK;

class PPKResourceFactory
{
    public function __invoke($services)
    {
		$obj = new PPKResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
