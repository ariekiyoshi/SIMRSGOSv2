<?php
namespace BPJService\V1\Rpc\Kunjungan;

class KunjunganControllerFactory
{
    public function __invoke($controllers)
    {
		$bpjs = $controllers->get('BPJService\Service');        
        return new KunjunganController($bpjs);
    }
}
