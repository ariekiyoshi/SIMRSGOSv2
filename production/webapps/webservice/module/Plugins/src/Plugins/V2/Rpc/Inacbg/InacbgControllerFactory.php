<?php
namespace Plugins\V2\Rpc\Inacbg;

class InacbgControllerFactory
{
    public function __invoke($controllers)
    {
        return new InacbgController($controllers);
    }
}
