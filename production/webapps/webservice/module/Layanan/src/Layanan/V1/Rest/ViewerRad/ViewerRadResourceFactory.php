<?php
namespace Layanan\V1\Rest\ViewerRad;

class ViewerRadResourceFactory
{
    public function __invoke($services)
    {
		$viewer = new ViewerRadResource();
		$viewer->setServiceManager($services);
        return $viewer;
    }
}
