<?php
namespace Plugins\V2\Rpc\Bpjs;

class BpjsControllerFactory
{
    public function __invoke($controllers)
    {
        return new BpjsController($controllers);
    }
}
