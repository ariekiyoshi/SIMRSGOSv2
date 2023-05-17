<?php
namespace INASIService\V1\Rpc\Peserta;

class PesertaControllerFactory
{
    public function __invoke($controllers)
    {
        $sm = $controllers->getServiceLocator();
        
        $inasis = $sm->get('INASIService\Service');
        return new PesertaController($inasis);
    }
}
