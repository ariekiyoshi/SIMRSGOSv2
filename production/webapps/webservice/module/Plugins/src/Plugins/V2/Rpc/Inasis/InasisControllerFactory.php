<?php
namespace Plugins\V2\Rpc\Inasis;

class InasisControllerFactory
{
    public function __invoke($controllers)
    {
        return new InasisController($controllers);
    }
}
