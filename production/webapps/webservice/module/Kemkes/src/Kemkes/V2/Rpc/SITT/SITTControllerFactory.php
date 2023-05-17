<?php
namespace Kemkes\V2\Rpc\SITT;

class SITTControllerFactory
{
    public function __invoke($controllers)
    {
        return new SITTController($controllers);
    }
}
