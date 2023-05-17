<?php
namespace INASIService\V1\Rpc\Kunjungan;

class KunjunganControllerFactory
{
    public function __invoke($controllers)
    {
        $sm = $controllers->getServiceLocator();
        
        $inasis = $sm->get('INASIService\Service');
        return new KunjunganController($inasis);
    }
}
