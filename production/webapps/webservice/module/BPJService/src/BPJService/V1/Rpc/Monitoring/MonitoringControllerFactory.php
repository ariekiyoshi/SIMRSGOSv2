<?php
namespace BPJService\V1\Rpc\Monitoring;

class MonitoringControllerFactory
{
    public function __invoke($controllers)
    {
		$bpjs = $controllers->get('BPJService\Service');         
        return new MonitoringController($bpjs);
    }
}
