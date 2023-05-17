<?php
namespace Kemkes\V1\Rest\BedMonitor;

class BedMonitorResourceFactory
{
    public function __invoke($services)
    {
    	$bmr = new BedMonitorResource($services);
    	$bmr->setServiceManager($services);
        return $bmr;
    }
}
