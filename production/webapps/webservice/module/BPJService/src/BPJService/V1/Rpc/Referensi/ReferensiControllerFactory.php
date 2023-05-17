<?php
namespace BPJService\V1\Rpc\Referensi;

class ReferensiControllerFactory
{
    public function __invoke($controllers)
    {
		$bpjs = $controllers->get('BPJService\Service'); 
        return new ReferensiController($bpjs);
    }
}
