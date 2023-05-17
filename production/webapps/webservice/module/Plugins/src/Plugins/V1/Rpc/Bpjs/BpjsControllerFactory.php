<?php
namespace Plugins\V1\Rpc\Bpjs;

class BpjsControllerFactory
{
    public function __invoke($controllers)
    {
        return new BpjsController($controllers);
    }
}
