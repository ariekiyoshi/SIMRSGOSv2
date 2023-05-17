<?php
namespace Plugins\V1\Rpc\Inacbg;

class InacbgControllerFactory
{
    public function __invoke($controllers)
    {
        return new InacbgController($controllers);
    }
}
