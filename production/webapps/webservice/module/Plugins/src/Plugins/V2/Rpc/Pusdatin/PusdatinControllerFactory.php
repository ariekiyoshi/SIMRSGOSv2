<?php
namespace Plugins\V2\Rpc\Pusdatin;

class PusdatinControllerFactory
{
    public function __invoke($controllers)
    {
        return new PusdatinController($controllers);
    }
}
