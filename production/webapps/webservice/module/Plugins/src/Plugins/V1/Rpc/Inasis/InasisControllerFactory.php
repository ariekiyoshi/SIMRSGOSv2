<?php
namespace Plugins\V1\Rpc\Inasis;

class InasisControllerFactory
{
    public function __invoke($controllers)
    {
        return new InasisController($controllers);
    }
}
