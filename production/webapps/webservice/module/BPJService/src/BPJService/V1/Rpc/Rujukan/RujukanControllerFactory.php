<?php
namespace BPJService\V1\Rpc\Rujukan;

class RujukanControllerFactory
{
    public function __invoke($controllers)
    {
		$bpjs = $controllers->get('BPJService\Service'); 
        return new RujukanController($bpjs);
    }
}
