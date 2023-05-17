<?php
namespace Aplikasi\V1\Rest\Teams;

class TeamsResourceFactory
{
    public function __invoke($services)
    {
		$obj = new TeamsResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
