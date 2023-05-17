<?php
namespace BPJService\V1\Rpc\Peserta;

class PesertaControllerFactory
{
    public function __invoke($controllers)
    {
		$bpjs = $controllers->get('BPJService\Service');
		
        return new PesertaController($bpjs);
    }
}
