<?php
namespace Plugins\V2\Rpc\Kemkes;

class KemkesControllerFactory
{
    public function __invoke($controllers)
    {
        return new KemkesController($controllers);
    }
}
