<?php
namespace Kemkes\V2\Rpc\Sisrute;

class SisruteControllerFactory
{
    public function __invoke($controllers)
    {
        return new SisruteController($controllers);
    }
}
