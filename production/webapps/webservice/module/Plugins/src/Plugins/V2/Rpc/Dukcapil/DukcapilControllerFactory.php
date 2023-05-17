<?php
namespace Plugins\V2\Rpc\Dukcapil;

class DukcapilControllerFactory
{
    public function __invoke($controllers)
    {
        return new DukcapilController($controllers);
    }
}
